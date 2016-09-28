<?php
include("../../config.php");

ensure_permission('mtb');
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

$gets['filter_year_id'] = $_REQUEST['filter_year_id'];
$gets['max_result'] = $limit;
$gets['max_result'] = $limit;

$trmapi = callapi($headers,'',$gets,"api/schools/terms");
$trmdatas = explode("\n",$trmapi['output']);
$trmdata = json_decode(end($trmdatas));

if($gets['filter_year_id']=='') die("<span class='label label-important'><i class='icon-mtbo-sign'></i> $lang_pleasefill $lang_year</span>");

//print_r($gets);
//print_r($trmdata->messageObject);
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
				<th>$lang_year</th>
				<th>$lang_term</th>
				<th>$lang_startdate</th>
				<th>$lang_enddate</th>
				<th>$lang_status</th>
				<th>$lang_notice</th>
				<th colspan=2>$lang_action</th>
eot;

echo "</tr>
		</thead>";
		
	echo "<tbody>";
		$yrsaip = callapi($headers,'',$gets,'api/schools/years');
		$yrsdatas = explode("\n",$yrsaip['output']);
		$yrsdata = json_decode(end($yrsdatas));
		//print_r($yrsdata);
		foreach($trmdata->messageObject as $lists){
			$lists->start_dt = reset(explode(' ',$lists->start_dt));
			$lists->end_dt = reset(explode(' ',$lists->end_dt));

			foreach($yrsdata->messageObject as $yrslists){
				if($yrslists->id==$lists->year_id){
					$year = $yrslists->years;
					break;
				}
			}
			
			 
			if($lists->actived==0) $actived_text = $lang_inactive;
			if($lists->actived==1) $actived_text = $lang_active;
			if($lists->actived==2) $actived_text = $lang_pending;
			echo "<tr id='row$row[id]'>
				<td>$year</td>
				<td>$lists->term_val</td>
				<td>$lists->start_dt</td>
				<td>$lists->end_dt</td>
				<td>$actived_text</td>
				<td>$lists->notice</td>
				<td align=center width=20><a title='$lang_edit' class='tooltips btn btn-mini btn-warning' id='editbutton' qtable='$table' qid='$lists->id' href='javascript:void(0)'><i class='icon-edit'></a></td>
				<td align=center width=20><a title='$lang_changestatus' class='tooltips btn btn-mini btn-info' id='statusbutton' qtable='$table' qid='$lists->id' href='javascript:void(0)'><i class='icon-ok'></a></td>
			</tr>";
		}
	echo "</tbody>";
	echo "</table>";
	
	$ceil = ceil($mtbdata->total_count / $limit);
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
		loadform('includes/mtb/mtb_trm_db.php?lang=$lang&$requestparam&page='+$(this).attr('page')+'&limit=$limit','#entry','#pageloadtext');
	});
	
	$("select[name=limit]").change(function() {
		loadform('includes/mtb/mtb_trm_db.php?lang=$lang&$requestparam&page=1&limit='+$(this).val(),'#entry','#limitloadtext')
	});
	
	$('.tooltips').tooltip();
</script>
eot;
?>