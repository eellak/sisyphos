<?php require_once('nucommon.php'); ?>
<?php
	$emailSetup = $GLOBALS['nuSetup'];

	if (	
		$emailSetup->set_smtp_host == "" ||
		$emailSetup->set_smtp_from_address == "" ||
		$emailSetup->set_smtp_from_name == "" ||
		$emailSetup->set_smtp_port == "" ||
		$emailSetup->set_smtp_use_ssl == ""
	) {	
		header("Location: nuemailnoconfig.php");	
	}


	if ($_REQUEST['a'] == "") {
		$form_action = "nuemailsend.php";
	} else {
		//must be a valid PHP Code
		$form_action = "nucall.php?p=".$_REQUEST['a'];
	}

        $input_style = "style=\"width:250px;\"";
        $from = $emailSetup->set_smtp_from_address;
        $sus_login_name = $_REQUEST['uname'];

        if ($sus_login_name != "globeadmin") {
                $sqlUser = "SELECT * FROM zzzsys_user WHERE sus_name = '$sus_login_name' ";
                $rsUser  = nuRunQuery($sqlUser);
		if (db_num_rows($rsUser) > 0 ) {
			$objUser = db_fetch_object($rsUser);
	                $replyto        = $objUser->sus_email;
        	        $replytoname    = $objUser->sus_name;
			if ($replyto == "" || $replyto == null) {
				header("Location: nuemailnoconfig.php?m=1");
			}

		} else {
			header("Location: nuemailnoconfig.php?m=1");	
		}

        } else {

                $replyto        = $emailSetup->set_smtp_from_address;
                $replytoname    = "globeadmin";
        }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv='Content-type' content='text/html;charset=UTF-8'>
<title>Upload</title>
<link rel="stylesheet" href="jquery/jquery-ui.css" />
<script src="jquery/jquery-1.8.3.js" type='text/javascript'></script>
<script src="jquery/jquery-ui.js" type='text/javascript'></script>
<?php

jsinclude('nuformat.js');
jsinclude('nucommon.js');
jsinclude('nueditform.js');
jsinclude('nubrowseform.js');

print $GLOBALS['nuSetup']->set_css;

$i = $_GET['i'];
$h = $_SESSION['home'];
$t = $_SESSION['title'];

if ( isset($_REQUEST['subject']) ) {
	$subject = $_REQUEST['subject'];
} else {
	$subject = "";
}

if ( isset($_REQUEST['message']) ) {
	$message = $_REQUEST['message'];
} else {
	$message = "";
}

if ( isset($_REQUEST['filename']) ) {
        $filename = $_REQUEST['filename'];
} else {
        $filename = "";
}

print "

<script>

function getFilename() {
	return '$filename';
}

function getSubject() {
	return '$subject';
}

function getMessage() {
	return '$message';
}

function nuGetID(){
        return '$i';
}

function nuGetHome(){
        return '$h';
}

function nuGetTitle(){
        return '$t';
}

</script>

";

?>
<script>

	function toggleAjaxProgressStart() {
		$('#result').html("");
		$('#result').hide();
		$('#buttonDo').hide();
                $('#ajaxDo').show();

	}

	function toggleAjaxProgressEnd() {
		$('#result').show();
        	$('#ajaxDo').hide();
                $('#buttonDo').show();
	}

	$(function() {
		$('#emailcreate').submit(function(event) {
	
				
			toggleAjaxProgressStart();
						
			var form = $(this);
			$.ajax({
				type: form.attr('method'),
				url: form.attr('action'),
				data: $('#emailcreate').serialize(),
			}).done(function(data) {

				var obj = $.parseJSON(data);
				if (obj.DATA.email_result == 'debug') {
					$('body').html("<pre>"+obj.DATA.email_message+"</pre>");
				} else if (obj.DATA.email_result == false) {	
					var result = "<font color=red>"+obj.DATA.email_message+"</font>";
					$('#result').html(result);
	                                toggleAjaxProgressEnd();	
				} else {
					var result = "<font color=green>"+obj.DATA.email_message+"</font>";
					$('#result').html(result);
                                	toggleAjaxProgressEnd();
				}

			}).fail(function(data) {

				var result = "<font color=red>Internal Error</font>";
				$('#result').html(result);
				toggleAjaxProgressEnd();

    			});
    			event.preventDefault();
			
  		});
	});

	$(document).ready(function() {

		var i         = nuGetID();
        	var pSession  = nuGetParentSession();
        	var w         = document.defaultView.parent.nuSession.getWindowInfo(i,pSession);

		$('#nuBuilderData').val(JSON.stringify(w));
		
		if (getSubject() == '') {	
			$('#subject').val('nuBuilderReport '+w.bread_crumb[w.bread_crumb.length-1]['title'].replace(/ /g,'_'));
		} else {
			$('#subject').val(decodeURI(getSubject()));
		}

		if (getMessage() == '') {
			$('#message').val('Please see attached PDF file: nuBuilderReport_'+w.bread_crumb[w.bread_crumb.length-1]['title'].replace(/ /g,'_')+'.pdf');
		} else {
			$('#message').val(decodeURI(getMessage()));
		}

		if (getFilename() == '') {
			$('#attachment').val('nuBuilderReport_'+w.bread_crumb[w.bread_crumb.length-1]['title'].replace(/ /g,'_')+'.pdf');	
		} else {
			$('#attachment').val(decodeURI(getFilename()));
		}
	});


</script>
</head>
<body>

	<fieldset id="emailform" class="nuFieldset">
	<legend><p class="nuLegend">Email</p></legend>
	<form id="emailcreate" method="POST" action="<?=$form_action;?>";>
                	<dl>
                        	<dt><label for="to">To</label></dt>
				<dd><input <?=$input_style;?> type="text" name="to" id="to" value="<?php echo $_REQUEST['to'];?>" /></dd>

				<dt><label for="replytoname">Reply To Name</label></dt>
                                <dd><?=$replytoname;?></dd>

				<dt><label for="replyto">Reply To</label></dt>
				<dd><?=$replyto;?></dd>

				<dt><label for="from">From</label></dt>
                                <dd><?=$from;?></dd>	

				<dt><label for="subject">Subject</label></dt>
                                <dd><input <?=$input_style;?> type="text" name="subject" id="subject" value="" /></dd>

				<dt><label for="message">Message</label></dt>
                                <dd>
					<textarea id="message" name="message" rows="4" cols="47">
					</textarea>
				</dd>

				<dt style="background-color: #E1E8EA;">&nbsp;</dt>
                                <dd>	
					<input id="buttonDo" class="nuButton" type="submit" id="emailcreatedo" name="send" value="&nbsp;Send Report&nbsp;" />
					<img style="display: none" id="ajaxDo" src="ajax-loader.gif">
					<span style="display: none" id="result"></span>
				</dd>

			</dl>
			
	<input id="nuBuilderData" type="hidden" name="nuBuilderData" value="" />	
	<input id="from"          type="hidden" name="from"          value="<?=$from;?>" />
	<input id="replyto"	  type="hidden" name="replyto"       value="<?=$replyto;?>" />
	<input id="replytoname"   type="hidden" name="replytoname"   value="<?=$replytoname;?>" />
	<input id="attachment"    type="hidden" name="attachment"    value="" />
	<input id="debug"    	  type="hidden" name="debug"         value="<?=$_REQUEST['debug'];?>" />
	<input id="calltype"      type="hidden" name="calltype"      value="<?=$_REQUEST['calltype'];?>" />

	</form>
	</fieldset>
</body>
</html>

