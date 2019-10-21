<?php
	$message1 = "Email delivery is not configured for your account";
	$message2 = "Email delivery is not configured for this site";
	if ( $_REQUEST['m'] == 1 ) {
		$message = $message1;
	} else {
		$message = $message2;
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv='Content-type' content='text/html;charset=UTF-8'>
<title></title>
</head>
<body>

	<br><br>
	<fieldset>
	<legend><p>Email</p></legend>
		<p><?=$message;?></p>	
	</fieldset>
</body>
</html>

