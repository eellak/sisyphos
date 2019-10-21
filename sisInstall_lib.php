<?php
class nuinstall {

    var $debug           = true;
    var $DB              = array();
	var $lastSQLerror    = "";
	var $sqlErrors	     = array();
	var $initResult	     = 'UNKNOWN';

	function checkInstall() {

		if ( $this->checkDatabaseConnection() ) {
			if ( $this->checkDatabaseExists() ) {
				if ( $this->checkTableExists('zzzsys_setup') ) {
					$this->initResult = 'OK';
				} else {
					$this->initResult = 'SCHEMA_INCOMPLETE';
				}
			} else {
				$this->initResult = 'DATABASE_NOT_CREATED';
			}	
		} else {
			$this->initResult = 'CANNOT_CONNECT_TO_SERVER';
		}
	}

    function setDB($DBHost, $DBName, $DBUserID, $DBPassWord) {
        $this->DB['DBHost']       = $DBHost;
        $this->DB['DBName']       = $DBName;
        $this->DB['DBUserID']     = $DBUserID;
        $this->DB['DBPassWord']   = $DBPassWord;
    }
	
	function checkDatabaseConnection() {

		$DBHost         = $this->DB['DBHost'];
		$DBUserID       = $this->DB['DBUserID'];
		$DBPassWord     = $this->DB['DBPassWord'];
		$DBName         = $this->DB['DBName'];

		$con = false;
		$con = mysqli_connect($DBHost,$DBUserID,$DBPassWord);
		if ($con === false) {
			return false;
		} else {
			return true;
		}
    }

	function checkDatabaseExists() {

        $DBHost         = $this->DB['DBHost'];
        $DBUserID       = $this->DB['DBUserID'];
        $DBPassWord     = $this->DB['DBPassWord'];
        $DBName         = $this->DB['DBName'];

        $sdb = mysqli_connect($DBHost,$DBUserID,$DBPassWord,$DBName);

		if ( !$sdb ) {
			return false;
		} else {
			return true;
		}	
    }

	function checkTableExists($table) {

		$DBName                 = $this->DB['DBName'];
		$dbInfo                 = $this->DB;
		$dbInfo['DBName']       = "information_schema";
		$sql                    = "SELECT table_name FROM tables WHERE table_schema = '$DBName' AND table_name = '$table'";
		$rs                     = $this->runQuery($sql, $dbInfo);
		$num                    = mysqli_num_rows($rs);

		if ($num != 1 ) {
			return false;
		} else {
			return true;	
		}
	}
	function run() {
		$this->importTemplate();
		// CHANGE MANAGERS PASSWORD
		$sql='UPDATE zzzsys_user SET sus_login_password = md5(CONCAT(\'manager\', \''.$_SESSION['DBGlobeadminPassword'].'\')) WHERE zzzsys_user_id = \'54737fc85981449\'';
		$this->runQuery($sql, $this->DB);
	}

	function importTemplate() {
		
		$filename   = realpath(dirname(__FILE__))."/sisyphos.sql";
		
		$templine = '';

		if($lines = file($filename,FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)){

			foreach ($lines as $line){

				if (substr($line, 0, 2) == '--' || $line == '' || substr($line, 0, 2) == '/*' || substr($line, 0, 1) == '#')
					continue;

				$templine .= $line;

				if (substr(trim($line), -1, 1) == ';'){
					$this->runQuery($templine, $this->DB);
					$templine = '';
				}
			}
		} else {
			echo "Πρόβλημα κατά την εισαγωγή του αρχείου!";
			die();
		}
	}

	function showSQLerrors() {
		$cnt = count($this->sqlErrors);
		if ($cnt > 0){
            echo "<b>Παρουσιάστηκαν τα παρακάτω SQL λάθη :</b><br>";
            echo "<pre>";
            foreach (($this->sqlErrors) as $err){
					print("
					<table border=0>
						<tr>
							<td>Κωδικός</td>
							<td> : </td>
							<td>".$err[0]."</td>
						</tr>
						<tr>
							<td>Περιγραφή</td>
							<td> : </td>
							<td>".$err[1]."</td>
						</tr>
						<tr>
							<td>SQL Ερώτημα</td>
							<td> : </td>
							<td>".$err[2]."</td>
						</tr>
					</table>
					");
					//printf("Κωδικός : %s\n", $err[0]);
					//printf("Περιγραφή : %s\n", $err[1]);
					//printf("SQL Ερώτημα : %s\n\n", $err[2]);
			}
            echo "</pre>";
        } else {
            echo "<b>Δεν παρουσιάστηκαν SQL λάθη!</b><br>";
        }
		return $cnt;
	}
	
	
	function runQuery($pSQL, $dbInfo) {

		$DBHost 	= $dbInfo['DBHost'];
		$DBUserID	= $dbInfo['DBUserID'];
		$DBPassWord = $dbInfo['DBPassWord'];
		$DBName		= $dbInfo['DBName'];

		if (strpos($pSQL,'`root`@`localhost`') !== false) {
			$pSQL = str_replace('`root`@`localhost`','`'.$DBUserID.'`@`'.$DBHost.'`',$pSQL);
		}
		
		$con = mysqli_connect($DBHost,$DBUserID,$DBPassWord, $DBName);

		/* check connection */
		if (mysqli_connect_errno()) {
			printf ("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}

		/* change character set to utf8 */
		if (!mysqli_set_charset($con, "utf8")) {
			printf("Error loading character set utf8: %s\n", mysqli_error($con));
			exit();
		}
		
		$t = mysqli_query($con, $pSQL);
		
		$this->lastSQLerror = mysqli_error($con);

		if ( "" !=  mysqli_error($con)) {
			$errors[0] = mysqli_errno($con);
			$errors[1] = mysqli_error($con);
			$errors[2] = $pSQL;
			array_push($this->sqlErrors, $errors);
		}
        
		mysqli_close($con);
		return $t;
    }
	
	function deleteInstallationFiles() {
		$res = array();
		
		$res['sisSetup.html'] = unlink('sisSetup.html') ? 'Διεγράφη' : 'Απέτυχε';
		$res['sisSetup.php'] = unlink('sisSetup.php') ? 'Διεγράφη' : 'Απέτυχε';
		
		$res['sisConfig.php'] = unlink('sisConfig.php') ? 'Διεγράφη' : 'Απέτυχε';
		
		$res['sisInstall.php'] = unlink('sisInstall.php') ? 'Διεγράφη' : 'Απέτυχε';
		$res['sisInstall_lib.php'] = unlink('sisInstall_lib.php') ? 'Διεγράφη' : 'Απέτυχε';
		
		$res['sisyphos.sql'] = unlink('sisyphos.sql') ? 'Διεγράφη' : 'Απέτυχε';
		
		return $res;
	}
}
?>
