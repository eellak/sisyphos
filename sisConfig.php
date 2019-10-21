<?php
require_once("config.php");
$msg = 'OK';

if(isset($_POST['school_name']) && isset($_POST['DBHost']) && isset($_POST['DBUser'])  && isset($_POST['DBpwd'])  && isset($_POST['DBName'])  && isset($_POST['GApwd'])) {
	
	$school_name 	=	trim($_POST['school_name']);
	$DBHost			=	trim($_POST['DBHost']);
	$DBUser			=	trim($_POST['DBUser']);
	$DBName			=	trim($_POST['DBName']);
	$DBpwd			=	$_POST['DBpwd'];
	$GApwd			=	$_POST['GApwd'];
	
	if($school_name!='' && $DBHost!='' && $DBUser!='' && $DBName!='' && $DBpwd!='' && $GApwd!=''){
		if ($nuConfigDBGlobeadminPassword=='nu' || $nuConfigDBGlobeadminPassword==$GApwd){
			//$file   = realpath(dirname(__FILE__))."/config.php";
			$file   = "config.php";
			$contents = '<?php
    $nuConfigDBHost                 = "'.$DBHost.'";
    $nuConfigDBName                 = "'.$DBName.'";
    $nuConfigDBUser                 = "'.$DBUser.'";
    $nuConfigDBPassword             = "'.$DBpwd.'";
    $nuConfigDBGlobeadminPassword   = "'.$GApwd.'";
    $nuConfigtitle                  = "'.$school_name.'";	
?>';
				 
			$contents	= 	"\xEF\xBB\xBF".  $contents; // adds utf8 boom
			if(!file_put_contents($file,$contents)){
				$msg = 'Η εγγραφή των ρυθμίσεων στο αρχείο «config.php» απέτυχε! Παρακαλώ <b>τροποποιήσετε το αρχείο «config.php» χειροκίνητα</b> και ολοκληρώστε την εγκατάσταση ανοίγοντας το αρχείο <a href="sisInstall.php">sisInstall.php</a>';
			}
		} else{
			$msg = 'Λάθος Κωδικός Εγκαταστάτη!';
		}
	} else {
		$msg = 'Παρακαλώ συμπληρώστε όλα τα πεδία της φόρμας!';
	}
} else {
	$msg = 'Παρακαλώ συμπληρώστε όλα τα πεδία της φόρμας!';
}

if($msg!='OK'){
$page='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv=\'Content-type\' content=\'text/html;charset=UTF-8\'>

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta name="apple-mobile-web-app-capable" content="yes" />

<title>Σίσυφος|Εγκατάσταση(2/3)</title>
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
			
.psmall {
	color: #525c66;
	line-height: 150%;
	font-size: 12px;
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
			<br>
			<h2>'.$msg.'</h2>
		</span>
		<form method="POST" action="sisSetup.php">
			<input type="button" onclick="history.back();" value="<< Επιστροφή" style="width: 250px; height: 40px; top: 220px; left: 180px; position: absolute; border-top-left-radius: 5px; border-top-right-radius: 5px; border-bottom-right-radius: 5px; border-bottom-left-radius: 5px; font-size: 22px; line-height: 22px; background-color: rgb(212, 206, 230);">
		</form>

	</div>
</body>
</html>';
	echo $page;	
} else{
    header("Location: sisInstall.php");
    exit;
}
?> 