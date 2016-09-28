<?php
include("../../config.php");
ensure_permission('ntf');
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
$myprofile = json_decode(end($userdatas));


if($filter_to_dt!='') $gets['filter_to_dt'] = $filter_to_dt;
if($filter_from_dt!='') $gets['filter_from_dt'] = $filter_from_dt;
//$gets['filter_from_user_id'] = $myprofile->id;
$gets['from_row'] = $pages;
$gets['max_result'] = $limit;

$ntfapi = callapi($headers,'',$gets,'api/notifies');
//print_r($ntfapi);
$ntfdatas = explode("\n",$ntfapi['output']);
$ntfdata = json_decode(end($ntfdatas));
//print_r($ntfdata->list[0]);
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
				<th>$lang_sender</th>
				<th>$lang_ricever</th>
				<th>$lang_sentdate</th>
				<th>$lang_title</th>
				<th>$lang_content</th>
			</tr>
		</thead> 
eot;
	echo "<tbody>";
		foreach($ntfdata->list as $lists){
			$imgcontent = "";
			foreach($lists->notifyImages as $notifyImage){
				$imgcontent .= "<p align=center><img src='$notifyImage->file_url' width=70% align=center></p><p align=center>$notifyImage->caption</p><hr>";
			}
			
			echo<<<eot
			<tr id='row$row[id]'>
				<td><b><img src='$lists->frm_user_photo' width=20 height=20> $lists->from_user_name</b></td>
				<td>$lists->to_user_name</td>
				<td>$lists->sent_dt</td>
				<td>$lists->title</td>
				<td><a href="javascript:;" class='tooltips fulltext' data-original-title="$lists->content <hr> $imgcontent">[$lang_read]</a></td>
		</tr>
eot;
		}
	echo "</tbody>";
	echo "</table>";
	
	$ceil = ceil($ntfdata->total_count / $limit);
	$next = $page+1; $back = $page-1;
	if($back<=0) $back = 1;
	if($next>$ceil) $next = $ceil;
	
echo <<<eot
	<div class="row-fluid">
		<div class="span6">
			<div class="dataTables_info">
				$lang_total <b>$ntfdata->total_count</b> $lang_recordin <b>$ceil</b> $lang_pages, $lang_youarein <b>$page</b>.
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
		loadform('includes/ntf/ntf_manager_db.php?lang=$lang&$requestparam&page='+$(this).attr('page')+'&limit=$limit','#entry','#pageloadtext');
	});
	
	$("select[name=limit]").change(function() {
		loadform('includes/ntf/ntf_manager_db.php?lang=$lang&$requestparam&page=1&limit='+$(this).val(),'#entry','#limitloadtext')
	});
	
	$('.tooltips').tooltip();
</script>
eot;
?>