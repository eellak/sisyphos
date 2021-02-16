<?php
require_once('config.php'); 

if (session_status() == PHP_SESSION_NONE) { // PHP7 added

	$conn = new mysqli($nuConfigDBHost, $nuConfigDBUser, $nuConfigDBPassword, $nuConfigDBName);
	if (!($conn->connect_error)) {
		$sql 	= "Select set_time_out_minutes From zzzsys_setup Where zzzsys_setup_id='1'";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		$gcLifetime = $row["set_time_out_minutes"] * 60;
	} else {
		$gcLifetime = 7200;
	}
	$conn->close();

	@ini_set("session.gc_maxlifetime", $gcLifetime); 
} // PHP7 added

session_start();

error_reporting( error_reporting() & ~E_NOTICE );

$_SESSION['DBHost']                 = $nuConfigDBHost;
$_SESSION['DBName']                 = $nuConfigDBName;
$_SESSION['DBUser']                 = $nuConfigDBUser;
$_SESSION['DBPassword']             = $nuConfigDBPassword;
$_SESSION['DBGlobeadminPassword']   = $nuConfigDBGlobeadminPassword;
$_SESSION['title']                  = $nuConfigtitle; 
$_SESSION['IsDemo']                 = (isset($nuConfigIsDemo) ? $nuConfigIsDemo : false); 
$_SESSION['SafeMode']               = (isset($nuConfigSafeMode) ? $nuConfigSafeMode : false);
$_SESSION['SafePHP']                = (isset($nuConfigSafePHP) ? $nuConfigSafePHP : array());
	
require_once('nudatabase.php');

set_time_limit(0);
mb_internal_encoding('UTF-8');

$setup                           = $GLOBALS['nuSetup'];                                   //--  setup php code just used for this database

nuClientTimeZone();

//==================FUNCTIONS============================================================
		// PHP7 function added
function myCount($a){
	return (is_array($a) ? count($a) : 0);	
}


function nuClientTimeZone(){

    global $setup;

    // get time zone setting
    if ($setup->set_timezone){
            $zone = $setup->set_timezone;
    } else {
            $zone = "Australia/Adelaide";
    }

    // set time zone setting for PHP
    date_default_timezone_set($zone);

    // calculate offset
    $dateObj        = new DateTime();
    $mins           = $dateObj->getOffset() / 60;
    $sgn            = ($mins < 0 ? -1 : 1);
    $mins           = abs($mins);
    $hrs            = floor($mins / 60);
    $mins          -= $hrs * 60;
    $offset         = sprintf('%+d:%02d', $hrs*$sgn, $mins);

    // set timezone setting for MYSQL
    nuRunQuery("SET time_zone = '$offset'");       
 
}


function nuDebug($t){

    global $nuDB;

    $i = nuID();
    $d = date('Y-m-d H:i:s');
    $s = $nuDB->prepare("INSERT INTO zzzsys_debug (zzzsys_debug_id, deb_message, deb_added) VALUES (? , ?, ?)");

    $s->execute(array($i, $t, $d));
    
    if($nuDB->errorCode() !== '00000'){
        error_log($nuDB->errorCode() . ": Could not establish nuBuilder database connection");
    }

}



function jsinclude($pfile){

	$timestamp = date("YmdHis", filemtime($pfile));                                         //-- Add timestamp so javascript changes are effective immediately
	print "<script src='$pfile?ts=$timestamp' type='text/javascript'></script>\n";
    
}



function cssinclude($pfile){

	$timestamp = date("YmdHis", filemtime($pfile));                                         //-- Add timestamp so javascript changes are effective immediately
	print "<link rel='stylesheet' href='$pfile?ts=$timestamp' />\n";
    
}


function nuTT(){

	return '___nu'.uniqid('1').'___';                                                         //--create a unique name for a Temp Table
    
}


function nuRunPHP($c){

	if($GLOBALS['phpcode_'.$c] == 1){return;}   //-- load only once
	
	$GLOBALS['phpcode_'.$c] = 1;
    $s                      = "SELECT * FROM zzzsys_php WHERE slp_code = ? ";
    $t                      = nuRunQuery($s, array($c));
    $r                      = db_fetch_object($t);

    $r->slp_php = nuGetSafePHP('slp_php', $r->zzzsys_php_id, $r->slp_php);

    $e                      = nuReplaceHashes($r->slp_php, $GLOBALS['latest_hashData']);
    
    eval($e);

}


function hex2rgb($hexOrColor) {

    $hex   = ColorToHex($hexOrColor);
    $hex   = str_replace("#", "", $hex);

    if(strlen($hex) == 3) {
        $r = hexdec(substr($hex,0,1).substr($hex,0,1));
        $g = hexdec(substr($hex,1,1).substr($hex,1,1));
        $b = hexdec(substr($hex,2,1).substr($hex,2,1));
    } else {
        $r = hexdec(substr($hex,0,2));
        $g = hexdec(substr($hex,2,2));
        $b = hexdec(substr($hex,4,2));
    }
    $rgb   = array($r, $g, $b);

    return $rgb;

}


function ColorToHex($pColor){

    $vColor    = strtoupper($pColor);
   
    if($vColor =='ALICEBLUE'){return 'F0F8FF';}
    if($vColor == 'ANTIQUEWHITE'){return 'FAEBD7';}
    if($vColor == 'AQUA'){return '00FFFF';}
    if($vColor == 'AQUAMARINE'){return '7FFFD4';}
    if($vColor == 'AZURE'){return 'F0FFFF';}
    if($vColor == 'BEIGE'){return 'F5F5DC';}
    if($vColor == 'BISQUE'){return 'FFE4C4';}
    if($vColor == 'BLACK'){return '000000';}
    if($vColor == 'BLANCHEDALMOND'){return 'FFEBCD';}
    if($vColor == 'BLUE'){return '0000FF';}
    if($vColor == 'BLUEVIOLET'){return '8A2BE2';}
    if($vColor == 'BROWN'){return 'A52A2A';}
    if($vColor == 'BURLYWOOD'){return 'DEB887';}
    if($vColor == 'CADETBLUE'){return '5F9EA0';}
    if($vColor == 'CHARTREUSE'){return '7FFF00';}
    if($vColor == 'CHOCOLATE'){return 'D2691E';}
    if($vColor == 'CORAL'){return 'FF7F50';}
    if($vColor == 'CORNFLOWERBLUE'){return '6495ED';}
    if($vColor == 'CORNSILK'){return 'FFF8DC';}
    if($vColor == 'CRIMSON'){return 'DC143C';}
    if($vColor == 'CYAN'){return '00FFFF';}
    if($vColor == 'DARKBLUE'){return '00008B';}
    if($vColor == 'DARKCYAN'){return '008B8B';}
    if($vColor == 'DARKGOLDENROD'){return 'B8860B';}
    if($vColor == 'DARKGRAY'){return 'A9A9A9';}
    if($vColor == 'DARKGREY'){return 'A9A9A9';}
    if($vColor == 'DARKGREEN'){return '006400';}
    if($vColor == 'DARKKHAKI'){return 'BDB76B';}
    if($vColor == 'DARKMAGENTA'){return '8B008B';}
    if($vColor == 'DARKOLIVEGREEN'){return '556B2F';}
    if($vColor == 'DARKORANGE'){return 'FF8C00';}
    if($vColor == 'DARKORCHID'){return '9932CC';}
    if($vColor == 'DARKRED'){return '8B0000';}
    if($vColor == 'DARKSALMON'){return 'E9967A';}
    if($vColor == 'DARKSEAGREEN'){return '8FBC8F';}
    if($vColor == 'DARKSLATEBLUE'){return '483D8B';}
    if($vColor == 'DARKSLATEGRAY'){return '2F4F4F';}
    if($vColor == 'DARKSLATEGREY'){return '2F4F4F';}
    if($vColor == 'DARKTURQUOISE'){return '00CED1';}
    if($vColor == 'DARKVIOLET'){return '9400D3';}
    if($vColor == 'DEEPPINK'){return 'FF1493';}
    if($vColor == 'DEEPSKYBLUE'){return '00BFFF';}
    if($vColor == 'DIMGRAY'){return '696969';}
    if($vColor == 'DIMGREY'){return '696969';}
    if($vColor == 'DODGERBLUE'){return '1E90FF';}
    if($vColor == 'FIREBRICK'){return 'B22222';}
    if($vColor == 'FLORALWHITE'){return 'FFFAF0';}
    if($vColor == 'FORESTGREEN'){return '228B22';}
    if($vColor == 'FUCHSIA'){return 'FF00FF';}
    if($vColor == 'GAINSBORO'){return 'DCDCDC';}
    if($vColor == 'GHOSTWHITE'){return 'F8F8FF';}
    if($vColor == 'GOLD'){return 'FFD700';}
    if($vColor == 'GOLDENROD'){return 'DAA520';}
    if($vColor == 'GRAY'){return '808080';}
    if($vColor == 'GREY'){return '808080';}
    if($vColor == 'GREEN'){return '008000';}
    if($vColor == 'GREENYELLOW'){return 'ADFF2F';}
    if($vColor == 'HONEYDEW'){return 'F0FFF0';}
    if($vColor == 'HOTPINK'){return 'FF69B4';}
    if($vColor == 'INDIANRED'){return 'CD5C5C';}
    if($vColor == 'INDIGO'){return '4B0082';}
    if($vColor == 'IVORY'){return 'FFFFF0';}
    if($vColor == 'KHAKI'){return 'F0E68C';}
    if($vColor == 'LAVENDER'){return 'E6E6FA';}
    if($vColor == 'LAVENDERBLUSH'){return 'FFF0F5';}
    if($vColor == 'LAWNGREEN'){return '7CFC00';}
    if($vColor == 'LEMONCHIFFON'){return 'FFFACD';}
    if($vColor == 'LIGHTBLUE'){return 'ADD8E6';}
    if($vColor == 'LIGHTCORAL'){return 'F08080';}
    if($vColor == 'LIGHTCYAN'){return 'E0FFFF';}
    if($vColor == 'LIGHTGOLDENRODYELLOW'){return 'FAFAD2';}
    if($vColor == 'LIGHTGRAY'){return 'D3D3D3';}
    if($vColor == 'LIGHTGREY'){return 'D3D3D3';}
    if($vColor == 'LIGHTGREEN'){return '90EE90';}
    if($vColor == 'LIGHTPINK'){return 'FFB6C1';}
    if($vColor == 'LIGHTSALMON'){return 'FFA07A';}
    if($vColor == 'LIGHTSEAGREEN'){return '20B2AA';}
    if($vColor == 'LIGHTSKYBLUE'){return '87CEFA';}
    if($vColor == 'LIGHTSLATEGRAY'){return '778899';}
    if($vColor == 'LIGHTSLATEGREY'){return '778899';}
    if($vColor == 'LIGHTSTEELBLUE'){return 'B0C4DE';}
    if($vColor == 'LIGHTYELLOW'){return 'FFFFE0';}
    if($vColor == 'LIME'){return '00FF00';}
    if($vColor == 'LIMEGREEN'){return '32CD32';}
    if($vColor == 'LINEN'){return 'FAF0E6';}
    if($vColor == 'MAGENTA'){return 'FF00FF';}
    if($vColor == 'MAROON'){return '800000';}
    if($vColor == 'MEDIUMAQUAMARINE'){return '66CDAA';}
    if($vColor == 'MEDIUMBLUE'){return '0000CD';}
    if($vColor == 'MEDIUMORCHID'){return 'BA55D3';}
    if($vColor == 'MEDIUMPURPLE'){return '9370D8';}
    if($vColor == 'MEDIUMSEAGREEN'){return '3CB371';}
    if($vColor == 'MEDIUMSLATEBLUE'){return '7B68EE';}
    if($vColor == 'MEDIUMSPRINGGREEN'){return '00FA9A';}
    if($vColor == 'MEDIUMTURQUOISE'){return '48D1CC';}
    if($vColor == 'MEDIUMVIOLETRED'){return 'C71585';}
    if($vColor == 'MIDNIGHTBLUE'){return '191970';}
    if($vColor == 'MINTCREAM'){return 'F5FFFA';}
    if($vColor == 'MISTYROSE'){return 'FFE4E1';}
    if($vColor == 'MOCCASIN'){return 'FFE4B5';}
    if($vColor == 'NAVAJOWHITE'){return 'FFDEAD';}
    if($vColor == 'NAVY'){return '000080';}
    if($vColor == 'OLDLACE'){return 'FDF5E6';}
    if($vColor == 'OLIVE'){return '808000';}
    if($vColor == 'OLIVEDRAB'){return '6B8E23';}
    if($vColor == 'ORANGE'){return 'FFA500';}
    if($vColor == 'ORANGERED'){return 'FF4500';}
    if($vColor == 'ORCHID'){return 'DA70D6';}
    if($vColor == 'PALEGOLDENROD'){return 'EEE8AA';}
    if($vColor == 'PALEGREEN'){return '98FB98';}
    if($vColor == 'PALETURQUOISE'){return 'AFEEEE';}
    if($vColor == 'PALEVIOLETRED'){return 'D87093';}
    if($vColor == 'PAPAYAWHIP'){return 'FFEFD5';}
    if($vColor == 'PEACHPUFF'){return 'FFDAB9';}
    if($vColor == 'PERU'){return 'CD853F';}
    if($vColor == 'PINK'){return 'FFC0CB';}
    if($vColor == 'PLUM'){return 'DDA0DD';}
    if($vColor == 'POWDERBLUE'){return 'B0E0E6';}
    if($vColor == 'PURPLE'){return '800080';}
    if($vColor == 'RED'){return 'FF0000';}
    if($vColor == 'ROSYBROWN'){return 'BC8F8F';}
    if($vColor == 'ROYALBLUE'){return '4169E1';}
    if($vColor == 'SADDLEBROWN'){return '8B4513';}
    if($vColor == 'SALMON'){return 'FA8072';}
    if($vColor == 'SANDYBROWN'){return 'F4A460';}
    if($vColor == 'SEAGREEN'){return '2E8B57';}
    if($vColor == 'SEASHELL'){return 'FFF5EE';}
    if($vColor == 'SIENNA'){return 'A0522D';}
    if($vColor == 'SILVER'){return 'C0C0C0';}
    if($vColor == 'SKYBLUE'){return '87CEEB';}
    if($vColor == 'SLATEBLUE'){return '6A5ACD';}
    if($vColor == 'SLATEGRAY'){return '708090';}
    if($vColor == 'SLATEGREY'){return '708090';}
    if($vColor == 'SNOW'){return 'FFFAFA';}
    if($vColor == 'SPRINGGREEN'){return '00FF7F';}
    if($vColor == 'STEELBLUE'){return '4682B4';}
    if($vColor == 'TAN'){return 'D2B48C';}
    if($vColor == 'TEAL'){return '008080';}
    if($vColor == 'THISTLE'){return 'D8BFD8';}
    if($vColor == 'TOMATO'){return 'FF6347';}
    if($vColor == 'TURQUOISE'){return '40E0D0';}
    if($vColor == 'VIOLET'){return 'EE82EE';}
    if($vColor == 'WHEAT'){return 'F5DEB3';}
    if($vColor == 'WHITE'){return 'FFFFFF';}
    if($vColor == 'WHITESMOKE'){return 'F5F5F5';}
    if($vColor == 'YELLOW'){return 'FFFF00';}
    if($vColor == 'YELLOWGREEN'){return '9ACD32';}
    return $vColor;
}

function nuID(){

	$i   = uniqid();
	$s   = md5($i);
    while($i == uniqid()){}
    return uniqid().$s[0].$s[1];

}

function nuGetFormProperties($f){

    global $nuDB;
	$t                      = nuRunQuery("SELECT * FROM zzzsys_form WHERE zzzsys_form_id = ?",array($f));
    if(nuErrorFound()){return;}
	$r                      = db_fetch_array($t);
	$r 			= nuCheckSafePHPMode($f, $r);	
	$GLOBALS['currentForm'] = $r;

}

function nuF($field){    //-- fields from current Form

	return $GLOBALS['currentForm'][$field];

}

function nuSessionArray($i){
	
	$A                        = array();
	$s                        = "
		SELECT 
		zzzsys_user_id         AS user_id, 
		sug_code               AS user_group, 
		sal_code               AS access_level, 
		zzzsys_access_level_id AS access_level_id, 
		sus_name               AS user_name,
		sus_email              AS email,
		sal_zzzsys_form_id     AS form_id
		FROM zzzsys_session 
		INNER JOIN zzzsys_user         ON sss_zzzsys_user_id         = zzzsys_user_id
		INNER JOIN zzzsys_user_group   ON sus_zzzsys_user_group_id   = zzzsys_user_group_id
		INNER JOIN zzzsys_access_level ON sug_zzzsys_access_level_id = zzzsys_access_level_id
		WHERE zzzsys_session_id = ?

	";
	
	$t                        = nuRunQuery($s, array($i));

	if($t->rowCount() == 0){
		$A['nu_user_id']          = 'globeadmin';
		$A['nu_user_group']       = 'globeadmin';
		$A['nu_access_level']     = 'globeadmin';
		$A['nu_user_name']        = 'globeadmin';
		$A['nu_index_id']         = 'nuindex';
		$A['nu_smtp_to_address']  = 'noreply@nubuilder.com';
		$A['nu_smtp_to_name']     = 'Globeadmin';
        nuV('nu_access_level_id', 'globeadmin');
	}else{
		$r                        = db_fetch_object($t);
		$A['nu_user_id']          = $r->user_id;
		$A['nu_user_group']       = $r->user_group;
		$A['nu_access_level']     = $r->access_level;
		$A['nu_user_name']        = $r->user_name;
		$A['nu_index_id']         = $r->form_id;
		$A['nu_smtp_to_address']  = $r->email;
		$A['nu_smtp_to_name']     = $r->user_name;
        nuV('nu_access_level_id', $r->access_level_id);
	}
        
        nuV('nu_user_id',      $A['nu_user_id']);
        nuV('nu_user_group',   $A['nu_user_group']);
        nuV('nu_user_name',    $A['nu_user_name']);
        nuV('nu_index_id',     $A['nu_index_id']);
	
	return $A;
	
}
function buildMenu($access_level_id,$hashData){
	$items = array();
	$sql="	SELECT 
				zzzsys_menu_item_id,
				ifNULL(sme_parent_menu_item_id,''),
				ifNULL(sme_image_name,''),
				ifNULL(sme_label,''),
				ifNULL(sme_title,''),
				ifNULL(sme_onclick_code,''),
				ifNULL(sme_onmouseover_code,''),
				ifNULL(sme_onmouseout_code,''),
				ifNULL(sme_display_condition,''),
				sme_order_number
			FROM zzzsys_menu_item
			INNER JOIN zzzsys_access_level_menu_item ON zzzsys_access_level_menu_item.slm_zzzsys_menu_item_id = zzzsys_menu_item_id
			WHERE zzzsys_access_level_menu_item.slm_zzzsys_access_level_id = '$access_level_id'
			ORDER BY sme_order_number";
	
	$t = nuRunQuery($sql);
    if(nuErrorFound()){return "";}
	while($r= db_fetch_row($t)){
		$id 				= $r[0];
		$parent_id			= $r[1];
		$image				= $r[2];
		$label				= $r[3];	
		$title				= $r[4];
		$onclick_code		= nuReplaceHashes($r[5],$hashData);
		$onmouseover_code	= nuReplaceHashes($r[6],$hashData);
		$onmouseout_code	= nuReplaceHashes($r[7],$hashData);
										// Έλεγχος του display_condition
		$display = true;
		if($r[8]!=''){
			$sql=nuReplaceHashes($r[8],$hashData);
			$retval = nuRunQuery($sql);
    		if(nuErrorFound()){$display=false;}
			if(!db_num_rows($retval)>0){$display=false;}
			$row= db_fetch_row($retval);
			if($row[0]!='1'){
				$display=false;	
			}
		}
		
		if($display){
			$items[] = array(
					'id'=> $id,
					'parent_id'=> $parent_id,
					'image'=> $image,
					'label'=> $label,
					'title'=> $title,
					'onclick_code'=> $onclick_code,
					'onmouseover_code'=> $onmouseover_code,
					'onmouseout_code'=> $onmouseout_code);
		}
	}
	if(count($items)>0){
		return '{"menu_items":'.json_encode($items).'}';
	} else {
		return "";
	}
}

function nuHashData(){

    $form_data                 = nuV('form_data');
    $h['TABLE_ID']             = nuTT();
    $h['RECORD_ID']            = nuV('record_id');
    $h['FORM_ID']              = nuV('form_id');
    $h['nu_browse_filter']     = nuV('filter');
    $h['nu_edited_record']     = nuV('edited');
    $h['nu_cloned_record']     = nuV('cloned');
    $h['nu_new_record']        = nuV('record_id') == '-1' ? '1' : '0';
	
	
	for($f = 0 ; $f < myCount($form_data['data']) ; $f++){	// PHP7 is_array added
	
        if(array_key_exists('records',$form_data['data'][$f])) {
            for($r = 0 ; $r < myCount($form_data['data'][$f]['records']) ; $r++){
			
				if(isset($form_data['data'][$f]['records'][$r]['fields'])){
            
					for($i = 0 ; $i < myCount($form_data['data'][$f]['records'][$r]['fields']) ; $i++){
					
						$fd                                     = $form_data['data'][$f]['records'][$r]['fields'][$i];
						
						if($form_data['data'][$f]['subform'] == ''){
							$prefix                             = '';
						}else{
							$prefix                             = $form_data['data'][$f]['subform'] . substr('000'.$r, -4);
						}
						
						$h[$prefix.$fd['field']]                = $fd['value'];
						
					}

					if($form_data['data'][$f]['subform'] != ''){
						$h[$form_data['data'][$f]['subform']][] = $prefix;

						if($form_data['data'][$f]['records'][$r]['delete_record'] == 'no'){
							$h[$form_data['data'][$f]['subform'].'_save'][] = $prefix;
						}
					}
				}
        
            }
		}
        
	}

	$v                             = nuV();

    foreach($v as $key => $value){                        //-- add nuV() to form_data
    
		$used                      = false;

		if(isset($_POST['nuWindow']['form_data'])){
			for($i = 0 ; $i < myCount($_POST['nuWindow']['form_data']['data'][0]['records'][0]['fields']) ; $i++){               //-- reapply hash variables from calling edit page (incase over written by $_POST['nuWindow'])

				if($_POST['nuWindow']['form_data']['data'][0]['records'][0]['fields'][$i]['field'] == $key){
				
					$used              = true;
					break;
					
				}
				
			}
		}

		if(!$used){

			$add['field']      = $key;
			$add['value']      = $value;
			$add['save']       = '0';
			
			$_POST['nuWindow']['form_data']['data'][0]['records'][0]['fields'][] = $add;
			
		}
		
    }
	
	$setup                         = $GLOBALS['nuSetup'];                                              //-- Read SMTP AUTH Settings from zzsys_setup table
	
	$h['nu_denied']                = $setup->set_denied;                                               //-- hide ids like .. eg. nu%

	$h['nu_smtp_username']         = $setup->set_smtp_username;
	$h['nu_smtp_password']         = $setup->set_smtp_password;
	$h['nu_smtp_host']             = $setup->set_smtp_host;
	$h['nu_smtp_from_address']     = $setup->set_smtp_from_address;
	$h['nu_smtp_port']             = $setup->set_smtp_port;
	$h['nu_smtp_use_ssl']          = $setup->set_smtp_use_ssl;
	$h['nu_smtp_from_name']        = $setup->set_smtp_from_name;

    $sessionData                   = nuSessionArray(nuV('session_id'));                               //-- user and access info
	
    $recordData                    = nuRecordArray(array_merge($sessionData, $h));                    //-- record data

    foreach($_POST['nuWindow'] as $key => $value){                                                    //-- add current hash variables
    
        $h[$key]                   = $value;
        
    }
    

	if(isset($form_data['data'][0]['records'][0]['fields'])){
		for($i = 0 ; $i < myCount($form_data['data'][0]['records'][0]['fields']) ; $i++){               //-- reapply hash variables from calling edit page (incase over written by $_POST['nuWindow'])
		
			$fd                       = $form_data['data'][0]['records'][0]['fields'][$i];
			$h[$fd['field']]           = $fd['value'];
			
		}
	}
    return array_merge($recordData,$sessionData, $h);

}

function nuRecordArray($hashData){

    $ignore                = array();
    $ignore[]              = 'sre_layout';
    $ignore[]              = 'form_data';
    $ignore[]              = 'slp_php';
    $noResult              = true;
    $A                     = array();
	
	$t                     = nuRunQuery("SELECT * FROM zzzsys_form WHERE zzzsys_form_id = ? ", array(nuV('form_id')));
	$r                     = db_fetch_object($t);

	if(nuV('call_type') != 'geteditform'){

		$r->sfo_custom_code_run_before_browse 	= nuGetSafePHP('sfo_custom_code_run_before_browse', $r->zzzsys_form_id, $r->sfo_custom_code_run_before_browse);
		$bb                			= nuReplaceHashes($r->sfo_custom_code_run_before_browse, $hashData);     //-- this is run to place any javascript into a Browse Form
		eval($bb);
	
	}
	
	$table                 = nuReplaceHashes($r->sfo_table, $hashData);
    if($table != ''){
    	$T                     = nuRunQuery("SELECT * FROM $table WHERE $r->sfo_primary_key = ? ", array(nuV('record_id')));
    	$R                     = db_fetch_array($T);
       
    	if (is_array($R)) {
    	foreach($R as $key => $value){                                           //-- add current hash variables

    			$noResult          = false;
    		
    			if(!is_numeric($key)){
    			
    				if(!in_array($key, $ignore)){
    					$A[$key]   = $value;
    					}
    			}
    	  }
    	}
    }
	
	if($noResult){                                                           //-- set fields to blank values
		
		$flds              = db_columns($table);
		
		for($i = 0 ; $i < myCount($flds) ; $i ++){
			
			$A[$flds[$i]]  = '';
			
		}
		
	}
        
	nuRunQuery("DROP TABLE IF EXISTS ".$hashData['TABLE_ID']);
		
	return $A;

}


function nuReplaceHashes($str, $arr){

	while(list($key, $value) = each($arr)){


		//Changed by SG on 20 April 2015
		/*	
		if(!is_array($value) and $str != '' and $key != ''){
			$newValue = addslashes($value);
			$str = str_replace('#'.$key.'#', $newValue, $str);
		}
		*/
		 if( !is_object($value) and !is_array($value) and $str != '' and $key != ''){
                        $newValue = addslashes($value);
                        $str = str_replace('#'.$key.'#', $newValue, $str);
                }
		
	}

    $GLOBALS['latest_hashData'] = $arr;
    
	return $str;
}



function nuTranslate($phrase){

	$setup    = $GLOBALS['nuSetup'];
	$t        = nuRunQuery("SELECT trl_translation FROM zzzsys_translate WHERE trl_language = '$setup->set_language' AND trl_english = ?", array($phrase));
    if(nuErrorFound()){return;}
	$r        = db_fetch_row($t);
	if($r[0] == ''){
		return str_replace( "'", "&#146",$phrase);
	}else{
		return str_replace( "'", "&#146",$r[0]);
	}

}
			

function nuTextFormats(){

//-----number formats
	$format = array();
 	for ($i=0; $i < 34; $i++) {
		$format[$i] = new stdClass;
	}
	
	$format[0]->type         = 'number';
	$format[0]->format       = '0';
	$format[0]->decimal      = '.';
	$format[0]->separator    = '';
	$format[0]->sample       = '10000';
	$format[0]->phpdate      = '';
	$format[0]->sql          = 'REPLACE(FORMAT(??,0), ",", "")';

	$format[1]->type         = 'number';
	$format[1]->format       = '1';
	$format[1]->decimal      = '.';
	$format[1]->separator    = '';
	$format[1]->sample       = '10000.0';
	$format[1]->phpdate      = '';
	$format[1]->sql          = 'REPLACE(FORMAT(??,1), ",", "")';

	$format[2]->type         = 'number';
	$format[2]->format       = '2';
	$format[2]->decimal      = '.';
	$format[2]->separator    = '';
	$format[2]->sample       = '10000.00';
	$format[2]->phpdate      = '';
	$format[2]->sql          = 'REPLACE(FORMAT(??,2), ",", "")';

	$format[3]->type         = 'number';
	$format[3]->format       = '3';
	$format[3]->decimal      = '.';
	$format[3]->separator    = '';
	$format[3]->sample       = '10000.000';
	$format[3]->phpdate      = '';
	$format[3]->sql          = 'REPLACE(FORMAT(??,3), ",", "")';

	$format[4]->type         = 'number';
	$format[4]->format       = '4';
	$format[4]->decimal      = '.';
	$format[4]->separator    = '';
	$format[4]->sample       = '10000.0000';
	$format[4]->phpdate      = '';
	$format[4]->sql          = 'REPLACE(FORMAT(??,4), ",", "")';

	$format[5]->type         = 'number';
	$format[5]->format       = '5';
	$format[5]->decimal      = '.';
	$format[5]->separator    = '';
	$format[5]->sample       = '10000.00000';
	$format[5]->phpdate      = '';
	$format[5]->sql          = 'REPLACE(FORMAT(??,5), ",", "")';

//-----date formats

	$format[6]->type         = 'date';
	$format[6]->format       = 'dd-mmm-yyyy';
	$format[6]->decimal      = '.';
	$format[6]->separator    = '';
	$format[6]->sample       = '13-Jan-2007';
	$format[6]->phpdate      = 'd-M-Y';
	$format[6]->sql          = 'DATE_FORMAT(??,"%d-%b-%Y")';
	$format[6]->jquery       = 'dd-M-yy';

	$format[7]->type         = 'date';
	$format[7]->format       = 'dd-mm-yyyy';
	$format[7]->decimal      = '.';
	$format[7]->separator    = '';
	$format[7]->sample       = '13-01-2007';
	$format[7]->phpdate      = 'd-m-Y';
	$format[7]->sql          = 'DATE_FORMAT(??,"%d-%m-%Y")';
	$format[7]->jquery       = 'dd-mm-yy';

	$format[8]->type         = 'date';
	$format[8]->format       = 'mmm-dd-yyyy';
	$format[8]->decimal      = '.';
	$format[8]->separator    = '';
	$format[8]->sample       = 'Jan-13-2007';
	$format[8]->phpdate      = 'M-d-Y';
	$format[8]->sql          = 'DATE_FORMAT(??,"%b-%d-%Y")';
	$format[8]->jquery       = 'M-dd-yy';

	$format[9]->type         = 'date';
	$format[9]->format       = 'mm-dd-yyyy';
	$format[9]->decimal      = '.';
	$format[9]->separator    = '';
	$format[9]->sample       = '01-13-2007';
	$format[9]->phpdate      = 'm-d-Y';
	$format[9]->sql          = 'DATE_FORMAT(??,"%m-%d-%Y")';
	$format[9]->jquery       = 'mm-dd-yy';

	$format[10]->type        = 'date';
	$format[10]->format      = 'dd-mmm-yy';
	$format[10]->decimal     = '.';
	$format[10]->separator   = '';
	$format[10]->sample      = '13-Jan-07';
	$format[10]->phpdate     = 'd-M-y';
	$format[10]->sql         = 'DATE_FORMAT(??,"%d-%b-%y")';
	$format[10]->jquery       = 'dd-M-y';

	$format[11]->type        = 'date';
	$format[11]->format      = 'dd-mm-yy';
	$format[11]->decimal     = '.';
	$format[11]->separator   = '';
	$format[11]->sample      = '13-01-07';
	$format[11]->phpdate     = 'd-m-y';
	$format[11]->sql         = 'DATE_FORMAT(??,"%d-%m-%y")';
	$format[11]->jquery       = 'dd-mm-y';

	$format[12]->type        = 'date';
	$format[12]->format      = 'mmm-dd-yy';
	$format[12]->decimal     = '.';
	$format[12]->separator   = '';
	$format[12]->sample      = 'Jan-13-07';
	$format[12]->phpdate     = 'M-d-y';
	$format[12]->sql         = 'DATE_FORMAT(??,"%b-%d-%y")';
	$format[12]->jquery       = 'M-dd-y';

	$format[13]->type        = 'date';
	$format[13]->format      = 'mm-dd-yy';
	$format[13]->decimal     = '.';
	$format[13]->separator   = '';
	$format[13]->sample      = '01-13-07';
	$format[13]->phpdate     = 'm-d-y';
	$format[13]->sql         = 'DATE_FORMAT(??,"%m-%d-%y")';
	$format[13]->jquery       = 'mm-dd-y';

//-----number formats

	$format[14]->type        = 'number';
	$format[14]->format      = '0';
	$format[14]->decimal     = '.';
	$format[14]->separator   = ',';
	$format[14]->sample      = '10,000';
	$format[14]->phpdate     = '';
	$format[14]->sql         = 'FORMAT(??,0)';

	$format[15]->type        = 'number';
	$format[15]->format      = '1';
	$format[15]->decimal     = '.';
	$format[15]->separator   = ',';
	$format[15]->sample      = '10,000.0';
	$format[15]->phpdate     = '';
	$format[15]->sql         = 'FORMAT(??,1)';

	$format[16]->type        = 'number';
	$format[16]->format      = '2';
	$format[16]->decimal     = '.';
	$format[16]->separator   = ',';
	$format[16]->sample      = '10,000.00';
	$format[16]->phpdate     = '';
	$format[16]->sql         = 'FORMAT(??,2)';

	$format[17]->type        = 'number';
	$format[17]->format      = '3';
	$format[17]->decimal     = '.';
	$format[17]->separator   = ',';
	$format[17]->sample      = '10,000.000';
	$format[17]->phpdate     = '';
	$format[17]->sql         = 'FORMAT(??,3)';

	$format[18]->type        = 'number';
	$format[18]->format      = '4';
	$format[18]->decimal     = '.';
	$format[18]->separator   = ',';
	$format[18]->sample      = '10,000.0000';
	$format[18]->phpdate     = '';
	$format[18]->sql         = 'FORMAT(??,4)';

	$format[19]->type        = 'number';
	$format[19]->format      = '5';
	$format[19]->decimal     = '.';
	$format[19]->separator   = ',';
	$format[19]->sample      = '10,000.00000';
	$format[19]->phpdate     = '';
	$format[19]->sql         = 'FORMAT(??,5)';

//-----euro number formats

	$format[20]->type        = 'number';
	$format[20]->format      = '0';
	$format[20]->decimal     = ',';
	$format[20]->separator   = '';
	$format[20]->sample      = '10000';
	$format[20]->phpdate     = '';
	$format[20]->sql         = 'FORMAT(??,0)';

	$format[21]->type        = 'number';
	$format[21]->format      = '1';
	$format[21]->decimal     = ',';
	$format[21]->separator   = '';
	$format[21]->sample      = '10000,0';
	$format[21]->phpdate     = '';
	$format[21]->sql         = 'FORMAT(??,1)';

	$format[22]->type        = 'number';
	$format[22]->format      = '2';
	$format[22]->decimal     = ',';
	$format[22]->separator   = '';
	$format[22]->sample      = '10000,00';
	$format[22]->phpdate     = '';
	$format[22]->sql         = 'FORMAT(??,2)';

	$format[23]->type        = 'number';
	$format[23]->format      = '3';
	$format[23]->decimal     = ',';
	$format[23]->separator   = '';
	$format[23]->sample      = '10000,000';
	$format[23]->phpdate     = '';
	$format[23]->sql         = 'FORMAT(??,3)';

	$format[24]->type        = 'number';
	$format[24]->format      = '4';
	$format[24]->decimal     = ',';
	$format[24]->separator   = '';
	$format[24]->sample      = '10000,0000';
	$format[24]->phpdate     = '';
	$format[24]->sql         = 'FORMAT(??,4)';

	$format[25]->type        = 'number';
	$format[25]->format      = '5';
	$format[25]->decimal     = ',';
	$format[25]->separator   = '';
	$format[25]->sample      = '10000,00000';
	$format[25]->phpdate     = '';
	$format[25]->sql         = 'FORMAT(??,5)';

	$format[26]->type        = 'number';
	$format[26]->format      = '0';
	$format[26]->decimal     = ',';
	$format[26]->separator   = '.';
	$format[26]->sample      = '10.000';
	$format[26]->phpdate     = '';
	$format[26]->sql         = 'FORMAT(??,0)';

	$format[27]->type        = 'number';
	$format[27]->format      = '1';
	$format[27]->decimal     = ',';
	$format[27]->separator   = '.';
	$format[27]->sample      = '10.000,0';
	$format[27]->phpdate     = '';
	$format[27]->sql         = 'FORMAT(??,1)';

	$format[28]->type        = 'number';
	$format[28]->format      = '2';
	$format[28]->decimal     = ',';
	$format[28]->separator   = '.';
	$format[28]->sample      = '10.000,00';
	$format[28]->phpdate     = '';
	$format[28]->sql         = 'FORMAT(??,2)';

	$format[29]->type        = 'number';
	$format[29]->format      = '3';
	$format[29]->decimal     = ',';
	$format[29]->separator   = '.';
	$format[29]->sample      = '10.000,000';
	$format[29]->phpdate     = '';
	$format[29]->sql         = 'FORMAT(??,3)';

	$format[30]->type        = 'number';
	$format[30]->format      = '4';
	$format[30]->decimal     = ',';
	$format[30]->separator   = '.';
	$format[30]->sample      = '10.000,0000';
	$format[30]->phpdate     = '';
	$format[30]->sql         = 'FORMAT(??,4)';

	$format[31]->type        = 'number';
	$format[31]->format      = '5';
	$format[31]->decimal     = ',';
	$format[31]->separator   = '.';
	$format[31]->sample      = '10.000,00000';
	$format[31]->phpdate     = '';
	$format[31]->sql         = 'FORMAT(??,5)';

//-----checkbox format

	$format[32]->type        = 'checkbox';
	$format[32]->format      = 'cb';
	$format[32]->decimal     = '';
	$format[32]->separator   = '';
	$format[32]->sample      = 'checkbox';
	$format[32]->phpdate     = '';
	$format[32]->sql         = 'CONCAT(\'<input type="checkbox" \',IF(??,\'checked \',\'\'),\'disabled>\')';

	$format[33]->type         = 'date';
	$format[33]->format       = 'yyyy-mm-dd';
	$format[33]->decimal      = '.';
	$format[33]->separator    = '';
	$format[33]->sample       = '2007-01-13';
	$format[33]->phpdate      = 'Y-m-d';
	$format[33]->sql          = 'DATE_FORMAT(??,"%Y-%m-%d")';
	$format[33]->jquery       = 'yy-mm-dd';

	return $format;

}


class nuSqlString{

    public  $from         = '';
    public  $where        = '';
    public  $groupBy      = '';
    public  $having       = '';
    public  $orderBy      = '';
    public  $fields       = array();
    public  $Dselect      = '';
    public  $Dfrom        = '';
    public  $Dwhere       = '';
    public  $DgroupBy     = '';
    public  $Dhaving      = '';
    public  $DorderBy     = '';
    public  $Dfields      = array();
    public  $SQL          = '';

    function __construct($sql){

        $sql              = str_replace(chr(13), ' ', $sql);//----remove carrige returns
        $sql              = str_replace(chr(10), ' ', $sql);//----remove line feeds

        $select_string    = $sql;
        $from_string      = stristr($sql, ' from ');
        $where_string     = $this->getOuter($sql, ' where ');
        $groupBy_string   = $this->getOuter($sql, ' group by ');
        $having_string    = $this->getOuter($sql, ' having ');
        $orderBy_string   = $this->getOuter($sql, ' order by ');
        
        $from             = str_replace($where_string,   '', $from_string);
        $from             = str_replace($groupBy_string, '', $from);
        $from             = str_replace($having_string,  '', $from);
        $from             = str_replace($orderBy_string, '', $from);
        
        $where            = str_replace($groupBy_string, '', $where_string);
        $where            = str_replace($having_string,  '', $where);
        $where            = str_replace($orderBy_string, '', $where);
        
        $groupBy          = str_replace($having_string,  '', $groupBy_string);
        $groupBy          = str_replace($orderBy_string, '', $groupBy);
        
        $having           = str_replace($orderBy_string, '', $having_string);
        
        $orderBy          = $orderBy_string;
        $this->from       = $from;
        $this->where      = $where;
        $this->groupBy    = $groupBy;
        $this->having     = $having;
        $this->orderBy    = $orderBy;

        $this->Dfrom      = $this->from;
        $this->Dwhere     = $this->where;
        $this->DgroupBy   = $this->groupBy;
        $this->Dhaving    = $this->having;
        $this->DorderBy   = $this->orderBy;

    	$this->buildSQL();
    }

	private function strristr($h, $n, $before = false) {
		$rpos = strripos($h, $n);
		if($rpos === false) return false;
		return ($before == false) ? substr($h, $rpos) : substr($h, 0, $rpos);
	}

	private function getOuter($h, $n) {
		$return = $this->strristr($h, $n);
		while($return !== false && substr_count($return, '(') != substr_count($return, ')') ){
			$return2 = $this->strristr(str_replace($return, '', $h), $n);
			$return = ($return2 !== FALSE)? $return2.$return : $return2;
		}

		return $return;
	}

    public function restoreDefault($pString){

    	if($pString == 'f'){$this->from      = $this->Dfrom;}
    	if($pString == 'w'){$this->where     = $this->Dwhere;}
    	if($pString == 'g'){$this->groupBy   = $this->DgroupBy;}
    	if($pString == 'h'){$this->having    = $this->Dhaving;}
    	if($pString == 'o'){$this->orderBy   = $this->DorderBy;}
    	$this->buildSQL();

    }



    public function addWhereClause($pClause){

        if(trim($this->where) == ''){
            $this->where = "WHERE $pClause";
        }else{
            $clause      = substr($this->where, 6);
            $this->where = "WHERE ($clause) AND ($pClause)";
        }

    }

    
    public function getTableName(){

    	return trim(substr($this->from, 5));

    }

    public function setFrom($pString){

    	$this->from          = $pString; 
    	$this->buildSQL();

    }

    public function setWhere($pString){

    	$this->where         = $pString; 
    	$this->buildSQL();

    }

    public function getWhere(){
    	return $this->where; 
    }

    public function setGroupBy($pString){

    	$this->groupBy       = $pString; 
    	$this->buildSQL();

    }

    public function setHaving($pString){

    	$this->having        = $pString; 
    	$this->buildSQL();

    }

    public function setOrderBy($pString){

    	$this->orderBy       = $pString; 
    	$this->buildSQL();

    }

    public function addField($pString){

		$this->fields[]      = $pString; 
    	$this->buildSQL();

    }

    public function removeAllFields(){

		$this->fields        = array();

    }

    private function buildSQL(){
    	$this->SQL           = 'SELECT '; 
    	$this->SQL           = $this->SQL . ' ' . implode(',', $this->fields);
    	$this->SQL           = $this->SQL . ' ' . $this->from;
    	$this->SQL           = $this->SQL . ' ' . $this->where;
    	$this->SQL           = $this->SQL . ' ' . $this->groupBy;
    	$this->SQL           = $this->SQL . ' ' . $this->having;
    	$this->SQL           = $this->SQL . ' ' . $this->orderBy;
    }

}


function nuV($pElement = NULL, $pValue = NULL){

        static $variables              = NULL;

        if ($variables === NULL && array_key_exists('nuWindow', $_POST)){
            
            $variables                 = $_POST['nuWindow'];
            $t                         = nuRunQuery("SELECT * FROM zzzsys_setup WHERE zzzsys_setup_id = 1");
            if(nuErrorFound()){return;}
            $r                         = db_fetch_object($t);
            $variables['set_language'] = $r->set_language;
        }
        
        if ($pValue    !== NULL){
            $variables[$pElement]      = $pValue;
        }

        if($pElement == NULL){
            return $variables;
        }else if(gettype($variables) == "array" && array_key_exists($pElement, $variables)){
		
            if($variables[$pElement] != 'null') {
                return $variables[$pElement];
            } else {
                return '';
            }
			
        }else{
            return NULL;
        }
}


function nuBuildHashData($J, $T){

    $hash               = array();
    $ignore             = array();
    $ignore[]           = 'sre_layout';
    $ignore[]           = 'form_data';
    $ignore[]           = 'slp_php';
    
    foreach($J as $key => $v){                                           //-- add current hash variables
        
        if(!in_array($key, $ignore)){
            $hash['' . $key . '']     = $v;
        }
        
    }

    $d                  = new DateTime();

    $hash['nu_date_time']     = $d->format('Y-m-d H:i:s');
    $hash['nu_date']          = $d->format('Y-m-d');
    $hash['nu_time']          = $d->format('H:i:s');
    $hash['nu_year']          = $d->format('Y');
    $hash['nu_month']         = $d->format('m');
    $hash['nu_day']           = $d->format('d');
    $hash['nu_hour']          = $d->format('H');
    $hash['nu_minute']        = $d->format('i');
    $hash['nu_second']        = $d->format('s');

    $hash['TABLE_ID']         = $T;

    
    return $hash;

}

function nuErrorFound(){
    
    if(isset($GLOBALS['ERRORS'])){
        return myCount($GLOBALS['ERRORS']) > 0;
    }else{
        return false;
    }
    
}

function nuUploadCodesToMessage($code) {
        switch ($code) { 
            case UPLOAD_ERR_INI_SIZE: 
                $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini"; 
                break; 
            case UPLOAD_ERR_FORM_SIZE: 
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form"; 
                break; 
            case UPLOAD_ERR_PARTIAL: 
                $message = "The uploaded file was only partially uploaded"; 
                break; 
            case UPLOAD_ERR_NO_FILE: 
                $message = "No file was uploaded"; 
                break; 
            case UPLOAD_ERR_NO_TMP_DIR: 
                $message = "Missing a temporary folder"; 
                break; 
            case UPLOAD_ERR_CANT_WRITE: 
                $message = "Failed to write file to disk"; 
                break; 
            case UPLOAD_ERR_EXTENSION: 
                $message = "File upload stopped by extension"; 
                break; 
            default: 
                $message = "Unknown upload error"; 
                break; 
        } 
        return $message; 
} 



function nuPDForPHPParameters($hashData, $validate = '', $saveToFile = false) {

    $hash               = array();
	
	if($validate == ''){                                                     //-- (runphp or printpdf)
		$theID          = $hashData['parent_record_id'];
	}else{                                                                   //-- just validate user access
		nuV('call_type', $validate);
		$theID          = nuV('code');
	}
    
    foreach ($hashData as $key => $val) {                                    //-- add current hash variables
        $hash[$key]     = $val;
    }

    if (nuV('call_type') == 'runphp') {                                      //-- add php record to hash variables
    
        $s              = "SELECT * FROM  zzzsys_php WHERE slp_code = '$theID'";
        $t              = nuRunQuery($s);
        if (nuErrorFound()) {
            return;
        }

        $r              = db_fetch_object($t);

	$r->slp_php = nuGetSafePHP('slp_php', $r->zzzsys_php_id, $r->slp_php);

        if(!nuPHPAccess($r->zzzsys_php_id)){
            nuDisplayError("Access denied to PHP - ($theID)");
            return;
        }

        foreach ($r as $key => $v) {                                         //-- add php hash variables
            $hash[$key] = $v;
        }
    }

    if (nuV('call_type') == 'printpdf') {                                    //-- add report record to hash variables
        
        $s              = "SELECT * FROM  zzzsys_report LEFT JOIN zzzsys_php ON sre_zzzsys_php_id = zzzsys_php_id WHERE sre_code = '$theID'";
        $t              = nuRunQuery($s);
        
        if (nuErrorFound()) {
            return;
        }
        
	$r              = db_fetch_object($t);

	$r->slp_php = nuGetSafePHP('slp_php', $r->zzzsys_php_id, $r->slp_php);	
        
	if(!nuReportAccess($r->zzzsys_report_id)){
		nuDisplayError("Access denied to Report - ($theID)");
            return;
        }

        foreach ($r as $key => $v) {                                          //-- add pdf hash variables
            $hash[$key] = $v;
        }
    }

	if($validate != '' and $saveToFile == false){return;}                                              //-- just check
	
    $i                  = nuID();
    $hash['sfi_blob']   = null;  
    $j                  = json_encode($hash);
    $d                  = date('Y-m-d h:i:s');

    nuRunQuery("INSERT INTO zzzsys_debug (zzzsys_debug_id, deb_message, deb_added) VALUES(?, ?, ?)", array($i, $j, $d));
    
    if (nuErrorFound()) {
        return;
    }
	
    if (nuV('call_type') == 'printpdf' and nuV('filename') != '' ) {                                    //-- save pdf to server

		return nuEmailGetReportFile($i);
		
	}


    return $i;
    
}


function nuGetLanguage(){


   	$setup     = $GLOBALS['nuSetup'];                                      //-- Get language from zzsys_setup table	
    $j         = "function nuTranslate(p){\n\n";
    $t         = nuRunQuery("SELECT * FROM zzzsys_translate WHERE trl_language = ? ", array($setup->set_language));
    
    while($r   = db_fetch_object($t)){
        $j    .= "   if(p == '" . str_replace("'", "\'", $r->trl_english) . "'){return '" . str_replace("'", "\'", $r->trl_translation) . "';}\n";
    }
    
    return $j .= "\n   return p;\n}\n\n";
    
}



function nuTime($message){
    
    if(!isset($GLOBALS['nu_time'])){
        $GLOBALS['nu_time'] = array();
    }
    
    $date                   = new DateTime();
    $GLOBALS['nu_time'][]   = $message . ' - ' . $date->getTimestamp();

}


function nuCreateFile($c){
    
    $t    = nuRunQuery("SELECT * FROM zzzsys_file WHERE sfi_code = ? ", array($c));
    $r    = db_fetch_object($t);
    $x    = explode('/',$r->sfi_type);
    $id   = nuID();
    $file = "tmp/$id." . $x[1];
    $h    = fopen($file , 'w');
    fwrite($h, $r->sfi_blob);
    fclose($h);
    
    return $file;
}


function nuEmailGetReportFile($request, $request_url = null) {

	if ($request_url == null) {
	
		if (!empty($_SERVER['HTTPS'])) {
			$request_url      = "https://";
		} else {
			$request_url      = "http://";	
		}
		$this_url             = $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		$this_url             = explode("/",$this_url);
		for ($x=0; $x<count($this_url)-1;$x++) {
			$request_url     .= $this_url[$x]."/";
		}
		
		$request_url         .= "nurunpdf.php?i=".$request;
		
	}

	$urlHandle                = fopen($request_url, "rb");
	$contents                 = stream_get_contents($urlHandle);
	fclose($urlHandle);

	$tmp_file                 = tempnam( sys_get_temp_dir(), "nu");
	$handle                   = fopen($tmp_file, "w");
	fwrite($handle, $contents);
	fclose($handle);
	return $tmp_file;
}

function nuNextNumberTables(){

//-- (test 1) table has only 2 fields
    $a = array();                                        //-- array of tables that meet the specs for generating an incremented file
    $d = $_SESSION['DBName']; 
    $s = "
        SELECT 
            table_name, 
            count(table_name) as CT, 
            column_name
        FROM information_schema.columns 
        WHERE table_schema = '$d'
        GROUP BY table_name
        HAVING CT = 2
        ";

    $t = nuRunQuery($s);

    while($r = db_fetch_row($t)){
        $s  = "
                SELECT COLUMN_KEY 
                FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_SCHEMA = '$d' 
                AND TABLE_NAME     = '$r[0]' 
                AND COLUMN_NAME    = '$r[2]' 
                AND EXTRA          LIKE '%auto_increment%'
            ";
        $ct = nuRunQuery($s);
        $cr = db_fetch_row($ct);

        if($cr[0] == 'PRI'){                                       //-- (test 2) first field is a auto increment Primary Key

            $s  = "
                SELECT CONCAT(DATA_TYPE, CHARACTER_MAXIMUM_LENGTH)
                FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_SCHEMA = '$d' 
                AND TABLE_NAME     = '$r[0]' 
                AND COLUMN_NAME    != '$r[2]' 
                AND DATA_TYPE      = 'varchar'
                ";
            $ct = nuRunQuery($s);
            $cr = db_fetch_row($ct);

            if($cr[0] == 'varchar25'){                               //-- (test 3) second field is a varchar
                $a[]  = $r[0];
            }
        
        }
 
    }

    return $a;
    
}

function nuNextNumber($t){

    $l     = db_columns($t);                                             //-- 2 columns (Primary Key, varchar(25))
    $u     = nuID();
    $s     = "INSERT INTO `$t` SET `$l[1]` = ? ";                       //-- insert to create next number
    $i     = nuRunQuery($s, array($u), true);
    
    $s     = "SELECT `$l[0]` FROM `$t` WHERE `$l[1]` = ? ";              //-- get next number
    $t     = nuRunQuery($s, array($u));
    $r     = db_fetch_row($t);
    
    return $r[0];                                                        //-- return next number

}

function nuClearDebug(){                                                 //-- remove older than 2 days

    $twoDays = mktime(0, 0, 0, date("m")  , date("d") - 2, date("Y"));
    $d       = date('Y-m-d h:i:s', $twoDays);
    
    nuRunQuery("DELETE FROM zzzsys_debug WHERE deb_added < ?", array($d));
    
}

function nuPHPAccess($i){

	$a = $GLOBALS['hashData']['nu_access_level'];

	if($a == 'globeadmin'){return true;}

	$s = "SELECT count(*) FROM zzzsys_php
		   WHERE zzzsys_php_id = ?
		   AND slp_nonsecure = '1'
		  ";	
		  
	$t = nuRunQuery($s, array($i));

	$r = db_fetch_row($t);

	if($r[0] != 0){return true;}                    //-- Non Secure
	
	$s = "SELECT count(*) FROM zzzsys_access_level_php
		   INNER JOIN zzzsys_access_level ON slp_zzzsys_access_level_id = zzzsys_access_level_id
		   WHERE (slp_zzzsys_php_id = ?
		   AND sal_code = ?)
		  ";	
	$t = nuRunQuery($s, array($i, $a));

	$r = db_fetch_row($t);

	return $r[0] != 0;                              //-- php allowed

}


function nuReportAccess($i){

	$a = $GLOBALS['hashData']['nu_access_level'];

	if($a == 'globeadmin'){return true;}

	$s = "SELECT count(*) FROM zzzsys_access_level_report
		   INNER JOIN zzzsys_access_level ON slr_zzzsys_access_level_id = zzzsys_access_level_id
		   WHERE slr_zzzsys_report_id = ?
		   AND sal_code = ?
		  ";	

	$t = nuRunQuery($s, array($i, $a));

	$r = db_fetch_row($t);
	
	return $r[0] != 0;        //-- report allowed

}



function nuEmailValidateAddress($email) {

   $isValid             = true;
   $atIndex             = strrpos($email, "@");

	if(is_bool($atIndex) && !$atIndex) {

		$isValid        = false;

	}else{

		$domain           = substr($email, $atIndex + 1);
		$local            = substr($email, 0, $atIndex);
		$localLen         = strlen($local);
		$domainLen        = strlen($domain);

		if ($localLen < 1 || $localLen > 64) {                                                                             //-- local part length exceeded
			$isValid      = false;
		} else if ($domainLen < 1 || $domainLen > 255) {                                                                   //-- domain part length exceeded
			$isValid      = false;
		} else if ($local[0] == '.' || $local[$localLen-1] == '.') {                                                       //-- local part starts or ends with '.'
			$isValid      = false;
		} else if (preg_match('/\\.\\./', $local)) {                                                                       //-- local part has two consecutive dots
			$isValid      = false;
		} else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {                                                       //-- character not valid in domain part
			$isValid      = false;
		} else if (preg_match('/\\.\\./', $domain)) {                                                                      //-- domain part has two consecutive dots
			$isValid      = false;
		} else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',str_replace("\\\\","",$local))) {         //-- character not valid in local part unless local part is quoted
			if (!preg_match('/^"(\\\\"|[^"])+"$/',
				str_replace("\\\\","",$local))){
				$isValid  = false;
			}
		}

		if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))) {                                          //-- domain not found in DNS
			$isValid      = false;
		}
	}
	
	return $isValid;
   
}



function nuSendEmail($to, $from, $fromname, $content, $subject, $filelist, $html = false, $cc = "", $bcc = "") {

    $toname                                      = '';
	//$html                                        = false;
	$wordWrap                                    = 120;
    
    $errorText                                   = "";
   	$setup                                       = $GLOBALS['nuSetup'];                                      //-- Read SMTP AUTH Settings from zzsys_setup table	
	
	if (!empty($setup->set_smtp_username)) 		{ $SMTPuser = trim($setup->set_smtp_username);}                        else{$errorText .= "SMTP Username not set.\n";}
	if (!empty($setup->set_smtp_password)) 		{ $SMTPpass = trim($setup->set_smtp_password);}                        else{$errorText .= "SMTP Password not set.\n";}
	if (!empty($setup->set_smtp_host)) 		    { $SMTPhost = trim($setup->set_smtp_host);}                            else{$errorText .= "SMTP Host not set.\n";}
	if (!empty($setup->set_smtp_from_address)) 	{ $SMTPfrom = trim($setup->set_smtp_from_address);}                    else{$errorText .= "SMTP From Address not set.\n";}
	if (!empty($setup->set_smtp_port)) 		    { $SMTPport = intval($setup->set_smtp_port);}                          else{$errorText .= "SMTP PORT not set.\n";}
	if (!empty($setup->set_smtp_use_ssl)) 		{ $SMTPauth = (intval($setup->set_smtp_use_ssl) == 1) ? true : false;} else{$SMTPauth = false;}
	if (!empty($setup->set_smtp_from_name)) 	{ $SMTPname = trim($setup->set_smtp_from_name);}	                   else{$SMTPname = "nuBuilder";}

	if ($errorText != '') {

		nuDisplayError("Unable to send SMTP Email, the following error(s) occured:\n" . $errorText);
		return;
        
	}
	
	require_once("phpmailer/class.phpmailer.php");

	try{

		$mail                        = new PHPMailer();
		$mail->IsSMTP();
		$mail->Host                  = $SMTPhost;
		$mail->Port                  = $SMTPport;
		$mail->SMTPSecure            = $SMTPauth ? 'ssl' : '';
		$mail->SMTPAuth              = $SMTPauth;
		$mail->CharSet           	 = 'iso-8859-7';
		
		if ($SMTPauth) {
			$mail->Username          = $SMTPuser;
			$mail->Password          = $SMTPpass;
		}

		if ($receipt) { 
			$mail->ConfirmReadingTo  = $from; 
		}


        $mail->AddReplyTo($from,$fromname);
        $mail->FromName = iconv('UTF-8','ISO-8859-7//IGNORE',$fromname);
		//$mail->FromName = $fromname;

		$mail->From                  = $from;
		$tonameArray                 = explode(',',$toname);
		$toArray                     = explode(',',$to);
	
		for ($i = 0; $i < myCount($toArray); $i++){
			if ($toArray[$i]) {
				if (isset($tonameArray[$i])) { 
					$thisToName      = $tonameArray[$i]; 
				} else { 
					$thisToName      = "";
				}
				$mail->AddAddress($toArray[$i], $thisToName);
			}
		}

        if($cc != ""){
            $ccArray = explode(',',$cc);
            foreach($ccArray as $ccAddress){
                $mail->AddCC($ccAddress);
            }
        }

        if($bcc != ""){
            $bccArray = explode(',',$bcc);
            foreach($bccArray as $bccAddress){
                $mail->AddBCC($bccAddress);
            }
        }

		$mail->WordWrap              = $wordWrap;
		$mail->IsHTML($html);

		foreach($filelist as $filename=>$filesource) {
			$mail->AddAttachment($filesource,$filename);
		}
		
		$mail->Subject               = $subject;
		$mail->Body                  = $content;

		$result[0]                   = $mail->Send();
		$result[1]                   = "Message sent successfully";

	}catch(phpmailerException $e) {
		$result[0]                   = false;
		$result[1]                   = $e->errorMessage();                                    //-- Pretty error messages from PHPMailer
	}catch(Exception $e){
		$result[0]                   = false;
		$result[1]                   = $e->errorMessage();                                    //-- Boring error messages from anything else!
	}

	foreach($filelist as $filename=>$filesource) {
		@unlink($filesource);
	}

    return $result;
}

function nuEmail($pPDForPHP, $pEmailTo, $pSubject, $pMessage, $hashData) { //-- Emails a PDF,PHP generated file or plain email (Requires hashdata of form to generate file from)
    
    if($hashData == '') {
        $hashData = nuHashData();
    }

    $session         = $hashData['session_id'];
    $sql             = "SELECT * FROM  zzzsys_session INNER JOIN zzzsys_user ON sss_zzzsys_user_id = zzzsys_user_id WHERE zzzsys_session_id = '$session'";
    $t               = nuRunQuery($sql);
    $r               = db_fetch_object($t);
    if($r != null) {
        $fromname    = $r->sus_name;
        $fromaddress = $r->sus_email;
    } else {
    	$setup       = $GLOBALS['nuSetup'];                                                           //-- Read SMTP AUTH Settings from zzsys_setup table	
        $fromname    = trim($setup->set_smtp_from_name);
        $fromaddress = trim($setup->set_smtp_from_address);
    }

	$filelist                                    = array();

    if($hashData['nu_pdf_code'] != '') {
    
        nuV('code', $pPDForPHP);
        nuV('call_type', 'printpdf');
        nuV('filename', $hashData['nu_email_file_name']);

        $hashData['parent_record_id']            = $hashData['nu_pdf_code'];
        $tmp_nu_file                             = nuPDForPHPParameters($hashData);
		$finfo                                   = finfo_open(FILEINFO_MIME_TYPE);                     //-- check to see if the file being sent is a PDF file
		
        if(finfo_file($finfo, $tmp_nu_file) != 'application/pdf') {
		
			nuDisplayError(file_get_contents($tmp_nu_file, true));
			finfo_close($finfo);

			return;
			
		}

    } else if($hashData['nu_php_code'] !=  '') {                                                          //-- Run PHP Code
    
        $s                                       = "SELECT zzzsys_php_id, slp_php FROM  zzzsys_php WHERE slp_code = '$pPDForPHP'";
        $t                                       = nuRunQuery($s);
        $r                                       = db_fetch_object($t);

	$r->slp_php = nuGetSafePHP('slp_php', $r->zzzsys_php_id, $r->slp_php);

        $php                                     = nuReplaceHashes($r->slp_php, $hashData);
        eval($php);
        return;
        
    }

    if($hashData['nu_pdf_code'] !=  '') {                                                              //-- File to attach, send with file
        $filelist[$hashData['nu_email_file_name']]  = $tmp_nu_file;
    }
    
    /*  	
    if(!nuEmailValidateAddress($pEmailTo)) {                                                          //-- check to see if to field email is valid
        nuDisplayError("To Email validation failed");
        return;
    }
    */	
	return nuSendEmail($pEmailTo, $fromaddress, $fromname, $pMessage, $pSubject, $filelist);
	
}

function nuGetSafePHP($field, $id, $value) {

	$check = $id.'_'.$field;

	// check if site is using nuBuilderProSafeMode
	if ( $_SESSION['SafeMode'] === true ) {

		// don't bother looking for file if the 'id' is not in Safe List Array
		if ( in_array($check, $_SESSION['SafePHP']) ) {

			// construct full file and path 
			$file      		= $id.'_'.$field;
			$full_file_and_path 	= dirname(__FILE__).'/nusafephp/'.$file;

			// get file contents
			$contents 		= @file_get_contents($full_file_and_path);

			// check if file contents is false, normally means file does not exist or file permission issues
			if ($contents === false) {
				nuDebug("error accessing file data in $full_file_and_path");
				$contents = '';
			}

		} else {
			$contents = '';
		}

	} else {
		// return default value if not in safe mode
		$contents = $value;
	}
	
	return $contents;
}

function nuCheckSafePHPMode($id, $r) {

	$fieldsToCheck = array(
		'sfo_custom_code_run_after_delete',
		'sfo_custom_code_run_after_save',
		'sfo_custom_code_run_before_browse',
		'sfo_custom_code_run_before_open',
		'sfo_custom_code_run_before_save'
	);

	if ( $_SESSION['SafeMode'] === true ) {

		for ( $x = 0; $x < count($fieldsToCheck); $x++ ) {

			$field = $fieldsToCheck[$x];
			if ( array_key_exists($field, $r) ) {

				$r[$field] = nuGetSafePHP($field, $id, $r[$field]); 
			} 
		}
	}

	return $r;
}



function nuDownloadFile($i, $s = ''){               //-- $s can be used to get type, name and blob from another table
	
	if($s == ''){
		
		$s = "SELECT sfi_type AS file_type, sfi_name AS file_name, sfi_blob AS file_blob FROM zzzsys_file WHERE zzzsys_file_id = '$i'";
		
	}
	
	$t = nuRunQuery($s);
	$r = db_fetch_object($t);
	
	header('Content-Type: '.$r->file_type.';');
	header('Content-Disposition: attachement;filename="'.$r->file_name.'";');
	
	$f = fopen('php://output', 'w');
	fwrite($f, $r->file_blob);
	fclose($f);

}


?>
