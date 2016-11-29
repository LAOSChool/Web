<?php
include("../../config.php");

ensure_permission('msg');
ensure_role('mod,sadmin,admin');

$page = $_REQUEST['page'];
$limit = $_REQUEST['limit'];

if( $limit =='') $limit = 20;
if($page=='') $page = 1;
$pages = ($page-1) * $limit;

$filter_to_dt = $_REQUEST['filter_to_dt'];
$filter_from_dt = $_REQUEST['filter_from_dt'];

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
$myprofile = json_decode($userdatas[14]);


if($filter_to_dt!='') $posts['filter_to_dt'] = $filter_to_dt;
if($filter_from_dt!='') $posts['filter_from_dt'] = $filter_from_dt;
$gets['filter_from_user_id'] = $myprofile->id;
$gets['from_row'] = $pages;
$gets['max_result'] = $limit;

$msgapi = callapi($headers,'',$gets,'api/messages');
//print_r($msgapi);
$msgdatas = explode("\n",$msgapi['output']);
$msgdata = json_decode($msgdatas[14]);
//print_r($msgdata->list[0]);

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
				<th>Sender</th>
				<th>Ricever</th>
				<th>Sent Date</th>
				<th>ຊື່​ໂຮງ​ຮຽນ</th>
				<th>Content</th>
			</tr>
		</thead> 
eot;
	echo "<tbody>";
		foreach($msgdata->list as $lists){
			echo<<<eot
			<tr id='row$row[id]'>
				<td><b><img src='$lists->frm_user_photo' width=20 height=20> $lists->from_user_name</b></td>
				<td>$lists->to_user_name</td>
				<td>$lists->sent_dt</td>
				<td>$lists->title</td>
				<td><a href="javascript:;" class='tooltips fulltext' data-original-title="$lists->content">[Read]</a></td>
		</tr>
eot;
		}
	echo "</tbody>";
	echo "</table>";
	
	$ceil = ceil($msgdata->total_count / $limit);
	$next = $page+1; $back = $page-1;
	if($back<=0) $back = 1;
	if($next>$ceil) $next = $ceil;
	
echo <<<eot
	<div class="row-fluid">
		<div class="span6">
			<div class="dataTables_info">
				$lang_total <b>$rrr</b> $lang_recordin <b>$ceil</b> $lang_pages, $lang_youarein <b>$page</b>.
			</div>
		</div>
		<div class="span6">
			<div class="dataTables_paginate paging_bootstrap pagination">
				<ul>
					<li><span id="pageloadtext" class='hide'></span></li>
					<li><a href="javascript:;" class='changepage' page='$back'><i class="icon-chevron-left"></i></a></li>
					<li><a href="javascript:;" class='tooltips'><b>$page</b>/$ceil</a></li>
					<li><a href="javascript:;" class='changepage' page='$next'><i class="icon-chevron-right"></i></a></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<script language='javascript'>
	$('.changepage').click(function(){
		loadform('includes/msg/msg_manager_db.php?$requestparam&page='+$(this).attr('page')+'&limit=$limit','#entry','#pageloadtext');
	});
	
	$("select[name=limit]").change(function() {
		loadform('includes/msg/msg_manager_db.php?$requestparam&page=1&limit='+$(this).val(),'#entry','#limitloadtext')
	});
	
	$('.tooltips').tooltip();
</script>
eot;
?>