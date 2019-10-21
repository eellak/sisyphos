<?php
$txt='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
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

<script>
function checkStrength(){
        
		password = document.forms["configForm"]["GApwd"].value;
        
		//if the password not set
		if (!password) { 
			alert ("Ο κωδικός εγκαταστάτη πρέπει να αποτελείται από τουλάχιστον οχτώ (8) χαρακτήρες!");
			return false;
		}
    
		//if the password length is less than 6
		if (password.length < 8) { 
			alert ("Ο κωδικός εγκαταστάτη πρέπει να αποτελείται από τουλάχιστον οχτώ (8) χαρακτήρες!");
			return false;
		}
		
		//Must have characters
		if (!password.match(/([a-zA-Z])/)){
		    alert ("Ο κωδικός εγκαταστάτη πρέπει να περιέχει τουλάχιστον ένα γράμμα!"); 
		    return false;
		} 
		
        //Must have numbers
		if (!password.match(/([0-9])/)){
		    alert ("Ο κωδικός εγκαταστάτη πρέπει να περιέχει τουλάχοστον ένα αριθμο!"); 
		    return false;
		} 
		
		//Must have at least one special character
		if (!password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) {
		    alert ("Ο κωδικός εγκαταστάτη πρέπει να περιέχει τουλάχιστον ένα ειδικό χαρακτήρα (!,%,&,@,#,$,^,*,?,_,~)!"); 
		    return false;
		}

		return true;
	}
</script>

</head>
<body bgcolor="#CCCCCC">

	<div 	class="shadeHolder" 
			style="	width: 700px; 
					height: 550px; 
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
			<h2>Παράμετροι Συστήματος</h2>

			<form name="configForm" method="POST" action="sisConfig.php" onsubmit="return(checkStrength());">
			<table border=0>
				<tr>
					<td><h3>Όνομα Σχολείου :</h3></td> <td><input type="text" name="school_name" size="37"></td>
				</tr>
				<tr>
					<td colspan=2><h3>MySQL Βάση δεδομένων</h3></td>
				</tr>
				<tr>
					<td>Εξυπηρετητής (Host Name ή IP) : </td> <td><input type="text" name="DBHost" size="30"></td>
				</tr>
				<tr>
					<td>Όνομα χρήστη : </td> <td><input type="text" name="DBUser" size="30"></td>
				</tr>
				<tr>
					<td>Κωδικός : </td> <td><input type="text" name="DBpwd" size="30"></td>
				</tr>
				<tr>
					<td>Όνομα βάσης δεδομένων : </td> <td><input type="text" name="DBName" size="30"></td>
				</tr>
				<tr>
					<td><h3>Κωδικός Εγκαταστάτη :</h3></td> <td><input type="text" name="GApwd" size="30"></td>
				</tr>
			</table>
			<h5>Προσοχή : Για λόγους ασφάλειας, ο κωδικός εγκαταστάτη ορίζεται εδώ για μία και μοναδική φορά!<br>Παρακαλώ επιλέξτε έναν πολύ ισχυρό κωδικό εγκαταστάτη και σημειώστε τον!<br>Πρέπει να είναι τουλάχιστον 8 χαρακτήρες και να περιέχει γράμματα αριθμούς και σύμβολα.</h5>
		</span>
		<input type="submit" value="Επόμενο >>" style="width: 250px; height: 40px; top: 445px; left: 400px; position: absolute; border-top-left-radius: 5px; border-top-right-radius: 5px; border-bottom-right-radius: 5px; border-bottom-left-radius: 5px; font-size: 22px; line-height: 22px; background-color: rgb(212, 206, 230);">
		</form>

	</div>


</body>
</html>';
echo $txt;
?>