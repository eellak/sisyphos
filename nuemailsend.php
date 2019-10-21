<?php require_once('nucommon.php'); ?>
<?php
	if ( isset($_SERVER['HTTPS']) ) {
        	$request_url = "https://";
	} else {
        	$request_url = "http://";
	}

	$this_url = $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        $this_url = explode("/",$this_url);
        for ($x=0; $x<count($this_url)-1;$x++) {
	        $request_url .= $this_url[$x]."/";
	}

	if ( trim($_REQUEST['calltype']) == 'runphp' ) {
        	$request_url .= "nurunphp.php?i=".$_REQUEST['debug'];
	} else if ( trim($_REQUEST['calltype']) == 'printpdf' ) {
        	$request_url .= "nurunpdf.php?i=".$_REQUEST['debug'];
	} else {
        	$response['DATA']['email_result'] = false;
                $response['DATA']['email_message'] = "Invalid Request Type: ".$_REQUEST['calltype'];
                echo json_encode($response);
                die();
	}

	if ( nuValidateEmailAddress($_REQUEST['to']) ) {

		$to 		= $_REQUEST['to'];
		$replyto 	= $_REQUEST['replyto'];
		$subject	= $_REQUEST['subject'];
		$content	= $_REQUEST['message'];
		$html 		= false;
		$wordWrap 	= 120;
		$filelist	= array();

		$filename		= $_REQUEST['attachment'];
		$report 		= nuEmailGetReportFile('',$request_url);
		$filelist[$filename] 	= $report; 			

		$result         = nuSendEmail($to, $replyto, $content, $html, $subject, $wordWrap, $filelist);

		@unlink($report);

		$response['DATA']['email_result'] = $result[0];
		$response['DATA']['email_message'] = $result[1];
	
	 	echo json_encode($response);		

	} else {

		$response['DATA']['email_result'] = false;
		$response['DATA']['email_message'] = "Invalid Email Address";
		echo json_encode($response);

	}

?>
