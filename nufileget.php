<?php 

require_once('nucommon.php');

$s = "SELECT * FROM zzzsys_file WHERE sfi_code = ? ";
$t = nuRunQuery($s, array($_GET['i']));
$r = db_fetch_object($t);
$f = "'Content-type: $r->sfi_type'";

Header($f); 

print $r->sfi_blob; 

?>
