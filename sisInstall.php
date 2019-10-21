<?php
	error_reporting( error_reporting() & ~E_NOTICE );
    //include("config.php");
	eval('?>' . file_get_contents('config.php'));
    include("sisInstall_lib.php");

    session_start();

    $_SESSION['DBHost']                 = $nuConfigDBHost;
    $_SESSION['DBName']                 = $nuConfigDBName;
    $_SESSION['DBUser']                 = $nuConfigDBUser;
    $_SESSION['DBPassword']             = $nuConfigDBPassword;
    $_SESSION['DBGlobeadminPassword']   = $nuConfigDBGlobeadminPassword;
    $_SESSION['title']                  = $nuConfigtitle;

    $template = new nuinstall();
    $template->setDB($_SESSION['DBHost'], $_SESSION['DBName'], $_SESSION['DBUser'], $_SESSION['DBPassword']);
	$template->checkInstall();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv='Content-type' content='text/html;charset=UTF-8'>

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta name="apple-mobile-web-app-capable" content="yes" />

<title>Σίσυφος|Εγκατάσταση(3/3)</title>
<link rel="shortcut icon" type="image/ico" href="favicon.ico">
<style>
.shadeHolder {
	border-bottom-left-radius:5px;
	border-bottom-right-radius:5px;
	border-top-right-radius:5px;
	border-top-left-radius:5px;
	box-shadow: 5px 5px 5px #888888;
	position:absolute;
	top:200px;
	left:200px;
	margin:auto;
	width:auto; 
	height:auto}
	
.pnormal {
	color: #525c66;
	line-height: 150%;
	font-size: 16px;
}	
			
.perror {
	color: #BB0000;
	line-height: 150%;
	font-size: 14px;
	font-weight: bold;
}

.pdisplay {
	color: #525c66;
	line-height: 150%;
	font-size: 14px;
}
</style>
</head>
<body bgcolor="#CCCCCC">

	<div 	class="shadeHolder" 
			style="	width: 700px; 
					height: 350px; 
					top: 20px; 
					left: 20px; 
					border: 1px solid grey; 
					position: absolute; 
					background-color: rgb(255, 255, 255);">

		<span style=" 	top: 15px; 
						left: 20px;  
						text-align: center; 
						font-size: 25px; 
						font-family: sans-serif; 
						position: absolute; 
						background-color: rgb(255, 255, 255);"> 
			<strong><span style="color: #d99b00; text-shadow: 1px 1px #D0D7D1;">Πληροφοριακό Σύστημα Σίσυφος - Εγκατάσταση</span></strong><br>
		</span>
		<span class="pnormal" style="top: 55px; left: 20px;  text-align: left; font-size: 15px; font-family: sans-serif; position: absolute; background-color: rgb(255, 255, 255);width: 650px;">

<?php
	if($template->initResult != 'CANNOT_CONNECT_TO_SERVER' && $template->initResult != 'DATABASE_NOT_CREATED' ) {
		if ( $template->initResult != 'SCHEMA_INCOMPLETE' ) {
			echo "
			<h2>Προσοχή!</h2><h2>Στη βάση δεδομένων που ορίσατε εντοπίστηκε παλαιότερη εγκατάσταση του Σίσυφου.</h2><h3>Δυστυχώς δεν διατίθεται η δυνατότητα αναβάθμισης.</h3><h3>Παρακαλώ στις παραμέτρους συστήματος να ορίσετε μια κενή βάση δεδομένων!<h3>
			<form method=\"POST\" action=\"sisSetup.php\">
				<input type=\"button\" onclick=\"history.back();\" value=\"<< Επιστροφή\" style=\"width: 250px; height: 40px; top: 220px; left: 180px; position: absolute; border-top-left-radius: 5px; border-top-right-radius: 5px; border-bottom-right-radius: 5px; border-bottom-left-radius: 5px; font-size: 22px; line-height: 22px; background-color: rgb(212, 206, 230);\">
			</form>";
		} else {	
			echo "
			<h2>Όλοι οι παράμετροι συστήματος έχουν οριστεί επιτυχώς!</h2>
			<h3>Είστε έτοιμοι για την εγκατάσταση!</h3>
			<br>
			<form method=\"POST\" action=\"sisInstall.php\">
				<table>
					<tr>
						<td>Κωδικός Εγκαταστάτη : </td> <td><input type=\"password\" name=\"pwd\" size=\"30\"></td>
					</tr>
					<tr>
						<td colspan=2>Διαγραφή των αρχείων εγκατάστασης, μετά από την επιτυχή εγκατάσταση (προτείνεται) : <input type=\"checkbox\" name=\"deletefiles\" value=\"delete\" checked></td>
					</tr>
				</table>
				<input type=\"submit\" value=\"Εγκατάσταση\" style=\"width: 250px; height: 40px; top: 200px; left: 180px; position: absolute; border-top-left-radius: 5px; border-top-right-radius: 5px; border-bottom-right-radius: 5px; border-bottom-left-radius: 5px; font-size: 22px; line-height: 22px; background-color: rgb(212, 206, 230);\">
			</form>";
		}	
		echo "	
		</span>";
	} else {
		echo "
		<h2>Προσοχή!</h2><h2>Δε κατέστη δυνατή η επικοινωνία με τη βάση δεδομένων.</h2><h3>Παρακαλώ ελέγξτε τις παραμέτρους συστήματος που ορίσατε στο προηγούμενο βήμα.<h3>
		<form method=\"POST\" action=\"sisSetup.php\">
			<input type=\"button\" onclick=\"history.back();\" value=\"<< Επιστροφή\" style=\"width: 250px; height: 40px; top: 200px; left: 180px; position: absolute; border-top-left-radius: 5px; border-top-right-radius: 5px; border-bottom-right-radius: 5px; border-bottom-left-radius: 5px; font-size: 22px; line-height: 22px; background-color: rgb(212, 206, 230);\">
		</form>";
	}
	echo "
	</div>";
	
	if (isset($_POST['pwd'])){
		if($_POST['pwd'] != $_SESSION['DBGlobeadminPassword'] ) {
			echo "
	<div class=\"shadeHolder\" style=\"width: 700px; height: 40px; top: 390px; left: 20px; border: 1px solid grey; position: absolute; background-color: rgb(255, 255, 255);\">
		<span class=\"perror\" style=\"top: 10px; left: 20px;  text-align: left; position: absolute; font-family: sans-serif; background-color: rgb(255, 255, 255);\">
			Λάθος Κωδικός Εγκαταστάτη!
		</span>
	</div>";
		} else {
			$template = new nuinstall();
			$template->setDB($_SESSION['DBHost'], $_SESSION['DBName'], $_SESSION['DBUser'], $_SESSION['DBPassword']);
			$template->checkInstall();
         
			if ( $template->initResult == 'SCHEMA_INCOMPLETE' ){
				$template->run();
			
				$height = '600px';
				$width  = '500px';
		
				echo "
		<div style=\"width: $width; height: $height; top: 390px; left: 20px; position: absolute; background-color: #CCCCCC;\">
			<span class=\"pdisplay\" style=\"top: 10px; left: 20px;  text-align: left; font-family: sans-serif; position: absolute; background-color: #CCCCCC;\">	
				<b>Η Εγκατάσταση ολοκληρώθηκε!</b><br>";
				if($template->showSQLerrors() == 0){
					if(isset($_POST['deletefiles'])){
						$res = $template->deleteInstallationFiles();
						echo "
					<br>Διαγραφή αρχείων εγκατάστασης :
					<table>
					";
						foreach ($res as $key => $value) {
							echo "
						<tr>	
							<td width=20> </td><td>Αρχείο : </td><td>".$key."</td><td> -> </td><td>".$value."</td>
						</tr>";
						}
					}
					echo "
					</table>
					<br>
					<b>Ας ξεκινήσουμε! <a href=\"index.php\">Σύνδεση</a></b>";
				}
				echo "
			</span>
		</div>";	
			}
		}
	}
?>
</body>
</html>
