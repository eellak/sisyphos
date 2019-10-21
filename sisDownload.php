<?php

proc_nice(8);

ob_start();

require_once("config.php");

$download_dest = 'tmp';
$progress_file = $download_dest.DIRECTORY_SEPARATOR."progress.txt";
$zip_file = $download_dest.DIRECTORY_SEPARATOR."sisyphos.zip";
$lock_file = $download_dest.DIRECTORY_SEPARATOR."lock";
$source_url = 'https://codeload.github.com/nekpap/sisyphos/zip/master';

define('DOWNLOAD_DEST', $download_dest);
define('PROGRESS_FILE', $progress_file);
define('ZIP_FILE', $zip_file);
define('GIT_URL',$source_url);

$done = false;
$max_download_size = 0;
$max_downloaded_size = 0;

if (isset($_GET['key']) && trim($_GET['key'])!=''){
	
	$key = $_GET['key'];
	
	$conn = mysqli_connect($nuConfigDBHost,$nuConfigDBUser,$nuConfigDBPassword, $nuConfigDBName);	
	
	if (mysqli_connect_errno()) {
		die("Connect to DataBase Failed: ".mysqli_connect_error());
	}
	$sql = "SELECT COUNT(*) FROM zzzsys_debug WHERE deb_message='$key'";
	
	$result = mysqli_query($conn, $sql);
	
	if (mysqli_connect_errno()) {
		die ("Query to DataBase Failed: ".mysqli_connect_error());
	}
    
	$row = $result->fetch_array();
	
	$sql = "DELETE FROM zzzsys_debug WHERE deb_message='$key'";
	
	$result = mysqli_query($conn, $sql);
	
	if($row[0]>0){

		$fp = fopen($lock_file, "w");

		if (flock($fp, LOCK_EX)) {

			file_put_contents(PROGRESS_FILE,"[0,0]",LOCK_EX);
			ob_flush();
			flush();

			$targetFile = fopen($zip_file, 'w' );
			
			$ch = curl_init($source_url);

			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt( $ch, CURLOPT_NOPROGRESS, false );
			curl_setopt( $ch, CURLOPT_PROGRESSFUNCTION, 'progressCallback' );
			curl_setopt( $ch, CURLOPT_FILE, $targetFile );
			
			if(curl_exec( $ch )!==FALSE){
				if(curl_error($ch)) {
					file_put_contents(PROGRESS_FILE,"[-1,0]",LOCK_EX);
					ob_flush();
					flush();  				
				} else{
					$done = true;
					$total_size = filesize($zip_file);
					file_put_contents(PROGRESS_FILE,"[$total_size,$total_size]",LOCK_EX);
					ob_flush();
					flush();		
				}
			} else {
 				file_put_contents(PROGRESS_FILE,"[-1,0]",LOCK_EX);
 				ob_flush();
				flush();
			}

			curl_close($ch);
			fclose($targetFile);
			flock($fp, LOCK_UN);
		} else {
			die ("Failed to lock!");
		}
		
		fclose($fp);
		unlink($lock_file);
		
	} else {
		die ("Key not matching!");
	}
	
	$conn->close();
	
} else {
	die ("Key not provided!");
}

ob_flush();
flush();

function progressCallback($resource,$download_size, $downloaded_size, $upload_size, $uploaded_size ){
	
	global $done;
	global $max_download_size;
	global $max_downloaded_size;
	
	if(!$done){
	
		if(!is_null($download_size) && $download_size>$max_download_size){$max_download_size=$download_size;}
		if(!is_null($downloaded_size) && $downloaded_size>$max_downloaded_size){$max_downloaded_size=$downloaded_size;}

		file_put_contents(PROGRESS_FILE,"[$max_downloaded_size,$max_download_size]",LOCK_EX);
		ob_flush();
		flush();
	}
}
?>