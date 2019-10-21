<?php
/*
class provider {
	
	function provider($user = '', $pass = '', $api_key = '', $orig = 'School'){//constructor, class initialization}
	
	function get_balance(){//query account balance}

    function get_SMS_report($code) {
    	//gets the SMS state with specific code-- do not implement this method if not provided by gateway
			// return associative array
				//$arr['state'] = state
								// '0' -> Άγνωστο
								// '1' -> Στάλθηκε
								// '2' -> Παραδόθηκε
								// '3' -> Απέτυχε
				//$arr['remarks'] = remarks		
	}	
	
	function get_delivery_reports(){
		//gets all pendind SMS delivery reports -- do not implement this method if function not provided by gateway
			// return associative (2d) array(code=>array('state'=>state,'remarks'=>remarks))
	}

    function sendSMS(	$mobile_number,	//	The target mobile phone number (including country code)
						$text = null){	//	The SMS body
						
			// return associative array
				//$arr['code']
				//$arr['state']
				//$arr['remarks']
	}
}
*/

class easysms{

	var $login 		= "YOUR_USERNAME";
	var $password 	= "YOUR_PASSWORD";
	var $api_key 	= "YOUR_API_KEY";
	var $originator = "School";
	    
    function easysms ( $user = '', $pass = '', $api_key = '', $orig = 'School') { 
			$this->login 		= $user;
			$this->password 	= $pass;
			$this->api_key 		= $api_key;
			$this->originator 	= $orig;
    }

    function get_balance() {
			$request = 'https://' . $this->login . ':' . $this->password . '@' . 'www.net2sms.gr/srvauth/'
						.	'index?cmd=easysms&action=get_balance&balance=true'	;

			return $this->navigate( $request );
    }

    function get_delivery_reports() {
		

			$request = 'https://' . $this->login . ':' . $this->password . '@' . 'www.net2sms.gr/srvauth/'
						. 'index?cmd=easysms&action=get_status&get_status=true'	;
			$result = $this->navigate( $request );
			
			$res = array();
			
			if($result!=''){
                $result_pieces = explode("|", $result);

                $arr_length = count($result_pieces);

                for ($i=0; $i<$arr_length; $i+=2) {
                	
					$state = ($result_pieces[$i+1]=='s' || $result_pieces[$i+1]=='1' ? '1' : ($result_pieces[$i+1]=='d' || $result_pieces[$i+1]=='2' ? '2' : ($result_pieces[$i+1]=='f' || $result_pieces[$i+1]=='3' ? '3' : '0')));
					$code = $result_pieces[$i];

					$res[$code] = array('state'=> $state, 'remarks'=>'');
				}
			}	
			return $res;
    }
/*
    function get_SMS_report($code) {
		return array('state'=> '0', 'remarks'=>'');
    }
*/
    function sendSMS( 	$mobile_number = null,	
						$text = null			
						) {
			$request = 'https://' . $this->login . ':' . $this->password . '@' . 'www.net2sms.gr/srvauth/'
						. 'index?cmd=easysms&action=send_sms'
						. '&mobile_number=' . urlencode($mobile_number)
						. '&text=' . urlencode($text)
						. '&request_delivery=true'
						. '&originator=' . urlencode($this->originator);
						
			$result = $this->navigate($request);
			$result_pieces = explode("|", $result);						
						
			$res = array();
			
			$res['state'] 		= $result_pieces[0]==1 ? 1 : 3;
			$res['code'] 		= $result_pieces[1];
			$res['remarks']		= '';
			
			return $res;
    }

	// Send the request and return the result -- PRIVATE function 
	private function  navigate( $request ){

		//Open URL for reading
		$handle = fopen ($request, 'r');
		if ( $handle ){
			//Get the response from the server
			$response = '';
			while ( $line = @fgets($handle,1024) ) { $response .= $line; }
			//Release the handle
			fclose ($handle);

			return $response;
		} else {
			return false;
		}
	}
}

// -------------------------------------------------------------------------------------------------------

class easysms3 {

	var $login 		= "YOUR_USERNAME";
	var $password 	= "YOUR_PASSWORD";
	var $api_key 	= "YOUR_API_KEY";
	var $originator = "School";
	    
    function easysms3 ( $user = '', $pass = '', $api_key = '', $orig = 'School') { 
			$this->login 		= $user;
			$this->password 	= $pass;
			$this->api_key 		= $api_key;
			$this->originator 	= $orig;
    }

    function get_balance() {
		$endpoint = 'https://easysms.gr/api/me/balance';
  		$parameters = array(
  			'key'       => $this->api_key,
  			'type'      => 'json'
  		);

  		$json = json_decode($this->call_endpoint($endpoint, $parameters), true);

  		if($json['status']=='1') 
  			return $json['balance'];
  		else
  			return 'N/A';
    }

    function get_delivery_reports() {

    	$endpoint = 'https://easysms.gr/api/status/get';

		$parameters = array(
			'key'       => $this->api_key,
			'type'      => 'json'
		);

		$json = json_decode($this->call_endpoint($endpoint, $parameters), true);
  		
  		if($json['status']=='1' and intval($json['total'])>0){

  			$res = array();

  			for($i=0; $i<intval($json['total']); $i++){
  				$s = $json[$i]['status'];
				$state = ($s=='s' || $s=='1' ? '1' : ($s=='d' || $s=='2' ? '2' : ($s=='f' || $s=='3' ? '3' : '0')));
  				$res[$sms['smsId']] = array('state'=> $state, 'remarks'=>'');
  			}
  			return $res;
  		}
  		else return;
    }

    function get_SMS_report($code) {

		$endpoint = 'https://easysms.gr/api/status/sms';

		$parameters = array(
			'key'       => $this->api_key,
			'smsId'     => $code,
			'type'      => 'json'
		);

		$json = json_decode($this->call_endpoint($endpoint, $parameters), true);
  		
  		if($json['status']=='1'){  			
  			if($s = $json['0']['status']){
				$state = ($s=='s' || $s=='1' ? '1' : ($s=='d' || $s=='2' ? '2' : ($s=='f' || $s=='3' ? '3' : '0')));
  				return array('state'=> $state, 'remarks'=>'');
  			} else return;
 		} else return;
    }

    function sendSMS( 	$mobile_number = null,	
						$text = null			
						) {
		
		$endpoint = 'https://easysms.gr/api/sms/send';

		$parameters = array(
			'key'     => $this->api_key,
			'text'    => $text,
			'from'    => $this->originator,
			'to'      => $mobile_number,
			'type'    => 'json'
		);
  		
  		$json = json_decode($this->call_endpoint($endpoint, $parameters), true);

		$res = array();
			
		$res['state'] 		= 	$json['status']=='1' ? '1' : '3';
		$res['code'] 		= 	array_key_exists ('smsId', $json ) ? $json['smsId'] : $json['id'];
		$res['remarks']		= 	($json['error'] == '0' ? '' : 
								($json['error']=='101' ? 'Λάθος κωδικός λογαριασμού' :
								($json['error']=='102' ? 'Δεν δόθηκε αριθμός τηλεφώνου' :
								($json['error']=='103' ? 'Μη έγκυρος αριθμός τηλεφώνου' :
								($json['error']=='104' ? 'Δεν δόθηκε αριθμός κείμενο' :
								($json['error']=='105' ? 'Ο λογαριασμός δεν έχει αρκετό υπόλοιπο για την αποστολή' :
								($json['error']=='106' ? 'Το μήνυμα δεν μπόρεσε να σταλεί' : 'Απροσδιόριστο σφάλμα')))))));
		return $res;
    }

	private function call_endpoint($endpoint, $parameters){

    	$c = curl_init();

    	curl_setopt($c, CURLOPT_URL, $endpoint);
    	curl_setopt($c, CURLOPT_POST, true);
    	curl_setopt($c, CURLOPT_POSTFIELDS, $parameters);
    	curl_setopt($c, CURLOPT_RETURNTRANSFER, true);

    	$output =  curl_exec($c);

    	curl_close($c);

    	return $output;
	}
}
?>
