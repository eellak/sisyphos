<?php 
	require_once('nucommon.php'); 

	if (isset($_REQUEST['p'])){

		$values  = array($_REQUEST['p']);
		$sql     = "SELECT zzzsys_php_id, slp_php FROM zzzsys_php WHERE slp_code = ? AND slp_nonsecure = '1' ";
		$rs      = nuRunQuery($sql, $values);
		$num     = db_num_rows($rs);

		if ($num == 1) {
		
			$r          = db_fetch_object($rs);
			$r->slp_php = nuGetSafePHP('slp_php', $r->zzzsys_php_id, $r->slp_php);
			$e          = nuReplaceHashes($r->slp_php, $_REQUEST);

			eval($e); 
			
		} else {
		
			echo "Request is not allowed";
			
		}

	} else {

		echo "Request format is invalid";
		
	}	
?>
