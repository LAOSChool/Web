<?php
session_start();

/*
	Version 3.4
	Built: Nov 01 2012
*/

//======================================================

//=Edit zone=;

$location="localhost";
$userdata="root";
$passdata="khongbiet";
$namedata = "dev_eschool2";

ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_USER_DEPRECATED& ~E_STRICT);

ini_set( 'include_path', ini_get('include_path').":".$_SERVER['DOCUMENT_ROOT'] . "/PEAR/"); 

$link = mysql_connect('localhost', 'root', 'khongbiet');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
echo 'Connected successfully';

require_once "DB.php";

$GLOBALS['db'] = DB::connect("mysql://$userdata:$passdata@$location/$namedata");
if(DB::isError($db)) $dberror = $db->getMessage();
else $db->setFetchMode(DB_FETCHMODE_ASSOC);

phpinfo();
echo $dberror;
$db->query("update message set content = 'ສະບາຍດີ Huynq' where id=166 and id=165");
?>

