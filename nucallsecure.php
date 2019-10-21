<?php 

	require_once('nucommon.php'); 
	
	$response                     = array();
	$response['DATA']             = '';
	$response['SUCCESS']          = false;
	$response['ERRORS']           = array();
	$GLOBALS['ERRORS']            = array();

	$hashData                     = nuHashData();
	$code                         = $_GET['c'];
	
	$sql                          = "SELECT * FROM  zzzsys_php WHERE slp_code = ?";
	$t                            = nuRunQuery($sql, array($code));
	$r                            = db_fetch_object($t);
	
	if(nuPHPAccess($r->zzzsys_php_id)){

		$r->slp_php 		  = nuGetSafePHP('slp_php', $r->zzzsys_php_id, $r->slp_php);
		$e                        = nuReplaceHashes($r->slp_php, $hashData);
		eval($e); 
		$response['DATA']         = $nuParameters;
		
		if($nuError != ''){
			$response['ERRORS'][] = $nuError;
		}
		
	}else{
		$response['ERRORS'][]     = "Access denied to PHP - ($r->slp_code)";
	}

	print json_encode($response);

?>
