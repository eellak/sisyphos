<?php 

require_once('nucommon.php'); 

$jsonID         = $_GET['i'];
$t              = nuRunQuery("SELECT deb_message AS json FROM zzzsys_debug WHERE zzzsys_debug_id = ? ", array($jsonID));
$r              = db_fetch_object($t);
$JSON           = json_decode($r->json);

$DATA           = $JSON->slp_php;
$ID             = $JSON->zzzsys_php_id;
$DATA		= nuGetSafePHP('slp_php', $ID, $DATA);

$TABLE_ID       = nuTT();
$hashData       = nuBuildHashData($JSON, $TABLE_ID);
$php            = nuReplaceHashes($DATA, $hashData);

eval($php);                                                                            //-- run php code

nuRunQuery("DELETE FROM zzzsys_debug WHERE zzzsys_debug_id = ? ", array($jsonID));

?>
