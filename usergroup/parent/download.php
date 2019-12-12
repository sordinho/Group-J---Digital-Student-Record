
<?php
$file = basename($_GET['file']);

//Given a string containing the path of a file or directory, this function will return the parent directory's path that is *levels* (2) up from the current directory
$uptwo = dirname(__DIR__, 2);
$uploaddir = $uptwo.'/uploads/';

/*Local host testing - different behavior on server*/
$actual_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$local_host_url = 'http://localhost/Group-J---Digital-Student-Record//usergroup/parent/download.php';

if(substr($actual_url, 0, strlen($local_host_url)) === $local_host_url) {
    $uploaddir = '../../files/';
}
/*End of local host behavior*/

$file = $uploaddir.$file;

if(!file_exists($file)){ // file does not exist
die('file not found');
} else {
header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=$file");
header("Content-Type: application/zip");
header("Content-Transfer-Encoding: binary");

// read the file from disk
readfile($file);
}
