<?php
include("../../config.php");


ensure_permission('cls');
ensure_role('mod,sadmin,admin');

$page = $_REQUEST['page'];
$limit = $_REQUEST['limit'];
$filter_user_role = $_REQUEST['filter_user_role'];
$filter_class_id = $_REQUEST['filter_class_id'];

if($filter_class_id=='') $filter_class_id = 'available';

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
$gets['filter_user_role'] = $filter_user_role;
$gets['filter_class_id'] = $filter_class_id;

if($filter_class_id=='available') $usrapi = callapi($headers,'',$gets,"api/users/available");
else $usrapi = callapi($headers,'',$gets,"api/users");

$usrdatas = explode("\n",$usrapi['output']);
$usrdata = json_decode($usrdatas[14]);


$headers = array();
$auth_key = $_SESSION[$config_session]['auth_key'];
$headers[] = "auth_key: $auth_key";
$headers[] = "api_key: TEST_API_KEY";

$clsaip = callapi($headers,'',$gets,'api/classes');
$clsdatas = explode("\n",$clsaip['output']);
$clsdata = json_decode($clsdatas[14]);

foreach($clsdata->list as $lists){
	$clsselect .= "<option value='$lists->id'>$lists->title</option>";
}

echo<<<eot
<form method=POST id='managerform' class="form-horizontal" action='includes/cls/cls_setclass_edit.php'>

<div class="dataTables_wrapper form-inline">
	<div class="clearfix">
		<div class="btn-group">
			<div class="limit">
				<label>
					Assign to Class: 
					<select class="input-medium" name="class_id">
						$clsselect
					</select>
					<button type="submit" class="btn btn-info" name="submit"><i class="icon-edit"></i> ອັບ​ເດດ</button>
					<span id="submit"></span>
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
				<th>Class</th>
				<th>Select</th>
eot;

echo "</tr>
		</thead>";
		
	echo "<tbody>";
		//print_r($usrdata->list[0]);
		foreach($usrdata->list as $lists){
			$class = $lists->classes[0]->title;
			echo "<tr>
				<td>$lists->sso_id</td>
				<td>$lists->fullname</td>
				<td><img src='$lists->photo' style='width:32px;height:32px'> $lists->gender $lists->nickname</td>
				<td>$class</td>
				<td><input type=checkbox name='user_id[]' value='$lists->id'></td>
			</tr>";
		}
	echo "</tbody>";
	echo "</table>";
	
	$ceil = ceil($usrdata->total_count / $limit);
	$next = $page+1; $back = $page-1;
	if($back<=0) $back = 1;
	if($next>$ceil) $next = $ceil;
	
echo <<<eot

	
	<div class="row-fluid">
		<div class="span6">
			<div class="dataTables_info">
				Total <b>$rrr</b> records in <b>$ceil</b> pages, you are in page <b>$page</b>.
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
		loadform('includes/cls/cls_setclass_db.php?$requestparam&page='+$(this).attr('page')+'&limit=$limit','#entry','#pageloadtext');
	});
	
	$("select[name=limit]").change(function() {
		loadform('includes/cls/cls_setclass_db.php?$requestparam&page=1&limit='+$(this).val(),'#entry','#limitloadtext')
	});
	
	$('#managerform').ajaxForm({
		target: '#submit', 
		beforeSubmit: function(){
			$('#submit').show().html('<span class="badge badge-warning"><i class="icon-spinner"></i> <?php lang('pleasewait') ?>....</span>'); 
		},
		success: function() { 
			;
		}
	});

	$('.tooltips').tooltip();
</script>
eot;
?>