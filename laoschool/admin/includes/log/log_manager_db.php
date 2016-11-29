<?php
include("../../config.php");

ensure_permission('log');
ensure_role('mod,sadmin,admin');

$page = $_REQUEST['page'];
$limit = $_REQUEST['limit'];

if( $limit =='') $limit = 20;
if($page=='') $page = 1;
$pages = ($page-1) * $limit;

$filter_to_dt = $_REQUEST['filter_to_dt'];
$filter_from_dt = $_REQUEST['filter_from_dt'];
$filter_sso_id = $_REQUEST['filter_sso_id'];
$filter_type = $_REQUEST['filter_type'];

$requestparam .= "";
foreach($_REQUEST as $key=>$value){
	$requestparam .= "$key=$value&";
}
$requestparam = substr($requestparam,0,-1);

for($i=5;$i<=51;$i=$i*2){
	if($i==$limit) $sss = "selected";
	else $sss = "";
	$slt .= "<option $sss value='$i'>$i</option> \n";
}

$auth_key = $_SESSION[$config_session]['auth_key'];

$headers = array();
$headers[] = "auth_key: $auth_key";
$headers[] = "api_key: TEST_API_KEY";

$userdata = callapi($headers,'','','api/users/myprofile');
$userdatas = explode("\n",$userdata['output']);
$myprofile = json_decode(end($userdatas));


if($filter_to_dt!='') $gets['filter_to_dt'] = $filter_to_dt;
if($filter_from_dt!='') $gets['filter_from_dt'] = $filter_from_dt;
if($filter_sso_id!='') $gets['filter_sso_id'] = $filter_sso_id;
if($filter_type!='') $gets['filter_type'] = $filter_type;
$gets['from_row'] = $pages;
$gets['max_result'] = $limit;

$logapi = callapi($headers,'',$gets,'api/logs');
//print_r($logapi);
$logdatas = explode("\n",$logapi['output']);
$logdata = json_decode(end($logdatas));
//print_r($logdata);

echo<<<eot

<div class="dataTables_wrapper form-inline">
	<div class="row-fluid">
		<div class="span6">
			<div class="limit">
				<label>
					<select class="input-mini" name="limit">
						$slt
					</select> $lang_recordperpage
					<span id='limitloadtext'></span>
				</label>
			</div>
		</div>
	</div>
	
	<table class="table table-bordered table-hover dataTable">
		<thead> 
			<tr> 
				<th>$lang_ssoid</th>
				<th>$lang_role</th>
				<th>$lang_reqdate</th>
				<th>$lang_acttype</th>
				<th>$lang_content</th>
			</tr>
		</thead> 
eot;
	echo "<tbody>";
		foreach($logdata->messageObject->list as $lists){
			$c = nl2br(preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
				return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
			}, $lists->content));

			echo<<<eot
			<tr id='row$row[id]'>
				<td><b>$lists->sso_id</b></td>
				<td>$lists->user_role</td>
				<td>$lists->request_dt</td>
				<td>$lists->act_type</td>
				<td>$c</td>
		</tr>
eot;
		}
	echo "</tbody>";
	echo "</table>";
	
	$ceil = ceil($logdata->messageObject->total_count / $limit);
	$next = $page+1; $back = $page-1;
	if($back<=0) $back = 1;
	if($next>$ceil) $next = $ceil;
	$total = $logdata->messageObject->total_count;
echo <<<eot
	<div class="row-fluid">
		<div class="span6">
			<div class="dataTables_info">
				$lang_total <b>$total</b> $lang_recordin <b>$ceil</b> $lang_pages, $lang_youarein <b>$page</b>.
			</div>
		</div>
		<div class="span6">
			<div class="dataTables_paginate paging_bootstrap pagination">
				<ul>
					<li><span id="pageloadtext" class='hide'></span></li>
					<li><a href="javascript:;" class='changepage' page='1'><i class="icon-double-angle-left"></i></a></li>
					<li><a href="javascript:;" class='changepage' page='$back'><i class="icon-angle-left"></i></a></li>
					<li><a href="javascript:;" class='tooltips'><b>$page</b>/$ceil</a></li>
					<li><a href="javascript:;" class='changepage' page='$next'><i class="icon-angle-right"></i></a></li>
					<li><a href="javascript:;" class='changepage' page='$ceil'><i class="icon-double-angle-right"></i></a></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<script language='javascript'>
	$('.changepage').click(function(){
		loadform('includes/log/log_manager_db.php?lang=$lang&$requestparam&page='+$(this).attr('page')+'&limit=$limit','#entry','#pageloadtext');
	});
	
	$("select[name=limit]").change(function() {
		loadform('includes/log/log_manager_db.php?lang=$lang&$requestparam&page=1&limit='+$(this).val(),'#entry','#limitloadtext')
	});
	
	$('.tooltips').tooltip();
</script>
eot;
?>