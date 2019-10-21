#!/usr/bin/php
<?php
	$url 	= $argv[1];

	if ($url != "") {
	
	        $options = array (CURLOPT_RETURNTRANSFER => true, 
        	        CURLOPT_HEADER => false,
                	CURLOPT_USERAGENT => "PHP cURL",
	                CURLOPT_CONNECTTIMEOUT => 120, 
        	        CURLOPT_TIMEOUT => 120 
	        );
	
        	$ch = curl_init($url);
	        curl_setopt_array($ch, $options);

        	$response = curl_exec($ch);

	        $result  = array();
        	$result['content']      = $response; 
	        $result['err']          = curl_errno($ch);
        	$result['errmsg']       = curl_error($ch);
	        $result['header']       = curl_getinfo($ch);
        	$result['httpCode']     = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if ($result['httpCode'] == 200 ) {
			echo $response;
		}

	        curl_close ( $ch );
	} else {
		echo "Usage: nucronwrapper.php <url> \n";
	}
?>
