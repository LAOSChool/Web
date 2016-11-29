<?php
include("../../config.php");


ensure_permission('usr');
ensure_role('mod,sadmin,admin');

$page = $_REQUEST['page'];
$limit = $_REQUEST['limit'];
$filter_user_role = $_REQUEST['filter_user_role'];
$filter_class_id = $_REQUEST['filter_class_id'];

if( $limit =='') $limit = 20;
if($page=='') $page = 1;
$pages = ($page-1) * $limit;

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

$gets['from_row'] = $pages;
$gets['max_result'] = $limit;
if($filter_user_role=='') $filter_user_role = 'TEACHER';
$gets['filter_user_role'] = $filter_user_role;
$gets['filter_class_id'] = $filter_class_id;

$usrapi = callapi($headers,'',$gets,"/api/users");
$usrdatas = explode("\n",$usrapi['output']);
$usrdata = json_decode($usrdatas[14]);

//print_r($usrdata->list[0]);

echo<<<eot

<div class="dataTables_wrapper form-inline">
	<div class="row-fluid">
		<div class="span6">
			<div class="limit">
				<label>
					<select class="input-mini" name="limit">
						$slt
					</select> records per page
					<span id='limitloadtext'></span>
				</label>
			</div>
		</div>
	</div>
	
	<table class="table table-bordered table-hover dataTable">
		<thead> 
			<tr> 
				<th>SSO ID</th>
				<th>Fullname</th>
				<th>Nickname</th>
				<th>Birthday</th>
				<th>Phone</th>
				<th>Role</th>
				<th>Class</th>
				<th>Edit</th>
eot;

echo "</tr>
		</thead>";
		
	echo "<tbody>";
		foreach($usrdata->list as $lists){
			$class = $lists->classes[0]->title;
			$lists->birthday = reset(explode(' ',$lists->birthday));
			echo "<tr>
				<td>$lists->sso_id</td>
				<td>$lists->fullname</td>
				<td><img src='$lists->photo' style='width:32px;height:32px;'> $lists->gender $lists->nickname</td>
				<td>$lists->birthday</td>
				<td>$lists->phone</td>
				<td>$lists->roles</td>
				<td>$class</td>
				<td align=center width=20><a title='Reset pasword' class='tooltips btn btn-mini btn-info' id='passbutton' qtable='$filter_user_role' qid='$lists->id' href='javascript:void(0)'><i class='icon-key'></a></td>
				<td align=center width=20><a title='Edit' class='tooltips btn btn-mini btn-warning' id='editbutton' qtable='$filter_user_role' qid='$lists->id' href='javascript:void(0)'><i class='icon-edit'></a></td>
			</tr>";
		}
	echo "</tbody>";
	echo "</table>";
	
	//print_r($usrdata);
	$ceil = ceil($usrdata->total_count / $limit);
	$next = $page+1; $back = $page-1;
	if($back<=0) $back = 1;
	if($next>$ceil) $next = $ceil;
	
echo <<<eot
	<div class="row-fluid">
		<div class="span6">
			<div class="dataTables_info">
				Total <b>$usrdata->total_count</b> records in <b>$ceil</b> pages, you are in page <b>$page</b>.
			</div>
		</div>
		<div class="span6">
			<div class="dataTables_paginate paging_bootstrap pagination">
				<ul>
					<li><span id="pageloadtext" class='hide'></span></li>
					<li><a href="javascript:;" class='changepage' page='$back'><i class="icon-chevron-left"></i></a></li>
					<li><a href="javascript:;" class='tooltips' data-original-title='Page $page of $ceil'><b>$page</b>/$ceil</a></li>
					<li><a href="javascript:;" class='changepage' page='$next'><i class="icon-chevron-right"></i></a></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<script language='javascript'>
	$('.changepage').click(function(){
		loadform('includes/usr/usr_manager_db.php?$requestparam&page='+$(this).attr('page')+'&limit=$limit','#entry','#pageloadtext');
	});
	
	$("select[name=limit]").change(function() {
		loadform('includes/usr/usr_manager_db.php?$requestparam&page=1&limit='+$(this).val(),'#entry','#limitloadtext')
	});
	
	$('.tooltips').tooltip();
</script>
eot;
?>