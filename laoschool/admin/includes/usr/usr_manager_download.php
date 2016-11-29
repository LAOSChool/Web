<?php
include("../../config.php");

ensure_permission('usr');
ensure_role('mod,sadmin,admin');

$date = date('Y').date('m').date('d');
header("Content-Type: application/force-download"); 
header("Content-Disposition: attachment; filename=user.xlsx");

$auth_key = $_SESSION[$config_session]['auth_key'];

$headers = array();
$headers[] = "auth_key: $auth_key";
$headers[] = "api_key: TEST_API_KEY";

$usrapi = callapi($headers,'','',"/api/users/download_excel");
$usrdatas = explode("\n",$usrapi['output']);
$usrdata = json_decode(end($usrdatas));

echo $usrdatas[15]."\n";
echo $usrdatas[16];
?>