<?php
include("../../config.php");


ensure_permission('cls');
ensure_role('mod,sadmin,admin');

$page = $_REQUEST['page'];
$limit = $_REQUEST['limit'];

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

$clsapi = callapi($headers,'',$gets,"/api/classes");
$clsdatas = explode("\n",$clsapi['output']);
$clsdata = json_decode($clsdatas[14]);

//print_r($clsdata->list[0]);

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
				<th>ຊື່​ໂຮງ​ຮຽນ</th>
				<th>Location</th>
				<th>Level</th>
				<th>Year</th>
				<th>Start date</th>
				<th>End date</th>
				<th>Teacher</th>
				<th>ສະຖາ​ນະ</th>
				<th>Edit</th>
eot;

echo "</tr>
		</thead>";
		
	echo "<tbody>";
		$stsaip = callapi($headers,'',$gets,'api/sys/sys_sts');
		$stsdatas = explode("\n",$stsaip['output']);
		$stsdata = json_decode($stsdatas[14]);
		
		$levaip = callapi($headers,'',$gets,'api/masters/m_clslevel');
		$levdatas = explode("\n",$levaip['output']);
		$levdata = json_decode($levdatas[14]);
		
		foreach($clsdata->list as $lists){
			foreach($stsdata->messageObject->list as $stslists){
				if($stslists->id==$lists->sts){
					$status = $stslists->sval;
					break;
				}
			}
			
			foreach($levdata->messageObject->list as $levlists){
				if($levlists->id==$lists->level){
					$level = $levlists->sval;
					break;
				}
			}
			
			$lists->start_dt = reset(explode(' ',$lists->start_dt));
			$lists->end_dt = reset(explode(' ',$lists->end_dt));
			//$status = ($lists->sts==1)?"<span class='badge badge-success'>Active</span>":"<span class='badge badge-important'>InActive</span>";
			echo "<tr id='row$row[id]'>
				<td>$lists->title</td>
				<td>$lists->location</td>
				<td>$level</td>
				<td>$lists->years</td>
				<td>$lists->start_dt</td>
				<td>$lists->end_dt</td>
				<td>$lists->headTeacherName</td>
				<td>$status</td>
				<td align=center width=20><a title='Edit' class='btn btn-mini btn-warning' id='editbutton' qtable='$table' qid='$lists->id' href='javascript:void(0)'><i class='icon-edit'></a></td>
			</tr>";
		}
	echo "</tbody>";
	echo "</table>";
	
	$ceil = ceil($clsdata->total_count / $limit);
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
		loadform('includes/cls/cls_manager_db.php?$requestparam&page='+$(this).attr('page')+'&limit=$limit','#entry','#pageloadtext');
	});
	
	$("select[name=limit]").change(function() {
		loadform('includes/cls/cls_manager_db.php?$requestparam&page=1&limit='+$(this).val(),'#entry','#limitloadtext')
	});
	
	$('.tooltips').tooltip();
</script>
eot;
?>