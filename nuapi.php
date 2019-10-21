<?php

header("Content-Type: application/json");
header("Cache-Control: no-cache, must-revalidate");
require_once('nucommon.php');

$response            = array();
$response['DATA']    = '';
$response['SUCCESS'] = false;
$response['ERRORS']  = array();
$response['TIMEOUT'] = 0;
$GLOBALS['ERRORS']   = array();
$GLOBALS['EXTRAJS']  = '';

nuGetFormProperties(nuV('form_id'));
$hashData            = nuHashData();
$GLOBALS['hashData'] = $hashData;

//==============================================================================
//                                  LOGIN
//==============================================================================
if (nuV('call_type') == 'login') {
    $response['DATA'] = nuLogin(nuV('username'), nuV('password'));
    nuClearDebug();
    
} else {

    $message          = nuValidateUser(nuV('session_id'), $hashData);                 //-- VALIDATE USER

    if ($message != '') {
        $response['ERRORS'][] = $message;
        print json_encode($response);
        return;
    }
}

//==============================================================================
//               CHECK ACCESS FOR RUNPHP OR PRINTPDF
//==============================================================================
if (nuV('call_type') == 'check_edit') {

    $J['user']        = nuCheckEdit();
    $response['DATA'] = json_encode($J);

}

//==============================================================================
//               CHECK ACCESS FOR RUNPHP OR PRINTPDF
//==============================================================================
if (nuV('call_type') == 'validateaccess') {

    $J['id']          = nuPDForPHPParameters($hashData, nuV('validate'));
    $J['iframe']      = nuV('iframe') == 0 ? 0 : 1;
    $response['DATA'] = json_encode($J);
	nuV('call_type', 'validateaccess');                          //-- reset calltype
}


//==============================================================================
//                               PRINTPDF OR RUNPHP
//==============================================================================
if (nuV('call_type') == 'printpdf' or nuV('call_type') == 'runphp') {


	if(nuV('iframe') == '0'){                                                      //-- don't check for blanks if coming from an iFrame
		$response['ERRORS'] = nuCheckParametersForm(nuV('form_data'));
	}else{
		$response['ERRORS'] = array();
	}
	
    if (count($response['ERRORS']) == 0) {                                         //-- if no error messages
		$J['id']          = nuPDForPHPParameters($hashData);                      //-- puts a JSON string in zzzsys_debug and returns the primary key
	}
    $J['iframe']      = nuV('iframe') == 0 ? 0 : 1;
    $response['DATA'] = json_encode($J);
}


//==============================================================================
//                               PRINTBROWSE
//==============================================================================
if (nuV('call_type') == 'runprintbrowse') {

    $J['id'] = nuGetBrowseHTML($hashData);                                         //-- puts a JSON string in zzzsys_debug and returns the primary key
    $response['DATA'] = json_encode($J);
}


//==============================================================================
//                              SAVE MOVED OBJECTS
//==============================================================================
if (nuV('call_type') == 'savemovedobjects') {

	if($_SESSION['IsDemo'] == 1){$response['ERRORS'][] = 'Unavailable in Demo';}   //-- ($nuConfigIsDemo = 1 in config.php)

	if (count($response['ERRORS']) == 0) {                                     //-- if no error messages
		nuSaveMovedObjects();
    }

	$hashData         = nuHashData();
	$response['DATA'] = nuGetEditForm($hashData);
}

//==============================================================================
//                              DELETEFORM
//==============================================================================
if (nuV('call_type') == 'deleteform') {

	if($_SESSION['IsDemo'] == 1){$response['ERRORS'][] = 'Unavailable in Demo';}   //-- ($nuConfigIsDemo = 1 in config.php)

	$beforeDelete      = nuReplaceHashes(nuF('sfo_custom_code_run_before_delete'), $hashData);
    
    eval($beforeDelete);
        
    if (count($response['ERRORS']) == 0) {                                     //-- if no error messages
        nuSaveForm(nuV('form_data'), $hashData);
    }
    
	$hashData         = nuHashData();
	$response['DATA'] = nuGetEditForm($hashData);
}

//==============================================================================
//                                SAVEFORM
//==============================================================================
if (nuV('call_type') == 'saveform') {

    $response['ERRORS']   = nuCheckForm(nuV('form_data'));
	
	if($_SESSION['IsDemo'] == 1){$response['ERRORS'][] = 'Unavailable in Demo';}   //-- ($nuConfigIsDemo = 1 in config.php)
	
	$nuHash             = $hashData;                                               //-- used in eval() along with hash variables

	$beforeSave         = nuReplaceHashes(nuF('sfo_custom_code_run_before_save'), $hashData);
    
    if (count($response['ERRORS']) == 0) {                                         //-- if no error messages
        eval($beforeSave);
        
        if (count($response['ERRORS']) == 0) {                                     //-- if still no error messages
            nuSaveForm(nuV('form_data'), $hashData);
        }
        
    }
    
    $hashData           = nuHashData();
    $response['DATA']   = nuGetEditForm($hashData);
    
}


//==============================================================================
//                              LOOKUPID
//==============================================================================
if (nuV('call_type') == 'lookupid') {
    $response['DATA'] = nuGetLookupData($hashData);
}

//==============================================================================
//                              LOOKUPCODE
//==============================================================================
if (nuV('call_type') == 'lookupcode') {
    $response['DATA'] = nuGetLookupData($hashData);
}


//==============================================================================
//                                 AUTOCOMPLETE
//==============================================================================
if (nuV('call_type') == 'autocomplete') {
    $response['DATA'] = nuGetAutocompleteData($hashData);
}

//==============================================================================
//                                  CLONEFORM
//==============================================================================
if (nuV('call_type') == 'cloneform') {

    $response['ERRORS']   = nuCheckForm(nuV('form_data'));
    if (count($response['ERRORS']) == 0) {
		nuSaveForm(nuV('form_data'), $hashData);
		$hashData         = nuHashData();
		$response['DATA'] = nuGetEditForm($hashData);
    }
}

//==============================================================================
//                                 GETEDITFORM
//==============================================================================
if (nuV('call_type') == 'geteditform') {
    $response['DATA'] = nuGetEditForm($hashData);
}

//==============================================================================
//                                GETBROWSEFORM
//==============================================================================
if (nuV('call_type') == 'getbrowseform') {
    $response['DATA'] = nuGetBrowseForm($hashData);
}

//==============================================================================
//                                GETLOOKUPFORM
//==============================================================================
if (nuV('call_type') == 'getlookupform') {
    $response['DATA'] = nuGetBrowseForm($hashData);
}

$response['ERRORS']  = array_merge($response['ERRORS'], $GLOBALS['ERRORS']);
$response['SUCCESS'] = true;
$response['TIMEOUT'] = nuV('nu_timeout');

nuRemoveTempTables($hashData);

print json_encode($response);

//===============================functions======================================




function nuGetBrowseHTML($hashData) {
    
    $formID            = nuV('form_id');
    $pageNo            = nuV('page_number');
    $J['session']      = nuV('session_id');
    $J['breadcrumb']   = nuV('breadcrumb');
    $J['call_type']    = nuV('call_type');
    $J['edit_browse']  = nuV('edit_browse');
    $J['form_id']      = nuV('form_id');
    $J['nu_user_name'] = nuV('nu_user_name');
    $t = nuRunQuery("SELECT * FROM zzzsys_form WHERE zzzsys_form_id = ? ", array(nuV('form_id')));
    if (nuErrorFound()) {
        return;
    }
    $r                 = db_fetch_object($t);

    $J['search']       = nuV('search');
    $J['filter']       = nuV('filter');
    $J['form_title']   = nuF('sfo_title');
    $J['set_title']    = nuV('set_title');
    
    $beforeBrowse = nuReplaceHashes(nuF('sfo_custom_code_run_before_browse'), $hashData);
    eval($beforeBrowse);

    return nuBuildTable($formID, $pageNo, $hashData);

}

function nuBuildTable($f, $p, $hashData) {
    
    $tt            = '__' . nuID() . '__';
    $OBJ           = array();
    $s             = "SELECT * FROM zzzsys_form WHERE zzzsys_form_id = '$f'";
    $t             = nuRunQuery($s);
    
    if (nuErrorFound()) {
        return;
    }
    
    $r             = db_fetch_object($t);
    $S             = "SELECT * FROM zzzsys_browse WHERE sbr_zzzsys_form_id = ? ORDER BY sbr_order";
    $T             = nuRunQuery($S, array($f));
    
    if (nuErrorFound()) {
        return;
    }

    $searchFields  = array();
    $searchColumns = explode(',', nuV('search_columns'));             //-- create an array of searchable columns
    $columnCount   = 1;
    
    while ($R = db_fetch_object($T)) {                                           //-- create columns
        
        $nuObject               = new stdClass;

//        $nuObject->title  = $R->sbr_title;
        $nuObject->title  = trim(preg_replace("/[^0-9a-zA-Z _]/", "", $R->sbr_title));      //-- changed 2015-04-07 by SC
        $nuObject->value  = $R->sbr_display;
        $nuObject->align  = $R->sbr_align;
        $nuObject->format = $R->sbr_format;
        $nuObject->width  = $R->sbr_width;

        $OBJ[]            = $nuObject;

        if (count($searchColumns) == 1) {
            nuV('search_columns', nuV('search_columns') . ",$columnCount");    //--  create a comma delimited string of searchable columns
            $searchFields[] = $R->sbr_display;
        } else {
            if (in_array($columnCount - 1, $searchColumns)) {
                $searchFields[] = $R->sbr_display;                           //-- searchable column
            }
        }
        $columnCount++;
    }

    if (nuV('edit_browse') == 'true') {
        $hashData = array_merge($hashData, nuGetCurrentData());            //-- use values on current page for hash data
    }

    $hashedSQL = $r->sfo_sql;
    $SQL       = new nuSqlString($hashedSQL);
    $SQL->removeAllFields();
    $SQL->addField($r->sfo_primary_key);
    $width     = 0;

    for ($i = 0; $i < count($OBJ); $i++) {

        if ($OBJ[$i]->format == '') {
            $SQL->addField("IFNULL(".$OBJ[$i]->value.",'') AS `" . $OBJ[$i]->title . "`");
        } else {
            $format   = nuTextFormats();
            $sql      = $format[$OBJ[$i]->format]->sql;
            $newField = str_replace('??', $OBJ[$i]->value, $sql);
            $SQL->addField("IFNULL(".$newField.",'') AS `" . $OBJ[$i]->title . "`");
        }
        $width = $width + $OBJ[$i]->width;
    }
    
    if (nuV('search') != '' or nuV('filter') != '') {
        if ($SQL->getWhere() == '') {
            $searchString = ' WHERE ' . nuBrowseWhereClause($searchFields, nuV('search') . ' ' . nuV('filter'));
        } else {
            $searchString = $SQL->getWhere() . ' AND ' . nuBrowseWhereClause($searchFields, nuV('search') . ' ' . nuV('filter'));
        }
        $SQL->setWhere($searchString);
    }
    
    if (nuV('sort') != 0) {
        $SQL->setOrderBy(' ORDER BY ' . nuV('sort') . ' ' . nuV('descending'));
    }

    $formattedSQL = nuReplaceHashes($SQL->SQL, $hashData);

    nuRunQuery("CREATE TABLE $tt $formattedSQL");
    
    if (nuErrorFound()) {
        return;
    }

    $J['objects'] = $OBJ;
    $J['records'] = $tt;                                                                     //-- temp tablename
    $j            = json_encode($J);
    $i            = nuID();
    $d            = date('Y-m-d h:i:s');
    
    nuRunQuery("INSERT INTO zzzsys_debug (zzzsys_debug_id, deb_message, deb_added) VALUES(?, ?, ?)", array($i, $j, $d));
    if (nuErrorFound()) {
        return;
    }

    return $i;
}

function nuSaveMovedObjects() {

    if (nuV('nu_user_name') != 'globeadmin') {
        return;
    }                       //-- only globeadmin can save move changes

    $m      = nuV('moved_objects');                                    //-- split up objects
    $f      = nuV('form_id');
    for ($a = 0; $a < count($m); $a++) {

		$i = $m[$a]['id'];
		$t = $m[$a]['top'];
		$l = $m[$a]['left'];
		$o = $m[$a]['order'];
        $s = "UPDATE zzzsys_object SET sob_all_column_number = '10', sob_all_order_number = '$o', sob_all_top = '$t' , sob_all_left = '$l'  WHERE sob_zzzsys_form_id = '$f' AND zzzsys_object_id = '$i' ";

        nuRunQuery($s);
        if (nuErrorFound()) {
            return;
        }
    }
}

function nuValidateUser($session, $hashData) {

    nuLogUser($session);                                                    //-- records user activity

    $timeout = time() - (60 * $GLOBALS['nuSetup']->set_time_out_minutes);

    nuRunQuery("DELETE FROM zzzsys_session WHERE sss_timeout < ? ", array($timeout));
    if (nuErrorFound()) {
        return;
    }
    $t       = nuRunQuery("SELECT * FROM zzzsys_session WHERE zzzsys_session_id = ? ", array($session));
    if (nuErrorFound()) {
        return;
    }
    $r       = db_fetch_object($t);

    $time    = time();
    $user    = $r->sss_zzzsys_user_id;

    if ($user == '') {
        return 'You are not currently logged in';                            //-- access to nothing
    }                                


    nuRunQuery("UPDATE zzzsys_session SET sss_timeout = $time WHERE zzzsys_session_id = ? ", array($r->zzzsys_session_id));
	nuV('nu_timeout',  $time);

    if (nuErrorFound()) {
        return;
    }

    if ($user == 'globeadmin') {
        return '';
    }                                                                        //-- access to everything

    $formID    = nuV('form_id');
    $recordID  = nuV('record_id');


    if(!in_array($formID, $_SESSION['nu_form_access'])){
    
		nuHomeBug();
		
        $t = nuRunQuery("SELECT CONCAT(sfo_name, ' - ', sfo_title) FROM zzzsys_form WHERE zzzsys_form_id = '$formID'");
        $r = db_fetch_row($t);
        nuDisplayError("You do not have access to this form (" . $r[0] . "). \r Please contact your system administrator");
        return;
		
    }


//===================save, new, clone and delete============================    

    if (nuV('call_type') == 'saveform') {                                     //-- new button also calls nuSaveForm()
    
		$sql       = "SELECT * FROM zzzsys_form WHERE zzzsys_form_id = '$formID'";
		$t         = nuRunQuery($sql);
		$r         = db_fetch_object($t);

        if($_SESSION['nu_access_'.$formID]['save'] == '1') {
            return "'Save' not allowed on this form for this user. \n Please contact your system administrator";
        }
        
    }
    
    if (nuV('call_type') == 'deleteform') {

		$sql       = "SELECT * FROM zzzsys_form WHERE zzzsys_form_id = '$formID'";
		$t         = nuRunQuery($sql);
		$r         = db_fetch_object($t);

        if($_SESSION['nu_access_'.$formID]['delete'] == '1') {
            return "'Delete' not allowed on this form for this user. \n Please contact your system administrator";
        }

    }
    
    if (nuV('call_type') == 'cloneform') {

		$sql       = "SELECT * FROM zzzsys_form WHERE zzzsys_form_id = '$formID'";
		$t         = nuRunQuery($sql);
		$r         = db_fetch_object($t);

        if($_SESSION['nu_access_'.$formID]['clone'] == '1') {
            return "'Clone' not allowed on this form for this user. \n Please contact your system administrator";
        }
    }
    
    return '';
}


//=================test for losing access====================================
function nuHomeBug(){

     $a = print_r($_SESSION['nu_form_access'],1);
	
	$user        = nuV('nu_user_name') . ' ( ' . nuV('call_type') . ' )';
	$array       = debug_backtrace();
			$trace       = '';
			
			for($i = 0 ; $i < count($array) ; $i ++){
				$trace  .= $array[$i]['file'] . ' - line ' . $array[$i]['line'] . ' (' . $array[$i]['function'] . ")\n\n";
			}

	$debug       = "

** HOME BUG	**
===USER==========

$user

===BACK TRACE====

$trace

===VARIABLE====

$a

	";
                
	nuDebug($debug);

}
//=====================================================


function nuValidateRecord($r, $hashData) {

    $recordID = nuV('record_id');

    if ($recordID == '-1') {
        return true;
    }
    if ($recordID == '') {
        return true;
    }

    $r->sfo_custom_code_run_before_browse 	= nuGetSafePHP('sfo_custom_code_run_before_browse', $recordID, $r->sfo_custom_code_run_before_browse);
    $bb      					= nuReplaceHashes($r->sfo_custom_code_run_before_browse, $hashData);
    eval($bb);

    $sfo_sql = nuReplaceHashes($r->sfo_sql, $hashData);
    $SQL     = new nuSqlString($sfo_sql);
    $table   = $SQL->getTableName();
    $SQL->addWhereClause("$r->sfo_primary_key = '$recordID'");

    $s       = "SELECT $r->sfo_primary_key FROM $table " . $SQL->getWhere();
    $t       = nuRunQuery($s);
    
    if (nuErrorFound()) {
        return;
    }
    $r       = db_fetch_row($t);
    if ($r[0] == '') {
        return false;
    } else {
        return true;
    }
}

function nuDisplayError($message) {
    $GLOBALS['ERRORS'][] = $message;
}

function nuHashArray($d) {

    $stamp                          = array();
    $hashData['PREVIOUS_RECORD_ID'] = $d['data'][0]['records'][0]['primary_key'];
    $array                          = array();

    for ($FORM = 0; $FORM < count($d['data']); $FORM++) {              //-- loop through forms (0 being the main form)

        $parentID = $d['data'][$FORM]['form_id'];

        if ($FORM == 0) {                     //-- form
            $s = "
                SELECT sfo_title as form_name, 
                    sfo_table as parent_table, 
                    sfo_primary_key as parent_primary_key 
                FROM zzzsys_form 
                WHERE zzzsys_form_id = '$parentID'
            ";
        } else {                              //-- subform
            $s = "
                SELECT 
                    sob_all_title           as form_name, 
                    sob_subform_table       as parent_table, 
                    sob_subform_primary_key as parent_primary_key, 
                    sob_all_name, 
                    sob_subform_foreign_key 
                FROM zzzsys_object 
                WHERE zzzsys_object_id = '$parentID'
            ";
        }
        $t        = nuRunQuery($s);
        if (nuErrorFound()) {
            return;
        }
        
        $formInfo = db_fetch_object($t);
		
		$RECORDS  = $d['data'][$FORM]['records'];     
        
        for ($R = 0; $R < count($RECORDS); $R++) {                     //-- loop through form field values
            
            $prefix = '';
            
            if($FORM > 0){
                $prefix = $formInfo->sob_all_name . sprintf('%04d', $R);
            }
            
            $RECORD = $RECORDS[$R];
            $PK     = addslashes($RECORD['primary_key']);
            $FIELDS = $RECORD['fields'];
            $DEL    = $RECORD['delete_record'];
            
            for ($F = 0; $F < count($FIELDS); $F++) {
                $FIELD = $FIELDS[$F];
                $array[$prefix . $FIELD['field']] = $FIELD['value'];            //-- build values into array
            }

        }
    }
    
    return $array;
    
}


function nuSaveForm($d, $hashData) {

    $stamp                          = array();
    $hashData['PREVIOUS_RECORD_ID'] = $d['data'][0]['records'][0]['primary_key'];

    for ($FORM = 0; $FORM < count($d['data']); $FORM++) {              //-- loop through forms (0 being the main form)
        $parentID = $d['data'][$FORM]['form_id'];

        if ($FORM == 0) {                     //-- form
            $s = "
                SELECT 
                    sfo_title       as form_name, 
                    sfo_table       as parent_table, 
                    sfo_primary_key as parent_primary_key 
                FROM zzzsys_form 
                WHERE zzzsys_form_id = '$parentID'
            ";
        } else {                              //-- subform
            $s = "
                SELECT 
                    sob_all_title           as form_name, 
                    sob_subform_table       as parent_table, 
                    sob_subform_primary_key as parent_primary_key, 
                    sob_subform_foreign_key 
                FROM zzzsys_object 
                WHERE zzzsys_object_id = '$parentID'
            ";
        }
        $t = nuRunQuery($s);
        if (nuErrorFound()) {
            return;
        }
        $formInfo = db_fetch_object($t);
		
		if(array_key_exists('records',$d['data'][$FORM])) {
        
            $RECORDS  = $d['data'][$FORM]['records'];     
            
            for ($R = 0; $R < count($RECORDS); $R++) {                                                                         //-- loop through form field values
                $SET         = array();
                $RECORD      = $RECORDS[$R];
                $PK          = addslashes($RECORD['primary_key']);
                $FIELDS      = $RECORD['fields'];
                $DEL         = $RECORD['delete_record'];
                $validFields = nuValidateArray($parentID, $formInfo->parent_table);

		$valid	     = array();	// ADDED by SG 9Jun2015
                for ($V = 0; $V < count($validFields); $V++) {
                    $valid[] = $validFields[$V]->field_name;
                }
                for ($F = 0; $F < count($FIELDS); $F++) {
                    $FIELD = $FIELDS[$F];
                    if (in_array($FIELD['field'], $valid) and $FIELD['save'] == 1) {

					if($FIELD['value'] == ''){
							$SET[] = addslashes($FIELD['field']) . ' = ' . 'NULL';                                             //-- Update empty Fields to NULL - added by OldShatterhand77 2014-02-07
						}else{
							$SET[] = addslashes($FIELD['field']) . ' = ' . "'" . addslashes($FIELD['value']) . "'";            //-- build UPDATE SET values
						}
                    }
					
                }

                if ($DEL == 'yes') {
                    $s     = "DELETE FROM $formInfo->parent_table WHERE $formInfo->parent_primary_key = '$PK'";
                } else {
                    if (count($SET) > 0) {
                        if ($PK == '') {
                            $PK      = nuGetNewPrimaryKey($formInfo->parent_table, $formInfo->parent_primary_key);             //-- insert a new record and return PK
                            $stamp[] = array('added', $formInfo->parent_table, $formInfo->parent_primary_key, $PK);
                        }
                        if ($FORM == 0) {            //-- main form
                            nuV('record_id', $PK);
                        } else {
                            $SET[]   = addslashes($formInfo->sob_subform_foreign_key) . ' = ' . "'" . addslashes(nuV('record_id')) . "'";   //-- add foreign key
                        }
                        $s       = "UPDATE $formInfo->parent_table SET " . implode(', ', $SET) . " WHERE $formInfo->parent_primary_key = '$PK'";
                        $stamp[] = array('changed', $formInfo->parent_table, $formInfo->parent_primary_key, $PK);
                    }
                    
                    if($FORM == 0) {
                            $hashData['RECORD_ID'] = $PK;
                    }
                }
                nuRunQuery($s);
                if (nuErrorFound()) {
                    return;
                }
            }
        }
    }
    nuLogStamp($stamp);
    
	$nuHash  = $hashData;                                                       //-- used in eval() along with hash variables
	
    if($d['data'][0]['records'][0]['delete_record'] == 'yes'){
        $eval = nuReplaceHashes(nuF('sfo_custom_code_run_after_delete'), $hashData);
    }else{
        $eval = nuReplaceHashes(nuF('sfo_custom_code_run_after_save'), $hashData);
    }
    eval($eval);
}


function nuLogStamp($stamp) {                                                    //-- records user activity
    for ($i = 0; $i < count($stamp); $i++) {

        $action      = $stamp[$i][0];
        $table       = $stamp[$i][1];
        $primary_key = $stamp[$i][2];
        $value       = $stamp[$i][3];
        $user        = nuV('nu_user_id');
        $d           = new DateTime();
        $date        = $d->format('Y-m-d H:i:s');
        $by          = $table . '_log_' . $action . '_by';
        $at          = $table . '_log_' . $action . '_at';

        $s           = "SELECT * FROM `$table` WHERE $primary_key = '$value'";

        $t           = nuRunQuery($s);
        if (nuErrorFound()) {
            return;
        }
        $r           = db_fetch_array($t);

	if (is_array($r)) {
        	foreach ($r as $key => $v) {
            		if ($key === $by) {
                		nuRunQuery("UPDATE `$table` set $key = '$user' WHERE $primary_key = '$value'");
                		if (nuErrorFound()) {
                    			return;
                		}
            		}
	
            		if ($key === $at) {
                		nuRunQuery("UPDATE `$table` set $key = '$date' WHERE $primary_key = '$value'");
                		if (nuErrorFound()) {
                    			return;
                		}
            		}
        	}
	}
    }
}

function nuCreateLog($id, $u) {                                                    //-- starts user activity
    $d    = new DateTime();
    $date = $d->format('Y-m-d H:i:s');
    $ip   = $_SERVER['REMOTE_ADDR'];
    $s    = "INSERT INTO zzzsys_user_log (zzzsys_user_log_id, sul_zzzsys_user_id, sul_ip, sul_start) VALUES ('$id', '$u', '$ip', '$date');";

    nuRunQuery($s);
    if (nuErrorFound()) {
        return;
    }
}

function sisLogFailure($un) { 

    $id   = nuID(); 
                                                      //-- Log Login Failure
    $d    = new DateTime();
    $date = $d->format('Y-m-d H:i:s');

    $ip   = $_SERVER['REMOTE_ADDR'];

    $s    = "INSERT INTO zzzsys_user_login_failure (zzzsys_user_login_failure_id, sul_ip, sul_datetime, sul_username) VALUES ('$id', '$ip', '$date', '$un')";

    nuRunQuery($s);
    if (nuErrorFound()) {
        return;
    }

    $setup = $GLOBALS['nuSetup'];

    $interval = (is_null($setup->set_lock_login_minutes) || $setup->set_lock_login_minutes==0 ? 60 : $setup->set_lock_login_minutes);

    $s = "DELETE FROM zzzsys_user_login_failure WHERE (sul_datetime < now() - INTERVAL $interval MINUTE)";
    nuRunQuery($s);
    if (nuErrorFound()) {
        return;
    }
}

function sisCountFailure($interval,$un) { 

    $ip   = $_SERVER['REMOTE_ADDR'];
    $s = "SELECT COUNT(*) as attempts FROM zzzsys_user_login_failure WHERE (sul_datetime >= now() - INTERVAL $interval MINUTE) AND sul_ip = '$ip' AND sul_username = '$un'";

    $t = nuRunQuery($s);
    if (nuErrorFound()) {
        return;
    }

    $r = db_fetch_object($t);
    
    return($r->attempts);
}

function nuLogUser($id) {                                                    //-- records user activity
    $d    = new DateTime();
    $date = $d->format('Y-m-d H:i:s');

    nuRunQuery("UPDATE zzzsys_user_log SET sul_end  = '$date' WHERE zzzsys_user_log_id = '$id'");
    if (nuErrorFound()) {
        return;
    }
}

function nuGetNewPrimaryKey($t, $p) {

    global $nuDB;
    $t  = trim($t);
    $p  = trim($p);
    $id = nuID();

    if (db_is_auto_id($t, $p)) {                                       //-- create auto id
        $i    = 'NULL';
        $auto = true;
    } else {                                                           //-- create string id
        $i    = "'$id'";
        $auto = false;
    }
    $r  = $nuDB->exec("INSERT INTO `$t` (`$p`) VALUES ($i)");  //-- insert record before updating
    if ($auto) {
        return $nuDB->lastInsertId();
    } else {
        return $id;
    }
}

function nuIsForm($parentID) {

    $t = nuRunQuery("SELECT * FROM zzzsys_form WHERE zzzsys_form_id = '$parentID'");
    if (nuErrorFound()) {
        return;
    }
    $r = db_fetch_object($t);
    if ($r->zzzsys_form_id == '') {
        return false;
    } else {
        return true;
    }
}

function nuCheckParametersForm($d) {

    $errors   = array();
	$i        = $d['data'][0]['form_id'];
	$fields   = $d['data'][0]['records'][0]['fields'];
	$o        = array();

	for($f = 0 ; $f < count($fields) ; $f ++){
		$o[$fields[$f]['field']] = $fields[$f]['value']; // PHP7 '' added
	}
	
	$s        = "SELECT * FROM zzzsys_object WHERE sob_all_no_blanks = '1' AND sob_zzzsys_form_id = '$i' ORDER BY sob_all_tab_number, sob_all_column_number, sob_all_order_number";
	$t        = nuRunQuery($s);
	
	while($r = db_fetch_object($t)){
	
		if($o[$r->sob_all_name] == ''){
			$errors[] = "'" . addslashes($r->sob_all_title) . "' Cannot be left blank";
		}
		
	}

    return $errors;

}

function nuCheckForm($d) {

    $errors = array();

    for ($FORM = 0; $FORM < count($d['data']); $FORM++) {                                 //-- loop through forms (0 being the main form)
        $parentID = $d['data'][$FORM]['form_id'];

        if ($FORM == '0') {                                                                 //-- form
            $s = "
                SELECT 
                    sfo_title       as form_name, 
                    sfo_table       as parent_table, 
                    sfo_primary_key as parent_primary_key 
                FROM zzzsys_form 
                WHERE zzzsys_form_id = '$parentID'
            ";
            $mainForm = true;
        } else {                                                                            //-- subform
            $s = "
                SELECT 
                    sob_all_title           as form_name, 
                    sob_subform_table       as parent_table, 
                    sob_subform_primary_key as parent_primary_key 
                FROM zzzsys_object 
                WHERE zzzsys_object_id = '$parentID'
            ";
            $mainForm = false;
        }
        $t = nuRunQuery($s);
        if (nuErrorFound()) {
            return;
        }
        $formInfo    = db_fetch_object($t);
        $validFields = nuValidateArray($parentID, $formInfo->parent_table);       //-- list of valid fields on this Form
        if(array_key_exists('records',$d['data'][$FORM])) $RECORDS = $d['data'][$FORM]['records'];

        for ($R = 0; $R < count($RECORDS); $R++) {

            $RECORD = $RECORDS[$R];
            $FIELDS = $RECORD['fields'];

            if ($RECORD['delete_record'] <> 'yes') {                                    //-- does need to be checked 
                for ($F = 0; $F < count($FIELDS); $F++) {
                   $FIELD = $FIELDS[$F];
                    $error = nuValidField($FIELD, $validFields, $formInfo, $RECORD['primary_key'], $R + 1, $mainForm, $parentID);     //-- check for valid table fieldname and value

                    if (count($error) != 0) {
                        $errors[] = $error->message;
                    }
                }
            }
        }
    }
    return $errors;
}

function nuValidField($FIELD, $validFields, $formInfo, $primary_key, $ROW, $mainForm, $parentID) {

    for ($i = 0; $i < count($validFields); $i++) {
        $val = addslashes($FIELD['value']);
        $fld = addslashes($FIELD['field']);
        
        if ($validFields[$i]->field_name == $fld) {                                                                                    //-- using a valid field name
            if (($validFields[$i]->no_blanks == '1' or $validFields[$i]->no_duplicates == '1' ) and $val == '') {                  //-- cannot be left blank
                $ERR = nuGetTabAndTitle($fld, $mainForm, $parentID);
				if ($mainForm) {                                                                                               //-- main form
					$ERR->message = "$ERR->title " . nuTranslate('cannot be left blank') . "..  (" . nuTranslate('TAB') . " : $ERR->tab)";
				} else {
					$ERR->message = "$ERR->title " . nuTranslate('in subform') . " " . nuTranslate('on row') . " $ROW " . nuTranslate('cannot be left blank') . "..";
				}
                return $ERR;
            }
            if ($validFields[$i]->no_duplicates == '1') {                                                                          //-- check for duplicates
                $s = "
                    SELECT $formInfo->parent_primary_key 
                    FROM $formInfo->parent_table 
                    WHERE $fld = '$val' AND $formInfo->parent_primary_key != '$primary_key'
                ";
                $t = nuRunQuery($s);
                if (nuErrorFound()) {
                    return;
                }
                $r = db_fetch_row($t);

                if ($r[0] != '') {                                                                                             //-- duplicate record
                    $ERR = nuGetTabAndTitle($fld, $mainForm, $parentID);
					if ($mainForm) {                                                                                       //-- main form
						$ERR->message = nuTranslate('There is already a record with a') . " $ERR->title " . nuTranslate('with a value of') . " $val..  (" . nuTranslate('TAB') . " : $ERR->tab)";
					} else {
						$ERR->message = nuTranslate('There is already a record with a') . " $ERR->title " . nuTranslate('with a value of') . " $val " . nuTranslate('in subform') . " $formInfo->form_name " . nuTranslate('on row') . " $ROW..  (" . nuTranslate('TAB') . " : $ERR->tab)";
					}
                    return $ERR;
                }
            }

            if ($validFields[$i]->format == 'text' and $validFields[$i]->format != '' and $val != '') {                                                                     //-- check format
                $format   = nuTextFormats();
                $datatype = $format[$validFields[$i]->format]->type;

                if ($datatype == 'date') {
                    $s = "SELECT IF('$val' = DATE_FORMAT('$val','%Y-%m-%d'),'1','0')";
                    $t = nuRunQuery($s);
                    if (nuErrorFound()) {
                        return;
                    }
                    $r = db_fetch_row($t);
                    if ($r[0] == '1') {
                        return array();
                    } else {

                        $ERR = nuGetTabAndTitle($fld, $mainForm, $parentID);
						if ($mainForm) {                                                                                  //-- main form
							$ERR->message = "$ERR->title " . nuTranslate('has an invalid date') . ".. (" . nuTranslate('TAB') . " : $ERR->tab)";
						} else {
							$ERR->message = "$ERR->title " . nuTranslate('has an invalid date') . " " . nuTranslate('in subform') . " $formInfo->form_name " . nuTranslate('on row') . " $ROW..  (" . nuTranslate('TAB') . " : $ERR->tab)";
						}
                        return $ERR;
                    }
                }
                if ($datatype == 'number') {
                    $s = "SELECT IF($val = $val+0,'1','0')";
                    $t = nuRunQuery($s);
                    if (nuErrorFound()) {
                        return;
                    }
                    $r = db_fetch_row($t);
                    if ($r[0] == '1') {
                        return array();
                    } else {

                        $ERR = nuGetTabAndTitle($fld, $mainForm, $parentID);

						if ($mainForm) {                                                                                   //-- main form
							$ERR->message = "$ERR->title " . nuTranslate('has an invalid number') . ".. (" . nuTranslate('TAB') . " : $ERR->tab)";
						} else {
							$ERR->message = "$ERR->title " . nuTranslate('has an invalid number') . " " . nuTranslate('in subform') . " $formInfo->form_name " . nuTranslate('on row') . " $ROW..  (" . nuTranslate('TAB') . " : $ERR->tab)";
						}
                        return $ERR;
                    }
                }
            } else {
                return array();                                                                                                  //-- no format required
            }
        }
    }
    return array();
}

function nuGetTabAndTitle($fld, $isMainForm, $id) {

	$t = nuRunQuery("SELECT zzzsys_object_id FROM zzzsys_object WHERE sob_all_name = '$fld'");            //-- get Object ID
	if (nuErrorFound()) {
		return;
	}
	$r               = db_fetch_row($t);
	$id              = $r[0];

    $t = nuRunQuery("SELECT * FROM zzzsys_object WHERE zzzsys_object_id = '$id'");               //-- get Object details
    if (nuErrorFound()) {
        return;
    }
    $r                = db_fetch_object($t);
    $nuError          = new stdClass;
    $nuError->title   = $r->sob_all_title;
    $nuError->tab     = $r->sob_all_tab_title;
    $nuError->field   = $fld;
    $nuError->message = '';

    return $nuError;
}

function nuValidateArray($parentID, $table) {

    $t    = nuRunQuery("SHOW FULL FIELDS FROM `$table`");
    if (nuErrorFound()) {
        return;
    }
    $flds = array();   //-- fields in table
    $a    = array();   //-- fields on Form
    while ($r = db_fetch_object($t)) {
    
        $flds[] = $r->Field;
    }


    $s = "
        SELECT 
			sob_all_name          AS field_name, 
			sob_all_type          AS field_type, 
			sob_all_title         AS field_title, 
			sob_all_no_blanks     AS no_blanks, 
			sob_all_no_duplicates AS no_duplicates, 
			sob_text_format   AS format
		FROM zzzsys_object 
		WHERE sob_zzzsys_form_id = '$parentID' 
		ORDER BY 
			sob_all_tab_number, 
			sob_all_column_number, 
			sob_all_order_number
	";

    $t = nuRunQuery($s);
    if (nuErrorFound()) {
        return;
    }

    while ($r = db_fetch_object($t)) {

        if (in_array($r->field_name, $flds)) {
            $a[] = $r;
        }
    }
    return $a;
}

function validate_email_account($u, $p){
	if (($u = filter_var($u, FILTER_VALIDATE_EMAIL)) !== false) {

		$domain = substr(strrchr($u, '@'), 1);

		$s = "
			SELECT *
			FROM email_login_server 
			WHERE domain_name = ?
		";
		
		
		$t = nuRunQuery($s, array($domain));
		if (nuErrorFound()) {
			return false;
		}
		$r = db_fetch_object($t);
		
		if ($r->server_name != '' && $r->port_number!='') {

			if($r->use_full_address_as_login_name=='0'){
				$user_name = substr($u, 0, strrpos($u, '@'));
			} else {
				$user_name = $u;
			}

			$authhost="{".$r->server_name.":".$r->port_number."/imap".($r->use_ssl=="1" ? "/ssl": "")."/novalidate-cert}INBOX";
			
			$options = 0;
			$retry = 1;

			if ($mbox=imap_open( $authhost, $user_name, $p, $options, $retry )){
				imap_close($mbox);
				return true;
			}
		}
	}
	
	return false;
}

function nuLogin($u, $p) {


    $ip                  = $_SERVER['REMOTE_ADDR'];
    $i                   = nuID();
    
    $time = time();
	
//------------------------------------------------------------------------------------------ check for attempts
    $setup = $GLOBALS['nuSetup'];

    if(!is_null($setup->set_lock_login_minutes) && !is_null($setup->set_numb_of_login_attempts) && $setup->set_lock_login_minutes!=0 && $setup->set_numb_of_login_attempts!=0){

        if(sisCountFailure($setup->set_lock_login_minutes,$u)>=$setup->set_numb_of_login_attempts){
            $A['session_id'] = 'Login Failed';
            return $A;    
        }
    }	
	
//------------------------------------------------------------------------------------------ globeadmin    
/*
    if ($u == 'globeadmin' and $p == $_SESSION['DBGlobeadminPassword']) {   //-- globeadmin
        $s               = "INSERT INTO zzzsys_session (zzzsys_session_id, sss_zzzsys_user_id, sss_timeout) VALUES ('$i','globeadmin', $time)";
        nuRunQuery($s);
		nuV('nu_timeout',  $time);
        nuCreateLog($i, 'globeadmin');

        if (nuErrorFound()) {
            return;
        }
        $sessionData     = nuSessionArray($i);
        $A['index_id']   = 'nuindex';
        $A['session_id'] = $i;
        nuSet_SESSION($i, $u);                                     //-- add accessible forms, php and reports
        return $A;
    }
*/
//------------------------------------------------------------------------------------------ email login

    $s = "
        SELECT zzzsys_user_id,login_with_email_account
        FROM zzzsys_user 
        WHERE sus_login_name = ? AND zzzsys_user_id != 'globeadmin'
    ";
	
    $t = nuRunQuery($s, array($u));
    if (nuErrorFound()) {
        return;
    }
    
    $r = db_fetch_object($t);
    if ($r->zzzsys_user_id == '') {                              
        $A['session_id'] = 'Login Failed';
        sisLogFailure($u);
        return $A;
    } else if($r->login_with_email_account=='1'){	//-- login with email account

        if(validate_email_account($u, $p)){
			$s = "INSERT INTO zzzsys_session (zzzsys_session_id, sss_zzzsys_user_id, sss_timeout) VALUES ('$i','$r->zzzsys_user_id', $time)";
			nuRunQuery($s);
			nuV('nu_timeout',  $time);
			nuCreateLog($i, $r->zzzsys_user_id);

			if (nuErrorFound()) {
				return;
			}
			$sessionData     = nuSessionArray($i);
			$A['index_id']   = $sessionData['nu_index_id'];
			$A['session_id'] = $i;
			nuSet_SESSION($i, $u);                                     //-- add accessible forms, php and reports
			return $A;		
		} else {
			$A['session_id'] = 'Login Failed';
            sisLogFailure($u);
			return $A;
		}
    } else {	
//------------------------------------------------------------------------------------------ normal user
		$s = "
			SELECT *
			FROM zzzsys_user 
			WHERE (sus_login_name = ? AND sus_login_password = md5(CONCAT(?, ?))) 
                                  AND zzzsys_user_id != 'globeadmin'
		";
	
		$t = nuRunQuery($s, array($u, $u, $p));
		if (nuErrorFound()) {
			return;
		}
    
		$r = db_fetch_object($t);
		if ($r->zzzsys_user_id == '') {                              //-- normal user
			$A['session_id'] = 'Login Failed';
            sisLogFailure($u);
			return $A;
		} else {

			$s = "INSERT INTO zzzsys_session (zzzsys_session_id, sss_zzzsys_user_id, sss_timeout) VALUES ('$i','$r->zzzsys_user_id', $time)";
			nuRunQuery($s);
			nuV('nu_timeout',  $time);
			nuCreateLog($i, $r->zzzsys_user_id);

			if (nuErrorFound()) {
				return;
			}
			$sessionData     = nuSessionArray($i);
			$A['index_id']   = $sessionData['nu_index_id'];
			$A['session_id'] = $i;
			nuSet_SESSION($i, $u);                                     //-- add accessible forms, php and reports
			return $A;
		}
	}
}


function nuSet_SESSION($id, $u){

    $_SESSION['session_id']       = $id;	
    $_SESSION['nu_form_access']   = array();
    $_SESSION['nu_php_access']    = array();
    $_SESSION['nu_report_access'] = array();

    if($u == 'globeadmin'){                                                                                        //-- get all forms, php and reports
    
        $a = array('form', 'php', 'report');
        
        for($i = 0 ; $i < count($a) ; $i ++){
        
            $t = nuRunQuery('SELECT * FROM zzzsys_' . $a[$i]);
        
            while($r = db_fetch_row($t)){
                $_SESSION['nu_' . $a[$i] . '_access'][] = $r[0];
            }
            
        }
        
    }else{
    
        $s  = "SELECT sss_zzzsys_user_id FROM zzzsys_session WHERE zzzsys_session_id = ? ";                              //-- get user id
        $t  = nuRunQuery($s, array($id));
        $r  = db_fetch_row($t);
        $ui = $r[0];

        $s  = "
            SELECT 
                sug_zzzsys_access_level_id, 
                sal_zzzsys_form_id 
            FROM zzzsys_user_group
            INNER JOIN zzzsys_user         ON sus_zzzsys_user_group_id    = zzzsys_user_group_id
            INNER JOIN zzzsys_access_level ON sug_zzzsys_access_level_id  = zzzsys_access_level_id
            WHERE zzzsys_user_id = ?
        ";
        
        $t                            = nuRunQuery($s, array($ui));
        $r                            = db_fetch_row($t);

        $a                            = $r[0];
        $_SESSION['nu_form_access'][] = $r[1];                                                                               //-- Home form
        
        $s = "
            SELECT 
                slf_zzzsys_form_id, 
                slf_add_button, 
                slf_save_button, 
                slf_delete_button, 
                slf_clone_button, 
                slf_new_button, 
                slf_print_button 
            FROM zzzsys_access_level_form 
            WHERE slf_zzzsys_access_level_id = ? 
        ";                                                                                                       //-- accessible forms and actions
            
        $t                            = nuRunQuery($s, array($a));
        
        while($r = db_fetch_row($t)){
		
			$_SESSION['nu_form_access'][]                          = $r[0];
			$_SESSION['nu_access_'.$r[0]]                          = array();
            
            if($r[1]=='1'){$_SESSION['nu_access_'.$r[0]]['add']    = '1';}
            if($r[2]=='1'){$_SESSION['nu_access_'.$r[0]]['save']   = '1';}
            if($r[3]=='1'){$_SESSION['nu_access_'.$r[0]]['delete'] = '1';}
            if($r[4]=='1'){$_SESSION['nu_access_'.$r[0]]['clone']  = '1';}
            if($r[5]=='1'){$_SESSION['nu_access_'.$r[0]]['new']    = '1';}
            if($r[6]=='1'){$_SESSION['nu_access_'.$r[0]]['print']  = '1';}
			
        }

        $s = "
            SELECT slp_zzzsys_php_id 
            FROM zzzsys_access_level_php 
            WHERE slp_zzzsys_access_level_id = ?
        ";              //-- accessible php
        $t = nuRunQuery($s, array($a));
        
        while($r = db_fetch_row($t)){
            $_SESSION['nu_php_access'][] = $r[0];
        }
        
        $s = "
            SELECT slr_zzzsys_report_id 
            FROM zzzsys_access_level_report 
            WHERE slr_zzzsys_access_level_id = ? 
        ";        //-- accessible reports

        $t = nuRunQuery($s, array($a));
        
        while($r = db_fetch_row($t)){
            $_SESSION['nu_report_access'][] = $r[0];
        }

    }

}

function nuFormAccess($f, $b = ''){

    if($b == ''){                                              //-- just check for form access
    
        return in_array($f, $_SESSION['nu_form_access']);

    }else{
   
        return $_SESSION['nu_access_'.$f][$b] != '1';          //-- button access allowed
        
    }

}

function nuGetAutocompleteData($hashData) {

    $searchFor    = nuV('record_id');
    $objectID     = nuV('object_id');
    $J['session'] = nuV('session_id');
    $s            = "SELECT * FROM zzzsys_object WHERE zzzsys_object_id = '$objectID'";
    $t            = nuRunQuery($s);
    if (nuErrorFound()) {
        return;
    }
    $o            = db_fetch_object($t);
    $id           = $o->sob_lookup_id_field;
    $code         = $o->sob_lookup_code_field;
    $desc         = $o->sob_lookup_description_field;
    $form         = $o->sob_lookup_zzzsys_form_id;
    $s            = "SELECT * FROM zzzsys_form WHERE zzzsys_form_id = '$form'";
    $T            = nuRunQuery($s);
    if (nuErrorFound()) {
        return;
    }

    $f            				= db_fetch_object($T);
    $f->sfo_custom_code_run_before_browse       = nuGetSafePHP('sfo_custom_code_run_before_browse', $f->zzzsys_form_id, $f->sfo_custom_code_run_before_browse);
    $bb           				= nuReplaceHashes($f->sfo_custom_code_run_before_browse, $hashData);
    eval($bb);

    $searchIn     = $code;

	
    $SQL            = new nuSqlString($f->sfo_sql);
	
	if($SQL->where == ''){
		$SQL->where = "WHERE";
	}else{
		$SQL->where = "$SQL->where AND";
	}
	$s            = "SELECT $code, $desc $SQL->from $SQL->where ($searchIn like '%$searchFor%' or $desc like '%$searchFor%')";
    $s            = nuReplaceHashes($s, $hashData);
    $T            = nuRunQuery($s);
    if (nuErrorFound()) {
        return;
    }
    $rows         = $T->rowCount();

    $re           = array();
    $i            = 0;
    while ($r = db_fetch_row($T)) {
        if ($i++ < 100) {
            array_push($re, array('label' => preg_replace('/' . $searchFor . '/i', "<span class='browsematch'>$0</span>", $r[0] . ' - ' . $r[1]), 'value' => $r[0]));
        }
        else
            break;
    }

    $J['results']   = $re;
    $J['prefix']    = nuV('prefix');
    $J['lookup_id'] = nuV('lookup_id');

    return json_encode($J);
}

function nuGetLookupData($hashData) {

    $searchFor    = addslashes(nuV('record_id'));
    $objectID     = nuV('object_id');
    $J['session'] = nuV('session_id');
    $s            = "SELECT * FROM zzzsys_object WHERE zzzsys_object_id = '$objectID'";
    $t            = nuRunQuery($s);
    if (nuErrorFound()) {
        return;
    }
    $o            = db_fetch_object($t);
    $id           = $o->sob_lookup_id_field;
    $code         = $o->sob_lookup_code_field;
    $desc         = $o->sob_lookup_description_field;
    $form         = $o->sob_lookup_zzzsys_form_id;
    $nuJavascript = $o->sob_lookup_javascript;
    $s            = "SELECT * FROM zzzsys_form WHERE zzzsys_form_id = '$form'";
    $T            = nuRunQuery($s);
    if (nuErrorFound()) {
        return;
    }

    $f            				= db_fetch_object($T);
    $f->sfo_custom_code_run_before_browse 	= nuGetSafePHP('sfo_custom_code_run_before_browse', $f->zzzsys_form_id, $f->sfo_custom_code_run_before_browse);
    $bb           				= nuReplaceHashes($f->sfo_custom_code_run_before_browse, $hashData);
    eval($bb);

	
    $s            = "SELECT * FROM zzzsys_lookup WHERE slo_zzzsys_object_id = '$objectID'";
    $t            = nuRunQuery($s);
    if (nuErrorFound()) {
        return;
    }
    $ON           = array();                                                                        //-- Object Name
    $su           = "";


	
	$o->sob_lookup_php = nuGetSafePHP('sob_lookup_php', $objectID, $o->sob_lookup_php);	
	$o->sob_lookup_php = str_replace('#RECORD_ID#', nuV('record_id'), $o->sob_lookup_php);
	$o->sob_lookup_php = str_replace('#call_type#', nuV('call_type'), $o->sob_lookup_php);

	eval($o->sob_lookup_php);                                                                       //-- define any php functions that may be used
	
    while ($r = db_fetch_object($t)) {
	
        $su         = $su . ', ' . nuGetFieldFuctionValue($r->zzzsys_slo_field_function_name, $o);
        array_push($ON, $r->zzzsys_slo_object_name);
		
    }
	
    if (nuV('call_type') == 'lookupid') {
        $searchIn   = $id;
    } else {
        $searchIn   = $code;
    }
	
    $SQL            = new nuSqlString($f->sfo_sql);
	
	if($SQL->where == ''){
		$SQL->where = "WHERE";
	}else{
		$SQL->where = "$SQL->where AND";
	}

    $s              = "SELECT $id, $code, $desc $su $SQL->from $SQL->where ($searchIn = '$searchFor')";
    $s              = nuReplaceHashes($s, $hashData);
    $T              = nuRunQuery($s);
    if (nuErrorFound()) {
        return;
    }
    $rows           = $T->rowCount();

    if ($rows == 1) {
        $r          = db_fetch_row($T);
    } elseif ($rows == 0) {
        $r[0]       = '';
        $r[1]       = '';
        $r[2]       = '';
    } else {
        $r[0]       = 'many records';
        $r[1]       = 'many records';
        $r[2]       = 'many records';
    }
	
    $lf             = array();
	
	if($rows == 0) {
		for($i = 0; $i < count($ON); $i++) {
			$lf[$ON[$i]]      = '';
		}
	}else{
		for ($i = 3; $i < count($r); $i++) {
            $objName          = $ON[$i - 3];
			$lf[$objName]     = nuFormatValue($r[$i], nuGetFormatNumber(nuV('parent_form_id'), $objName));
		}
	}

    $J['id']                  = $r[0];
    $J['code']                = $r[1];
    $J['description']         = $r[2];
	$J['javascript']          = $nuJavascript;
    $J['lookup_other_fields'] = $lf;
    $J['prefix']              = nuV('prefix');
    $J['lookup_id']           = nuV('lookup_id');

    return json_encode($J);
}

function nuGetFieldFuctionValue($f, $o){

    if(substr(trim($f),-1) == ')'){                                           //-- should be a function - has an ending bracket

        $pos = strrpos($f, '(');
        
        if ($pos === false) {                                                 //-- not a function - has no beginning bracket
        
            return "'error!'";

		}else{

            $fpos = strrpos($o->sob_lookup_php, trim(substr($f,0,$pos)));
			
            if ($fpos === false) {                                            //-- should be an sql function

				return $f;
				
            }else{                                                            //-- a valid php function defined in sob_lookup_php
			
                $eval = '$nuvar = '. $f . ';';
                eval($eval);
				return "'" . str_replace("'", "\\'", $nuvar) . "'";
				
            }
        }
    
    }else{
        return $f;
    }
}

function nuGetFormatNumber($formID, $objName){

    $s = "SELECT sob_text_format FROM zzzsys_object WHERE sob_zzzsys_form_id = '$formID' AND sob_all_name = '$objName'";
    $t = nuRunQuery($s);
    $r = db_fetch_row($t);
    
    return $r[0].'';
}



function nuGetEditForm($hashData) {

    if (nuF('sfo_width') > 0) {
        $J['form_width']  = nuF('sfo_width');
    } else {
        $J['form_width']  = 1000;
    }
    if (nuF('sfo_height') > 0) {
        $J['form_height'] = nuF('sfo_height');
    } else {
        $J['form_height'] = 500;
    }

    $beforeOpen = nuReplaceHashes(nuF('sfo_custom_code_run_before_open'), $hashData);
    eval($beforeOpen);
    $formID                   = nuV('form_id');
    $J['nu_user_name']        = nuV('nu_user_name');
    $J['record_id']           = nuV('record_id');
    $J['session']             = nuV('session_id');
    $J['form_title']          = nuF('sfo_title');
    $nuRepositonObjects       = nuGetRepositonObjects();
	$J['draggable_objects']   = nuGetDraggableObjects();
    $js                       = nuF('sfo_custom_code_run_javascript');
    $J['form_javascript']     = $nuRepositonObjects . nuReplaceHashes($js, $hashData);
    nuPHPPDFBreadcrumb();
    if($GLOBALS['EXTRAJS'] != ''){
        $J['form_javascript'] = $J['form_javascript'] . "\n\n\n//-- Custom Javascript Added via the PHP function : nuAddJavascript()\n\n\n" . $GLOBALS['EXTRAJS'];
    }

    $data                     = nuGetObjectsForOneRecord('form', $formID, nuV('record_id'), $hashData);
    $buttons                  = nuGetEditButtons($formID, $hashData);
    $J['buttons']             = $buttons;

	if(count($data['objects']) == 1){                                           //-- fake an Object
	
		$nuObject                = new stdClass;

		$nuObject->title         = '';
		$nuObject->o_id          = 'nu_fake';
		$nuObject->r_id          = '';
		$nuObject->f_id          = $formID;
		$nuObject->type          = 'words';
		$nuObject->field         = 'nu_fake_id';
		$nuObject->column        = '1';
		$nuObject->order         = '1';
		$nuObject->tab           = '1';
		$nuObject->tab_title     = 'No Objects';
		$nuObject->top           = '';
		$nuObject->left          = '';
		$nuObject->height        = '0';
		$nuObject->no_blanks     = '0';
		$nuObject->read_only     = '0';
		$nuObject->no_duplicates = '0';
		$nuObject->align         = 'l';
		$nuObject->display       = '1';
		$nuObject->events        = '[]';
		$nuObject->width         = '1';

		$data['objects'][]       = $nuObject;
	}
		
	$J['objects']    = $data['objects'];
	$J['records']    = $data['records'];
	$J['edited']     = $data['edited'];
	$J['breadcrumb'] = nuGetBreadcrumb(nuF('sfo_breadcrumb'), $data['objects'], $hashData);
	$J['formats']    = json_encode(nuTextFormats());
	$J['set_title']  = nuV('set_title');
	$J['php_ids']    = nuPHPJSON();
	$J['report_ids'] = nuReportJSON();
	
	if(substr($formID,0,2) == 'nu'){                     //-- Forms that can use Ace Editor
		$J['schema'] = nuSchemaJSON();
	}else{
		$J['schema'] = '[]';
	}
	
	//$J['object_properties'] = nuObjectJSON();
	
	$J['menu'] = buildMenu(nuV('nu_access_level_id'),$hashData);
	$J['nu_user_id'] = nuV('nu_user_id');
	$J['new_notifications'] = '-1';
	$J['new_messages'] = '-1';
	
	$display_play_buttons = $J['record_id']=='-1' || nuF('sfo_play_buttons')=='0' ? false : true;
	
	if($display_play_buttons){
		
		$display_condition = nuF('sfo_play_buttons_display_condition');
	
		if(!is_null($display_condition) && $display_condition != ''){
			$sql=nuReplaceHashes($display_condition,$hashData);
			$retval = nuRunQuery($sql);
    		if(nuErrorFound()){$display_play_buttons=false;}
			if(!db_num_rows($retval)>0){
				$display_play_buttons=false;
			} else {
				$row= db_fetch_row($retval);
				if($row[0]!='1'){
					$display_play_buttons=false;	
				}
			}
		}
	}
	
	$J['play_buttons'] = $display_play_buttons ? '1' : '0';
	
	return json_encode($J);
}

function nuPHPPDFBreadcrumb(){                           //-- adds the description of the 

    if (nuV('parent_form_id') == 'nurunreport') {

        $code        = nuGetPdfCode(nuV('parent_record_id'));
        $description = nuGetPdfDescription(nuV('parent_record_id'));
        $js          = "$('#nuBreadCrumb_2').html('$code - $description')";
        nuAddJavascript($js);
        
    } else if (nuV('parent_form_id') == 'nurunphp') {
    
        $code        = nuGetPhpCode(nuV('parent_record_id'));
        $description = nuGetPhpDescription(nuV('parent_record_id'));
        $js          = "$('#nuBreadCrumb_2').html('$code - $description')";
        nuAddJavascript($js);
        
    }
    
}


function nuGetBreadcrumb($sqlBreadcrumb, $recs, $hashData) {

    $bcData = array();

    if (nuV('record_id') == '-1') {
        return 'New Record';
    }
    if (trim($sqlBreadcrumb) != '') {

        foreach ($recs as $rec) {

            if($rec->field != '' ){
                if(isset($rec->value)){
                    $bcData[$rec->field] = $rec->value;
                }
            }
            
        }

        $sql = nuReplaceHashes($sqlBreadcrumb, $hashData);
        $sql = nuReplaceHashes($sql, $bcData);
        if (strtoupper(substr(trim($sql), 0, 6)) == 'SELECT') {

            $t = nuRunQuery($sql);
            if (nuErrorFound()) {
                return;
            }
            $r = db_fetch_row($t);

            return $r[0];
        } else {

            return $sql;                                      //-- no sql just using hash variables
        }
    }
	
}

function nuGetRepositonObjects() {

    $i = nuV('form_id');

    $s = "
        SELECT 
            sob_all_name, 
            sob_all_top, 
            sob_all_left 
        FROM zzzsys_object 
        WHERE sob_zzzsys_form_id = ? AND (sob_all_top > 0 OR sob_all_left > 0) 
        ORDER BY
            sob_all_tab_number, 
            sob_all_column_number, 
            sob_all_order_number 
    ";

    $t = nuRunQuery($s, array($i));
    if (nuErrorFound()) {
        return;
    }

    if ($t->rowCount() == 0) {
        return '';
    }

    $m = '';
	$m .= "";
    while ($r = db_fetch_object($t)) {

        $m .= "
		nuMoveObject('$r->sob_all_name', '$r->sob_all_top', '$r->sob_all_left');
        ";
    }

    $j = "

$(document).ready(function() {

	window.nuDraggableObjects.forEach(function(a) {
	
		if(a[4] > 0 && a[5] > 0){
		
			nuMoveObject(a[1], a[4], a[5]);
			
		}
	
	});

});

";

    return $j;
	
}


function nuGetDraggableObjects() {

    $i = nuV('form_id');
	$o = array();
    $s = "
        SELECT 
            zzzsys_object_id, 
            sob_all_name, 
            sob_all_type, 
			IF(ISNULL(sob_all_top), '0', IF(sob_all_top  = '','0',sob_all_top))   AS thetop, 
			IF(ISNULL(sob_all_left),'0', IF(sob_all_left = '','0',sob_all_left))  AS theleft, 
			sob_all_tab_number
        FROM zzzsys_object 
        WHERE sob_zzzsys_form_id = ? 
        ORDER BY
            sob_all_tab_number, 
            sob_all_column_number, 
            sob_all_order_number 
    ";

    $t = nuRunQuery($s, array($i));
	
    if (nuErrorFound()){return $o;}

    if ($t->rowCount() == 0){return $o;}

    while ($r = db_fetch_object($t)){
		$o[] = array($r->zzzsys_object_id ,$r->sob_all_name ,$r->sob_all_type, $r->sob_all_tab_number, $r->thetop, $r->theleft);
    }

    return $o;
}



function nuGetBrowseForm($hashData) {
    
    $formID                = nuV('form_id');
    $pageNo                = nuV('page_number');
    $J['session']          = nuV('session_id');
    $J['breadcrumb']       = nuV('breadcrumb');
    $J['call_type']        = nuV('call_type');
    $J['edit_browse']      = nuV('edit_browse');
    $J['form_id']          = nuV('form_id');
    $J['nu_user_name']     = nuV('nu_user_name');
	$GLOBALS['nuSum']      = false;
	
    $t                     = nuRunQuery("SELECT * FROM zzzsys_form WHERE zzzsys_form_id = ? ", array(nuV('form_id')));
    if (nuErrorFound()) {
        return;
    }
    $r                     = db_fetch_object($t);

    if ($r->sfo_redirect_form_id == '') {
        $J['open_form_id'] = nuV('form_id');
    } else {                                                                //-- open Form other than default
        $J['open_form_id'] = $r->sfo_redirect_form_id;
    }

    $J['search']           = nuV('search');
    $J['filter']           = nuV('filter');
    $J['form_title']       = nuF('sfo_title');
    $J['set_title']        = nuV('set_title');
    $js                    = nuF('sfo_custom_code_run_javascript');
	$J['form_javascript']  = nuReplaceHashes($js, $hashData);
    
    if($GLOBALS['EXTRAJS'] != ''){
        $J['form_javascript'] = $J['form_javascript'] . "\n\n\n//-- Custom Javascript Added via the PHP function : nuAddJavascript()\n\n\n" . $GLOBALS['EXTRAJS'];
    }

    $beforeBrowse          = nuReplaceHashes(nuF('sfo_custom_code_run_before_browse'), $hashData);
    eval($beforeBrowse);

    $data                  = nuGetBrowseRecords($formID, $pageNo, $hashData);
    $buttons               = nuGetBrowseButtons($formID, $hashData);
    $J['buttons']          = $buttons;
	$w                     = 0;
	
	for($i = 1 ; $i < count($data['objects']) ; $i++){
		$w                 = $w + $data['objects'][$i]->width;
	}
	if($w == 0){                                             //-- show no records if no columns
		    $J['records']  = array();
	}else{
		    $J['records']  = $data['records'];
	}
	
	$min      = 350;
	
	if(nuF('sfo_add_button') == '1'){
		$min = $min + 50;
	}
	if(nuF('sfo_print_button') == '1'){
		$min = $min + 50;
	}

	if($w < $min){                                           //-- minimum length for a Browse Form
	
		$nuObject          = new stdClass;

		$nuObject->title   = '';
		$nuObject->value   = '';
		$nuObject->align   = '';
		$nuObject->format  = '';
		$nuObject->width   = $min - $w ;
		$nuObject->search  = '';

		$data['objects'][] = $nuObject;
	}
		
    $J['sum']              = nuSumColumns($data['sql']);
    $J['objects']          = $data['objects'];
    $J['openform']         = $data['openform'];
    $J['parentform']       = $data['parentform'];
    $J['parentrecord']     = $data['parentrecord'];
    $J['display']          = $data['display'];
    $J['rowCount']         = $data['rowCount'];
    $J['searchColumns']    = $data['searchColumns'];
    $J['filterStrings']    = $data['filterStrings'];
    $J['formats']          = json_encode(nuTextFormats());

	$J['menu'] = buildMenu(nuV('nu_access_level_id'),$hashData);
	$J['nu_user_id'] = nuV('nu_user_id');
	
    return json_encode($J);
}

function nuGetBrowseSum(){
	$GLOBALS['nuSum']      = true;
}

function nuSumColumns($s){

    if($GLOBALS['nuSum']){

		$SQL       = new nuSqlString($s);
		$fstart    = strrpos(strtoupper($s), ' FROM ');
		$fstring   = substr($s, 7, $fstart-7);
		$fields    = explode(',IFNULL(', $fstring);
		
		for($i = 1 ; $i < count($fields) ; $i++){
			$SQL->addField('SUM(IFNULL(' . $fields[$i] . ')');
		}

		$t = nuRunQuery($SQL->SQL);
		$r = db_fetch_row($t);

		return $r;
	}else{
		return array();
	}
   
}

function nuGetEditButtons($f, $hashData) {

    $s         = "SELECT * FROM zzzsys_form WHERE zzzsys_form_id = '$f'";
    $t         = nuRunQuery($s);
    if (nuErrorFound()) {
        return;
    }
    $r         = db_fetch_object($t);
    $nuButtons = array();

    if (nuV('parent_form_id') == 'nurunreport') {

        $code        = nuGetPdfCode(nuV('parent_record_id'));
        $nuButtons[] = nuButtonClass('Print', "nuPrintPDF('$code')", $hashData);
        $nuButtons[] = nuButtonClass('Email', "nuEmailPDF('$code')", $hashData);
        
    } else if (nuV('parent_form_id') == 'nurunphp') {
    
        $code        = nuGetPhpCode(nuV('parent_record_id'));
        $nuButtons[] = nuButtonClass('Run Procedure', "nuRunPHP('$code')", $hashData);
        
    } else {
        $save   = nuButtonTitle('Save', $r->sfo_save_button, $r->sfo_save_title, $r->sfo_save_button_display_condition, $hashData);
        $new    = nuButtonTitle('New', $r->sfo_new_button, $r->sfo_new_title, $r->sfo_new_button_display_condition, $hashData);
        $clone  = nuButtonTitle('Clone', $r->sfo_clone_button, $r->sfo_clone_title, $r->sfo_clone_button_display_condition, $hashData);
        $delete = nuButtonTitle('Delete', $r->sfo_delete_button, $r->sfo_delete_title, $r->sfo_delete_button_display_condition, $hashData);
        $fID    = nuV('form_id');
        $rID    = nuV('record_id');

        if ($save != '' and ($_SESSION['nu_access_'.$f]['save'] != '1' or nuV('nu_user_name') == 'globeadmin') ) {

            $nuButtons[] = nuButtonClass($save, 'nuSaveForm()', $hashData);
        }
        if ($new != '' and ($_SESSION['nu_access_'.$f]['new'] != '1' or nuV('nu_user_name') == 'globeadmin')) {

            $nuButtons[] = nuButtonClass($new, 'nuSaveForm(true, 1)', $hashData);
        }
        if (nuV('call_type') != 'cloneform') {
            if ($clone != '' and ($_SESSION['nu_access_'.$f]['clone'] != '1' or nuV('nu_user_name') == 'globeadmin')) {

                $nuButtons[] = nuButtonClass($clone, "nuCloneForm(this, '$fID', '$rID')", $hashData);
            }
            if ($delete != ''and ($_SESSION['nu_access_'.$f]['delete'] != '1' or nuV('nu_user_name') == 'globeadmin')) {

                $nuButtons[] = nuButtonClass($delete, 'nuDeleteForm()', $hashData);
            }
        }
        $s = "SELECT * FROM zzzsys_form_action WHERE sfa_zzzsys_form_id = '$f' ORDER BY zzzsys_form_action_id";
        $t = nuRunQuery($s);
        if (nuErrorFound()) {
            return;
        }
		$title = '';    //---Title always being set to empty string - Ben
        while ($r = db_fetch_object($t)) {                            //-- loop through any action buttons
           $title = nuButtonTitle($r->sfa_button_title, '1', $r->sfa_button_title, $r->sfa_button_display_condition, $hashData);

            if ($title != '') {
                $nuButtons[] = nuButtonClass($title, str_replace('"','\"',$r->sfa_button_javascript), $hashData);
            }
        }
    }

    return $nuButtons;
}

function nuGetPhpCode($id){

    $t = nuRunQuery("SELECT slp_code FROM zzzsys_php WHERE zzzsys_php_id = ? ", array($id));
    $r = db_fetch_row($t);
    
    return $r[0];

}


function nuGetPdfCode($id){

    $t = nuRunQuery("SELECT sre_code FROM zzzsys_report WHERE zzzsys_report_id = ? ", array($id));
    $r = db_fetch_row($t);
    
    return $r[0];

}

function nuGetPhpDescription($id){

    $t = nuRunQuery("SELECT slp_description FROM zzzsys_php WHERE zzzsys_php_id = ? ", array($id));
    $r = db_fetch_row($t);
    
    return $r[0];

}


function nuGetPdfDescription($id){

    $t = nuRunQuery("SELECT sre_description FROM zzzsys_report WHERE zzzsys_report_id = ? ", array($id));
    $r = db_fetch_row($t);
    
    return $r[0];

}

function nuGetBrowseButtons($f, $hashData) {

    $s        = "SELECT * FROM zzzsys_form WHERE zzzsys_form_id = '$f'";
    $t        = nuRunQuery($s);
    if (nuErrorFound()) {
        return;
    }
    $r         = db_fetch_object($t);
    $nuButtons = array();
    $add       = nuButtonTitle('Add Record', $r->sfo_add_button, $r->sfo_add_title, $r->sfo_add_button_display_condition, $hashData);
    $print     = nuButtonTitle('Print', $r->sfo_print_button, $r->sfo_print_title, $r->sfo_print_button_display_condition, $hashData);

    if ($r->sfo_redirect_form_id != '') {             //-- open Form other than default
        $r->zzzsys_form_id = $r->sfo_redirect_form_id;
    }
    if (nuV('edit_browse') == 'true') {                         //-- open in a new window
        if ($add != '' and ($_SESSION['nu_access_'.$f]['add'] != '1' or nuV('nu_user_name') == 'globeadmin')) {

            $nuButtons[] = nuButtonClass($add, "window.nuControlKey=true;nuOpenForm(nuFORM.parent_form_id, nuFORM.parent_record_id, '$r->zzzsys_form_id', '-1', '$r->sfo_title', nuFORM.filter);window.nuControlKey=false;", $hashData);
        }
    } else {
        if ($add != '' and ($_SESSION['nu_access_'.$f]['add'] != '1' or nuV('nu_user_name') == 'globeadmin')) {

            $nuButtons[] = nuButtonClass($add, "nuOpenForm(nuFORM.parent_form_id, nuFORM.parent_record_id, '$r->zzzsys_form_id', '-1', '$r->sfo_title', nuFORM.filter)", $hashData);
        }
    }
    if ($print != '' and ($_SESSION['nu_access_'.$f]['print'] != '1' or nuV('nu_user_name') == 'globeadmin')) {

        $nuButtons[] = nuButtonClass($print, 'nuRunPrintBrowse()', $hashData);
    }
    return $nuButtons;
}

function nuButtonClass($t, $j, $h) {

    $nuButton        = new stdClass;
    $nuButton->title = $t;
    $nuButton->js    = nuReplaceHashes($j, $h);

    return $nuButton;
}

function nuGetBrowseRecords($f, $p, $hashData) {
    $s                      = "SELECT * FROM zzzsys_form WHERE zzzsys_form_id = '$f'";
    $t                      = nuRunQuery($s);
    if (nuErrorFound()) {
        return;
    }
    $r = db_fetch_object($t);

    $nuBrowse               = new stdClass;

    $nuBrowse->title        = $r->sfo_title;
    $nuBrowse->form_to_open = $r->sfo_redirect_form_id;

    if ($r->sfo_row_height == '0' or $r->sfo_row_height == '') {
        $nuBrowse->row_height = 22;  //-- default
    } else {
        $nuBrowse->row_height = $r->sfo_row_height;
    }

    if ($r->sfo_rows == '0' or $r->sfo_rows == '') {
        $nuBrowse->rows = 25;  //-- default
    } else {
        $nuBrowse->rows = $r->sfo_rows;
    }

    $S = "SELECT * FROM zzzsys_browse WHERE sbr_zzzsys_form_id = ? ORDER BY sbr_order";
    $T = nuRunQuery($S, array($f));
    if (nuErrorFound()) {
        return;
    }
    $nuObject = new stdClass;

    $nuObject->title  = 'PK';
    $nuObject->value  = '';
    $nuObject->align  = '';
    $nuObject->format = '';
    $nuObject->width  = '';
    $nuObject->search = '';

    $OBJ[]            = $nuObject;
    $REC              = array();
    $FORM_TO_OPEN     = array();
    $PARENT_RECORD_ID = array();
    $searchFields     = array();
    $searchColumns    = explode(',', nuV('search_columns'));                     //-- create an array of searchable columns
    $columnCount      = 0;
    while ($R = db_fetch_object($T)) {                                           //-- create columns
        $nuObject         = new stdClass;

        $nuObject->title  = $R->sbr_title;
        $nuObject->value  = $R->sbr_display;
        $nuObject->align  = $R->sbr_align;
        $nuObject->format = $R->sbr_format;
        $nuObject->width  = $R->sbr_width;

        $OBJ[]            = $nuObject;

        if (count($searchColumns) == 1) {
            nuV('search_columns', nuV('search_columns') . ",$columnCount");      //--  create a comma delimited string of searchable columns
            $searchFields[] = $R->sbr_display;
        } else {
            if (in_array($columnCount, $searchColumns)) {
                $searchFields[] = $R->sbr_display;                               //-- searchable column
            }
        }

        $columnCount++;
    }

    if (nuV('edit_browse') == 'true') {
        $hashData = array_merge($hashData, nuGetCurrentData());                  //-- use values on current page for hash data
    }

    $hashedSQL = $r->sfo_sql;
    $SQL       = new nuSqlString($hashedSQL);
    $SQL->removeAllFields();
    $SQL->addField($r->sfo_primary_key);
    $width     = 0;
    
    for ($i = 1; $i < count($OBJ); $i++) {

        if ($OBJ[$i]->format == '') {
            $SQL->addField("IFNULL(".$OBJ[$i]->value.",'')");
        } else {
            $format   = nuTextFormats();
            $sql      = $format[$OBJ[$i]->format]->sql;
            $newField = str_replace('??', $OBJ[$i]->value, $sql);
            $SQL->addField("IFNULL(".$newField.",'')");
        }
        $width = $width + $OBJ[$i]->width;
    }


    if (trim(nuV('search')) != '' or nuV('filter') != '') {
        if ($SQL->getWhere() == '') {
            $searchString = ' WHERE ' . nuBrowseWhereClause($searchFields, nuV('search') . ' ' . nuV('filter'));
        } else {
            $searchString = $SQL->getWhere() . ' AND ' . nuBrowseWhereClause($searchFields, nuV('search') . ' ' . nuV('filter'));
        }
        $SQL->setWhere($searchString);
    }
    if (nuV('sort') != 0) {
        
        $SQL->setOrderBy(' ORDER BY ' . nuGetOrderBy() . ' ' . nuV('descending'));
    }

    $start        = $nuBrowse->rows * ($p - 1);
    $row          = 0;
    $formattedSQL = nuReplaceHashes($SQL->SQL, $hashData);
    $t            = nuRunQuery($formattedSQL);
    
    if (nuErrorFound()) {
        return;
    }

    while ($r = db_fetch_row($t)) {                                                          //-- create rows
        if ($row >= $start) {
            $REC[] = $r;
            $PARENT_RECORD_ID[] = $r[0];
            
            if (nuV('form_id') == 'nurunreport') {                                           //-- open Form which will be used for the criteria of running a Report
                $FORM_TO_OPEN[] = nuGetReportCriteriaForm($r[0]);
            }
            if (nuV('form_id') == 'nurunphp') {                                              //-- open Form which will be used for the criteria of running PHP Code
                $FORM_TO_OPEN[] = nuGetPHPCriteriaForm($r[0]);
            }
            if (nuV('form_id') != 'nurunreport' and nuV('form_id') != 'nurunphp') {

                $S = "SELECT * FROM zzzsys_form WHERE zzzsys_form_id = ? ";
                $T = nuRunQuery($S, array(nuV('form_id')));
                if (nuErrorFound()) {
                    return;
                }
                $R = db_fetch_object($T);

                if ($R->sfo_redirect_form_id != '') {             //-- open Form other than default
                    $R->zzzsys_form_id = $R->sfo_redirect_form_id;
                }

                $FORM_TO_OPEN[] = $R->zzzsys_form_id;                                      //-- open standard Edit Form
            }
        }
        if (count($REC) == $nuBrowse->rows) {
            break;
        }

        $row++;
    }

    $J['sql']           = $formattedSQL;
    $J['objects']       = $OBJ;
    $J['records']       = $REC;
    $J['openform']      = $FORM_TO_OPEN;
    $J['parentform']    = nuV('form_id');
    $J['parentrecord']  = $PARENT_RECORD_ID;
    $J['display']       = $nuBrowse;
    $J['rowCount']      = $t->rowCount();
    $J['searchColumns'] = nuV('search_columns');
    $J['filterStrings'] = nuBrowseWhereClause(array(), nuV('search') . ' ' . nuV('filter'), true);

    return $J;
}


function nuGetOrderBy(){

    $f = nuV('form_id');
    $o = nuV('sort') - 2;
    $s = "SELECT sbr_sort FROM zzzsys_browse WHERE sbr_zzzsys_form_id = '$f' ORDER BY sbr_order LIMIT $o, 1";
    $t = nuRunQuery($s);
    $r = db_fetch_row($t);
    return $r[0];
    
}


function nuGetReportCriteriaForm($i) {

    $t = nuRunQuery("SELECT sre_zzzsys_form_id FROM zzzsys_report WHERE zzzsys_report_id = ? ", array($i));
    if (nuErrorFound()) {
        return;
    }
    $r = db_fetch_row($t);

    return $r[0];
}

function nuGetPHPCriteriaForm($i) {

    $t = nuRunQuery("SELECT slp_zzzsys_form_id FROM zzzsys_php WHERE zzzsys_php_id = ? ", array($i));
    if (nuErrorFound()) {
        return;
    }
    $r = db_fetch_row($t);

    return $r[0];
}

function nuGetCurrentData() {

    $d = nuV('form_data');
    $a = array();
    for ($i = 0; $i < count($d); $i++) {
        $a[$d[$i]['field']] = $d[$i]['value'];
    }
    return $a;
}

function nuBrowseWhereClause($searchFields, $searchString, $returnArray = false) {
    $pos          = -1;
    $start        = -1;
    $phrases      = array();
    $SEARCHES     = array();
    $wordSearches = array();
    $highlight    = array();

    while (true) {
        $pos = strpos($searchString, '"', $pos + 1);                              //-- search for double quotes
        if ($pos === false) {
            break;                                                                            //-- stop searching
        } else {
            if ($start == -1) {
                $start     = $pos;                                                            //-- find start position of phrase
            } else {
                $phrases[] = "$start," . ($pos + 1);                                             //-- add start and end to array
                $start     = -1;
            }
        }
    }

    for ($i = 0; $i < count($phrases); $i++) {

        $p          = explode(',', $phrases[$i]);
        $SEARCHES[] = substr($searchString, $p[0], $p[1] - $p[0]);
    }
    
    for ($i = 0; $i < count($SEARCHES); $i++) {

        $pos = strpos($searchString, '-' . $SEARCHES[$i]);                                      //-- check for a preceeding minus
        if ($pos === false) {
            $task[]       = 'include';
            $searchString = str_replace($SEARCHES[$i], ' ', $searchString);             //-- include phrase
            $highlight[]  = substr($SEARCHES[$i], 1, -1);
        } else {
            $task[]       = 'exclude';
            $searchString       = str_replace('-' . $SEARCHES[$i], ' ', $searchString);                   //-- remove phrase
        }
        $SEARCHES[$i] = ' "%' . substr($SEARCHES[$i], 1, -1) . '%" ';
        
    }

    $wordSearches = explode(' ', $searchString);
    $quo = '"';
    
    for ($i = 0; $i < count($wordSearches); $i++) {

        if (strlen($wordSearches[$i]) > 0) {
            if (substr($wordSearches[$i], 0, 1) == '-' and strlen($wordSearches[$i]) > 1) {       //-- check for a preceeding minus
                $task[]      = 'exclude';
                $SEARCHES[]  = $quo . '%' . addslashes(substr($wordSearches[$i], 1)) . '%' . $quo;      //-- add word to exclude
            } else {
                $task[]      = 'include';
                $SEARCHES[]  = $quo . '%' . addslashes($wordSearches[$i]) . '%' . $quo;                //-- add word to include
                $highlight[] = $wordSearches[$i];
            }
        }
    }
    
    for ($i = 0; $i < count($SEARCHES); $i++) {                                                //-- search for (or exclude) these strings
        $include = array();
        $exclude = array();
        
        for ($SF = 0; $SF < count($searchFields); $SF++) {                                     //-- loop through searchable fields
            if ($task[$i] == 'include') {
                $include[] = $searchFields[$SF] . ' LIKE  ' . $SEARCHES[$i];
            } else {
                $exclude[] = $searchFields[$SF] . ' NOT LIKE  ' . $SEARCHES[$i];
            }
        }
        if (count($include) > 0) {
            $where[] = ' (' . implode(' OR ', $include) . ') ';
        }
        if (count($exclude) > 0) {
            $where[] = ' (' . implode(' AND ', $exclude) . ') ';
        }
    }

    if ($returnArray) {
        return $highlight;
    } else {
        return ' (' . implode(' AND ', $where) . ') ';
    }
}

function nuGetObjectsForOneRecord($parent, $parentID, $recordID, $hashData) {

    if ($parent == 'form') {
        $s = "
            SELECT 
                sfo_table as parent_table, 
                sfo_primary_key as parent_primary_key, 
                sfo_sql as the_sql 
            FROM zzzsys_form 
            WHERE zzzsys_form_id = '$parentID'
        ";
    } else { //-- subform
        $s = "
            SELECT 
                sob_subform_table as parent_table, 
                sob_subform_primary_key as parent_primary_key, 
                sob_subform_sql as the_sql 
            FROM zzzsys_object 
            WHERE zzzsys_object_id = '$parentID'
        ";
        $hashData['SUBFORM_RECORD_ID'] = $recordID;
    }
	
    $t               = nuRunQuery($s);
	
    if (nuErrorFound()) {
        return;
    }
    $f               = db_fetch_object($t);
    $recordArray     = nuGetRecord($f, $recordID);

    $stamp[]         = array('viewed', $f->parent_table, $f->parent_primary_key, $recordID);      //-- record view

	$last_edited     = nuLastEdited($f->parent_table, $f->parent_primary_key, $recordID);         //-- last edited

    nuLogStamp($stamp);

    $t = nuRunQuery("
        SELECT * 
        FROM zzzsys_object 
        WHERE sob_zzzsys_form_id = '$parentID' 
        ORDER BY 
            sob_all_tab_number, 
            sob_all_column_number, 
            sob_all_order_number
    ");
    if (nuErrorFound()) {
        return;
    }
    $o               = '';
    $OBJ[]           = nuBaseObject($o, $recordID, $hashData);  //--create the Primary Key Record
    $OBJ[0]->display = '0';
    $OBJ[0]->field   = 'nuPK';
    $OBJ[0]->f_id    = $parentID;
    $OBJ[0]->type    = 'text';
    $OBJ[0]->width   = '0';
    $OBJ[0]->format  = '';

    if (nuV('call_type') == 'cloneform' and $recordID != '-1') {
        $REC[] = '';
    } else {
        $REC[] = $recordID;
    }
    while ($o = db_fetch_object($t)) {
    
        if ($o->sob_all_type == 'listbox') {
            $OBJ[] = nuGetObjectListbox($recordArray, $o, $recordID, $hashData);
        }
        if ($o->sob_all_type == 'checkbox') {
            $OBJ[] = nuGetObjectCheckbox($recordArray, $o, $recordID, $hashData);
        }
        if ($o->sob_all_type == 'dropdown') {
            $OBJ[] = nuGetObjectDropdown($recordArray, $o, $recordID, $hashData);
        }
        if ($o->sob_all_type == 'textarea') {
            $OBJ[] = nuGetObjectTextarea($recordArray, $o, $recordID, $hashData);
        }
        if ($o->sob_all_type == 'text') {
            $OBJ[] = nuGetObjectText($recordArray, $o, $recordID, $hashData);
        }
        if ($o->sob_all_type == 'html') {
            $OBJ[] = nuGetObjectHtml($o, $recordID, $hashData);
        }
        if ($o->sob_all_type == 'button') {
            $OBJ[] = nuGetObjectButton($o, $recordID, $hashData);
        }
        if ($o->sob_all_type == 'display') {
            $OBJ[] = nuGetObjectDisplay($o, $recordID, $hashData);
        }
        if ($o->sob_all_type == 'iframe') {
            $OBJ[] = nuGetObjectiFrame($o, $hashData);
        }
        if ($o->sob_all_type == 'words') {
            $OBJ[] = nuGetObjectWords($o, $hashData);
        }
        if ($o->sob_all_type == 'subform') {
            $OBJ[] = nuGetObjectSubform($f, $o, $recordID, $hashData);		
        }
        if ($o->sob_all_type == 'browse') {
            $OBJ[] = nuGetObjectBrowse($f, $o, $recordID, $hashData);
        }
        if ($o->sob_all_type == 'lookup') {
            $OBJ[] = nuGetObjectLookup($f, $o, $recordID, $hashData);
        }
        if(isset($OBJ[count($OBJ) - 1]->value)){
            $REC[] = $OBJ[count($OBJ) - 1]->value;
        }else{
            $REC[] = '';
        }
        
    }
    $J['objects'] = $OBJ;
    $J['records'] = $REC;
    $J['edited']  = $last_edited[0];
	
    return $J;
}

function nuGetObjectBrowse($f, $o, $recordID, $hashData) {

    $nuObject          = nuBaseObject($o, $recordID, $hashData);

    $nuObject->form_id = $o->sob_browse_zzzsys_form_id;

    $t                 = nuRunQuery("SELECT * FROM zzzsys_form WHERE zzzsys_form_id = ? ", array($o->sob_browse_zzzsys_form_id));
    if (nuErrorFound()) {
        return;
    }
    $r                 = db_fetch_object($t);

    $nuObject->filter  = nuReplaceHashes($o->sob_browse_filter, $hashData);

    return $nuObject;
}

//====== start of subform============

function nuGetObjectSubform($f, $o, $recordID, $hashData) {
    
    $hashData['TABLE_ID']   = nuTT();
    $nuObject               = nuBaseObject($o, $recordID, $hashData);
    $nuObject->addable      = $o->sob_subform_addable;
    $nuObject->deletable    = $o->sob_subform_deletable;
    $nuObject->title_height = '40';
    $nuObject->row_type     = $o->sob_subform_type;
    $nuObject->row_height   = $o->sob_subform_row_height;

    $data                   = nuGetSubform($nuObject->o_id, $o, $hashData);

    $nuObject->records      = json_encode($data[0][0]);
    $nuObject->objects      = json_encode($data[1]);

    nuRemoveTempTables($hashData);
    
    return $nuObject;
}

function nuGetSubform($subformID, $o, $hashData) {
    
    $sf        = nuSubformPageRecords($subformID, $o, $hashData); //-- gets objects and records

    $records[] = $sf['records'];
    $objects   = $sf['objects'][count($sf['objects']) - 1];     //-- last blank record
    $sf        = array();

    $sf[0]     = $records;
    $sf[1]     = $objects;

    return $sf;
}

function nuSubformPageRecords($subformID, $o, $hashData) {  //-- get subform rows
    
    $count = 0;
    $SF    = array();
    $s = "
        SELECT 
            sob_subform_table       as parent_table, 
            sob_subform_primary_key as parent_primary_key, 
            sob_subform_sql
        FROM zzzsys_object 
        WHERE zzzsys_object_id = ?
    ";
    $t     = nuRunQuery($s, array($subformID));
    if (nuErrorFound()) {
        return;
    }
	
    $f     = db_fetch_object($t);
    $s     = nuReplaceHashes($f->sob_subform_sql, $hashData);

    $t     = nuRunQuery($s);
    if (nuErrorFound()) {
        return;
    }
    $SF['records'] = array();

    while ($r = db_fetch_array($t)) {

        $data            = nuGetObjectsForOneRecord('subform', $subformID, $r[$f->parent_primary_key], $hashData);
        $SF['records'][] = $data['records'];
    }

    $data            = nuGetObjectsForOneRecord('subform', $subformID, '-1', $hashData);  //-- add a blank record
    $SF['objects'][] = $data['objects'];
    $addBlank        = false;

    if ($o->sob_subform_addable == '1' and $o->sob_all_read_only == '0') {     //-- can be added to and is not readonly
    
        $SF['records'][] = $data['records'];
        $addBlank        = true;
        
    }

    if(nuV('call_type') == 'cloneform' and $o->sob_all_clone != '1'){          //-- check if subform should be cloned
    
        $SF['records'] = array();                                              //-- empty records
        
        if($addBlank){
            $SF['records'][] = $data['records'];                               //-- add one record
        }
        
    }

    return $SF;
}

//========= end of subform ==========//

function nuUseDefault($o) {

	if(nuV('call_type') == 'cloneform' and $o->sob_all_clone != '1'){                    //-- clone the record but not this field - use a default instead
		return true;
	}
	if(nuV('parent_form_id') == 'nurunreport' or nuV('parent_form_id') == 'nurunphp'){   //-- criteria screen
		return true;
	}
	return false;
	
}


function nuGetObjectHtml($o, $recordID, $hashData) {

    $nuObject       = nuBaseObject($o, $recordID, $hashData);
    $nuObject->html = nuReplaceHashes($o->sob_html_code, $hashData);

    return $nuObject;
}

function nuGetObjectButton($o, $recordID, $hashData) {

    $nuObject                 = nuBaseObject($o, $recordID, $hashData);
    $nuObject->form_id        = $o->sob_button_zzzsys_form_id;
	$nuObject->filter         = $o->sob_button_browse_filter;
    $s                        = "SELECT * FROM zzzsys_form WHERE zzzsys_form_id = ? ";
    $t                        = nuRunQuery($s, array($o->sob_button_zzzsys_form_id));
    
    if (nuErrorFound()) {
        return;
    }
    
    if(db_num_rows($t) > 0){
        $f                    = db_fetch_object($t);
        $nuObject->form_title = $f->sfo_title;
    }

    $nuObject->record_id      =  nuReplaceHashes($o->sob_button_skip_browse_record_id, $hashData);  //--  $o->sob_button_skip_browse_record_id;

    return $nuObject;
}

function nuGetObjectDisplay($o, $recordID, $hashData) {

    $nuObject        = nuBaseObject($o, $recordID, $hashData);
    $nuObject->value = nuGetDefaultValue($o->sob_display_sql, $hashData);

    return $nuObject;
}

function nuGetObjectLookup($f, $o, $recordID, $hashData) {

    $hashData['TABLE_ID'] = nuTT();
    $nuObject             = nuBaseObject($o, $recordID, $hashData);
    if ($recordID == '-1' or nuUseDefault($o)) {
        $recordID = nuGetDefaultValue($o->sob_all_default_value_sql, $hashData);
		$values   = nuGetLookupValues($f, $o, $recordID, $hashData,1);
    } else {
		$values   = nuGetLookupValues($f, $o, $recordID, $hashData,0);   
	}
	$nuObject->value   = $values; 
    $nuObject->c_width = $o->sob_lookup_code_width;
	$nuObject->d_width = $o->sob_lookup_description_width;
    
	if ($nuObject->display != '1') {
		$nuObject->c_width = '0';
		$nuObject->d_width = '0';
	}
    
    $nuObject->width        = $o->sob_lookup_description_width + $o->sob_lookup_code_width + 24;
    $nuObject->form         = $o->sob_lookup_zzzsys_form_id;
    $nuObject->autocomplete = $o->sob_lookup_autocomplete;

    nuRemoveTempTables($hashData);
    
    return $nuObject;
}

function nuGetObjectTextarea($recordArray, $o, $recordID, $hashData) {

    $nuObject = nuBaseObject($o, $recordID, $hashData);

    if ($recordID == '-1' or nuUseDefault($o)) {
        $nuObject->value = nuGetDefaultValue($o->sob_all_default_value_sql, $hashData);
    } else {
        if($recordArray != "" and array_key_exists($o->sob_all_name, $recordArray)) {
            $nuObject->value = $recordArray[$o->sob_all_name];
        }
    }
    return $nuObject;
}

function nuGetObjectListbox($recordArray, $o, $recordID, $hashData) {

    $nuObject = nuBaseObject($o, $recordID, $hashData);

    if ($recordID == '-1' or nuUseDefault($o)) {
        $nuObject->value = nuGetDefaultValue($o->sob_all_default_value_sql, $hashData);
    } else {
        if($recordArray != "" and array_key_exists($o->sob_all_name, $recordArray)) {
            $nuObject->value = $recordArray[$o->sob_all_name];
        }
    }

    $nuObject->list = nuEncodeList(nuReplaceHashes($o->sob_listbox_sql, $hashData));

    return $nuObject;
}

function nuGetObjectDropdown($recordArray, $o, $recordID, $hashData) {

    $nuObject = nuBaseObject($o, $recordID, $hashData);

    if ($recordID == '-1' or nuUseDefault($o)) {
        $nuObject->value = nuGetDefaultValue($o->sob_all_default_value_sql, $hashData);
    } else {
        if($recordArray != "" and array_key_exists($o->sob_all_name, $recordArray)) {
            $nuObject->value = $recordArray[$o->sob_all_name];
        }
    }

    $nuObject->list = nuEncodeList(nuReplaceHashes($o->sob_dropdown_sql, $hashData));

    return $nuObject;
}

function nuGetObjectCheckbox($recordArray, $o, $recordID, $hashData) {

    $nuObject = nuBaseObject($o, $recordID, $hashData);

    if ($recordID == '-1' or nuUseDefault($o)) {
        $nuObject->value = nuGetDefaultValue($o->sob_all_default_value_sql, $hashData);
    } else {
        if($recordArray != "" and array_key_exists($o->sob_all_name, $recordArray)) {
            $nuObject->value = $recordArray[$o->sob_all_name];
        }
    }

    return $nuObject;
}

function nuGetObjectText($recordArray, $o, $recordID, $hashData) {
    $nuObject = nuBaseObject($o, $recordID, $hashData);
    
    $format   = nuTextFormats();

    if ($recordID == '-1' or nuUseDefault($o)) {
        $nuObject->value = nuGetDefaultValue($o->sob_all_default_value_sql, $hashData);
    } else {
        if($recordArray != "" and array_key_exists($o->sob_all_name, $recordArray)) {
            $nuObject->value = $recordArray[$o->sob_all_name];
        }
    }

    $nuObject->value     = nuFormatValue($nuObject->value, $o->sob_text_format);
    $nuObject->format    = $o->sob_text_format;
    $nuObject->text_type = $o->sob_text_type;

    if ($nuObject->format == null) {
        $nuObject->format = '';
    }
    
    if($o->sob_text_format == ''){
        $nuObject->is_date = false;
    }else{
        $nuObject->is_date = $format[$o->sob_text_format]->type == 'date';
    }

    return $nuObject;
}

function nuGetObjectWords($o,$hashData) {

    $nuObject = nuBaseObject($o, '', $hashData);

    return $nuObject;
}

function nuGetObjectiFrame($o,$hashData) {

    $nuObject = nuBaseObject($o, '', $hashData);
    
    if($o->sob_iframe_zzzsys_php_id == ''){                //-- check for php first
    
        $t                  = nuRunQuery("SELECT sre_code FROM zzzsys_report WHERE zzzsys_report_id = '$o->sob_iframe_zzzsys_report_id'");
        $r                  = db_fetch_row($t);

        $nuObject->src_code = $r[0];
        $nuObject->src      = 'pdf';
        
    }else{
    
        $t                  = nuRunQuery("SELECT slp_code FROM zzzsys_php WHERE zzzsys_php_id = '$o->sob_iframe_zzzsys_php_id'");
        $r                  = db_fetch_row($t);

        $nuObject->src_code = $r[0];
        $nuObject->src      = 'php';
        
    }

    return $nuObject;
}

function nuBaseObject($o, $recordID, $hashData) {

    $nuObject                = new stdClass;

    if ($o == '') {
        return $nuObject;
    }

	if($o->sob_all_title===NULL){                                             //-- Stop titles displaying NULL - added by OldShatterhand77 2014-02-08
		$nuObject->title     = '';
	}else{
		$nuObject->title     = $o->sob_all_title;
	}
	
    $nuObject->o_id          = $o->zzzsys_object_id;
    $nuObject->r_id          = $recordID;
    $nuObject->f_id          = $o->sob_zzzsys_form_id;
    $nuObject->type          = $o->sob_all_type;
    $nuObject->field         = $o->sob_all_name;
    $nuObject->column        = $o->sob_all_column_number;
    $nuObject->order         = $o->sob_all_order_number;
    $nuObject->tab           = $o->sob_all_tab_number;
    $nuObject->tab_title     = $o->sob_all_tab_title;
    $nuObject->top           = $o->sob_all_top;
    $nuObject->left          = $o->sob_all_left;
    $nuObject->height        = $o->sob_all_height;
    $nuObject->no_blanks     = $o->sob_all_no_blanks;
    $nuObject->read_only     = $o->sob_all_read_only;
    $nuObject->no_duplicates = $o->sob_all_no_duplicates;
    $nuObject->align         = $o->sob_all_align;
    $nuObject->display       = nuDisplayObject($o,$hashData);
    $nuObject->events        = nuGetEvents($o->zzzsys_object_id, $hashData);

    if ($nuObject->display == '1') {
        $nuObject->width = $o->sob_all_width;
    } else {
        $nuObject->width = '0';
        $nuObject->title = '';
    }
    return $nuObject;
}

function nuGetEvents($i, $hashData) {

    $E = array();
    $t = nuRunQuery("SELECT * FROM zzzsys_event WHERE sev_zzzsys_object_id = ?", array($i));
    if (nuErrorFound()) {
        return;
    }

    while ($r = db_fetch_object($t)) {

        $E['event'][] = $r->sev_name;
        $E['js'][]    = nuReplaceHashes($r->sev_javascript, $hashData);
    }

    return json_encode($E);
}

function nuDisplayObject($o,$hashData) {

    $sql = nuReplaceHashes($o->sob_all_display_condition,$hashData);

    if ($sql == '') {
        return '1';
    } else {
        $t = nuRunQuery($sql);
        if (nuErrorFound()) {
            return;
        }
        $r = db_fetch_row($t);
        return $r[0];
    }
}

function nuGetDefaultValue($sql, $hashData) {
    if ($sql == '') {
        return '';
    }
  
    $nn          = nuNextNumberTables();                             //-- tables with auto-increment for getting a next number
    
    for($i = 0 ; $i < count($nn) ; $i++){
        
        $hash    = '#' . $nn[$i] . '#';

        if (strrpos($sql, $hash) === false){
        }else{                                                       //-- used in this sql statement
            $sql = "SELECT '".str_replace($hash, nuNextNumber($nn[$i]), $sql)."'";
        }
        
    }
	
	$sql         = nuReplaceHashes($sql, $hashData);

    $t           = nuRunQuery($sql);
    if (nuErrorFound()) {
        return;
    }
    $r           = db_fetch_row($t);

    return $r[0];

}


function nuGetLookupValues($f, $o, $recordID, $hashData, $default){

    //--get value to lookup eg. customer_id
	if($default) {
		$fieldValue = $recordID;
	} else {
        if(in_array($o->sob_all_name, db_columns($f->parent_table))){
            $s          = "SELECT `$o->sob_all_name` FROM `$f->parent_table` WHERE `$f->parent_primary_key` = '$recordID'";
            $s          = nuReplaceHashes($s, $hashData);
            $t          = nuRunQuery($s);

            if (nuErrorFound()) {
                return;
            }
            $r          = db_fetch_row($t);
            $fieldValue = $r[0];
        }else{
            $fieldValue = '';
        }
	}
    
    //-- get id of browse form to look in
    $luForm = $o->sob_lookup_zzzsys_form_id;
    $t      = nuRunQuery("SELECT * FROM zzzsys_form WHERE `zzzsys_form_id` = '$luForm'");

    if (nuErrorFound()) {
        return;
    }

    $r     					= db_fetch_object($t);
    $r->sfo_custom_code_run_before_browse 	= nuGetSafePHP('sfo_custom_code_run_before_browse', $r->zzzsys_form_id, $r->sfo_custom_code_run_before_browse);
    $bb    					= nuReplaceHashes($r->sfo_custom_code_run_before_browse, $hashData);
    eval($bb);

	$SQL   = new nuSqlString($r->sfo_sql);

    $s = "
        SELECT 
            $o->sob_lookup_id_field, 
            $o->sob_lookup_code_field, 
            $o->sob_lookup_description_field 
            $SQL->from 
        WHERE $r->sfo_primary_key = '$fieldValue'
    ";

    $s     = nuReplaceHashes($s, $hashData);

    $t     = nuRunQuery($s);
    if (nuErrorFound()) {
        return;
    }
    $r     = db_fetch_row($t);
    if ($r[0] == '') {
        $r[0] = '';
        $r[1] = '';
        $r[2] = '';
    }

    return json_encode($r);
}

function nuGetRecord($f, $r) {

    $s = "SELECT * FROM `$f->parent_table` WHERE `$f->parent_primary_key` = ?";
    $t = nuRunQuery($s, array($r));
    if (nuErrorFound()) {
        return;
    }
    $a = db_fetch_array($t);
    return $a;
}

function nuFormatValue($value, $formatNumber) {

    if ($formatNumber == '') {
        return $value;
    }   //-- no format
    if ($value == '') {
        return $value;
    }   //-- no format
    if (substr($value, 0, 10) == '0000-00-00') {
        return '';
    }   //-- null date
    
    $format = nuTextFormats();
    $sql    = $format[$formatNumber]->sql;
    $sql    = 'SELECT ' . str_replace('??', "'" . $value . "'", $sql);
    $t      = nuRunQuery($sql);
    if (nuErrorFound()) {
        return;
    }
    $r      = db_fetch_row($t);
    $v      = $r[0];

    if ($format[$formatNumber]->type == 'number') {
        $v = str_replace(',', 'sep', $v);
        $v = str_replace('.', 'dec', $v);
        $v = str_replace('sep', $format[$formatNumber]->separator, $v);
        $v = str_replace('dec', $format[$formatNumber]->decimal, $v);
    }
    return $v;
}

function nuEncodeList($sql) {

    $a = array();

    if (substr(strtoupper(trim($sql)), 0, 6) == 'SELECT') {                      //-- sql statement
        $t = nuRunQuery($sql);
        if (nuErrorFound()) {
            return;
        }

        while ($r = db_fetch_row($t)) {
            $a[] = $r;
        }
    } else {                                                                     //-- comma delimited string
        $t = explode('|', $sql);

        for ($i = 0; $i < count($t); $i++) {

            $r    = array();
            $r[0] = $t[$i];
            $r[1] = $t[$i + 1];
            $a[]  = $r;
            $i++;
        }
    }

    return json_encode($a);
}


function nuButtonTitle($name, $show, $title, $sql, $hash = array()){

    if ($show != '1') {
        return '';
    }

    if ($sql == '') {
        if($title != '') {                  //-- 28/01/2014 - 2:55PM - Added code to return title if one existed - Ken
            return $title;
        } else {
            return $name;
        }

    }
    $sql = nuReplaceHashes($sql, $hash);
    $t   = nuRunQuery($sql);
    if (nuErrorFound()) {
        return;
    }
    $r   = db_fetch_row($t);

    if ($r[0] != '1') {
        return '';
    }

    if($title != '') {                      //-- 28/01/2014 - 2:55PM - Added code to return title if one existed - Ken
        return $title;
    } else {
        return $name;
    }

}

function nuRemoveTempTables($hashData) {

    $t = $hashData['TABLE_ID'];
    $a = 'abcdefghijklmnopqrstuvwxyz';

    nuRunQuery('DROP TABLE IF EXISTS ' . $t);
    if (nuErrorFound()) {
        return;
    }

    for ($i = 0; $i < 26; $i++) {
        nuRunQuery('DROP TABLE IF EXISTS ' . $t . $a[$i]);
        if (nuErrorFound()) {
            return;
        }
    }
}


function nuAddJavascript($js){
    $GLOBALS['EXTRAJS'] = $GLOBALS['EXTRAJS'] . "\n\n" . $js;
}

function nuSubformArray($sf, $all = true){

	if($all){
		$a = $GLOBALS['hashData'][$sf];
	}else{
		$a = $GLOBALS['hashData'][$sf.'_save'];
	}
	
	return $a;
}


function nuGetJSONSubform($s){

	$h = $GLOBALS['hashData'];

    for($i = 0 ; $i < count($h['form_data']['data']) ; $i++){
        
        if($h['form_data']['data'][$i]['subform'] == $s){
            return $h['form_data']['data'][$i]['json'];
        }
        
    }
	
	return '[]';
    
}

function nuCheckEdit(){

	$t = nuRunQuery("SELECT sfo_table, sfo_primary_key FROM zzzsys_form WHERE zzzsys_form_id = ? ", array(nuV('form_id')));
	$r = db_fetch_object($t);
	
	if($r->sfo_table == '#TABLE_ID#'){return '';}

	$c = nuLastEdited($r->sfo_table, $r->sfo_primary_key, nuV('record_id'));

	if(nuV('last_edit') == $c[0]){return '';}
	
	return $c[1];

}

function nuLastEdited($f, $p, $r){

	$t = nuRunQuery("SELECT * FROM $f WHERE $p = ? ", array($r));
	$r = db_fetch_array($t);
	
	$a = $r[$f . '_log_changed_at'];
	$b = $r[$f . '_log_changed_by'];
	
	$n = nuGetUserName($b);
	
	return array($a, $n);
	
}

function nuGetUserName($id){

	if($id == 'globeadmin'){return $id;}
	
	$t = nuRunQuery("SELECT sus_login_name FROM zzzsys_user WHERE zzzsys_user_id = ? ", array($id));
	
	$r = db_fetch_row($t);
	
	return $r[0];

}


function nuSchemaJSON(){

    $tables = array();
	$db = $_SESSION['DBName'];
    
//===================TABLES AND FIELDS=========================================
    $s = "
    
	SELECT TABLE_NAME
	FROM INFORMATION_SCHEMA.TABLES
	WHERE TABLE_SCHEMA = '$db'
	AND TABLE_NAME NOT LIKE 'zzzsys_%'
	AND TABLE_TYPE != 'VIEW'
	GROUP BY TABLE_NAME
    
    ";
    $t = nuRunQuery($s);
    
    while($r = db_fetch_object($t)){

		$S = "
        
		SELECT COLUMN_NAME
		FROM INFORMATION_SCHEMA.COLUMNS
		WHERE TABLE_SCHEMA = '$db'
		AND TABLE_NAME = '$r->TABLE_NAME'
		GROUP BY COLUMN_NAME
        
        ";
    
        
        $T       = nuRunQuery($S);
    
        $fields  = array();
    
        while($R = db_fetch_object($T)){
        
            $fields[] = $R->COLUMN_NAME;
        
    
        }
    
    
        $tables[] = nuAddTableObject($r->TABLE_NAME, $fields);
    
    }

    
//===================FORMS AND OBJECTS=========================================
    $s = "
    
		SELECT CONCAT('FORM: ',sfo_name) AS t, sfo_name FROM zzzsys_form
		INNER JOIN zzzsys_object ON zzzsys_form_id = sob_zzzsys_form_id
		WHERE zzzsys_form_id NOT LIKE 'nu%'
		GROUP BY sfo_name
    
    ";

    $t = nuRunQuery($s);
    
    while($r = db_fetch_object($t)){
    
        $S = "
        
            SELECT CONCAT('#', sob_all_name, '#') as t
            FROM zzzsys_form
			INNER JOIN zzzsys_object ON zzzsys_form_id = sob_zzzsys_form_id
            WHERE sfo_name = '$r->sfo_name'
            GROUP BY sob_all_name
        
        ";
    
        
        $T       = nuRunQuery($S);
    
        $fields  = array();
    
        while($R = db_fetch_object($T)){
        
            $fields[] = $R->t;
        
    
        }
    
    
        $tables[] = nuAddTableObject($r->t, $fields);
    
    }

    return json_encode($tables);
    
    
}


function nuObjectJSON(){

	if(nuV('nu_user_name') != 'globeadmin'){
		return '[]';
	}

    $j['object'] = array();
    $j['form']   = array();
    $j['report'] = array();
    $j['php']    = array();
	$i           = nuV('form_id');
    
    $s = "	SELECT * 
			FROM zzzsys_object 
			WHERE sob_zzzsys_form_id = '$i'
		";
    $t           = nuRunQuery($s);
    
    while($r = db_fetch_object($t)){
		$j['object'][] = $r;
    }

   $s = "	SELECT 
			zzzsys_form_id AS id,
			sfo_name AS code,
			sfo_title AS description
			FROM zzzsys_form
			WHERE zzzsys_form_id NOT LIKE 'nu%'
			ORDER BY sfo_name
		";
    $t = nuRunQuery($s);
    
    while($r = db_fetch_object($t)){
		$j['form'][] = $r;
    }


   $s = "	SELECT 
			zzzsys_report_id AS id,
			sre_code AS code,
			sre_description AS description
			FROM zzzsys_report
			WHERE zzzsys_report_id NOT LIKE 'nu%'
			ORDER BY sre_code
		";
    $t = nuRunQuery($s);
    
    while($r = db_fetch_object($t)){
		$j['report'][] = $r;
    }


   $s = "	SELECT 
			zzzsys_php_id AS id,
			slp_code AS code,
			slp_description AS description
			FROM zzzsys_php
			WHERE zzzsys_php_id NOT LIKE 'nu%'
			ORDER BY slp_code
		";
    $t = nuRunQuery($s);
    
    while($r = db_fetch_object($t)){
		$j['php'][] = $r;
    }


    return json_encode($j);
    
    
}

function nuAddTableObject($t, $f){

    $c         = new stdClass;
    
    $c->table  = $t;
    $c->fields = $f;
    
    return $c;
    
}

function nuPHPJSON(){
	
	$s = "SELECT * FROM zzzsys_php";
	$t = nuRunQuery($s);
	$a = array();
	
	while($r = db_fetch_object($t)){
		
		$c               = new stdClass;
		$c->record       = $r->zzzsys_php_id;
		$c->form         = $r->slp_zzzsys_form_id;
		
		$a[$r->slp_code] = $c;
		
	}
	
    return $a;
    return json_encode($a);
	
}


function nuReportJSON(){
	
	$s = "SELECT * FROM zzzsys_report";
	$t = nuRunQuery($s);
	$a = array();
	
	while($r = db_fetch_object($t)){
		
		$c               = new stdClass;
		$c->record       = $r->zzzsys_report_id;
		$c->form         = $r->sre_zzzsys_form_id;
		
		$a[$r->sre_code] = $c;
		
	}
	
    return $a;
    return json_encode($a);
	
}


?>
