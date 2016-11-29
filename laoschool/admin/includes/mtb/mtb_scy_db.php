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

$gets['from_row'] = $pages;
$gets['max_result'] = $limit;

$scyapi = callapi($headers,'',$gets,"api/schools/years");
$scydatas = explode("\n",$scyapi['output']);
$scydata = json_decode(end($scydatas));

//print_r($scydata->messageObject);
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
				<th>$lang_from</th>
				<th>$lang_to</th>
				<th>$lang_term</th>
				<th>$lang_duration</th>
				<th>$lang_startdate</th>
				<th>$lang_edit</th>
eot;

echo "</tr>
		</thead>";
		
	echo "<tbody>";
		foreach($scydata->messageObject as $lists){
			$lists->start_dt = reset(explode(' ',$lists->start_dt));
			echo "<tr id='row$row[id]'>
				<td>$lists->years</td>
				<td>$lists->from_year</td>
				<td>$lists->to_year</td>
				<td>$lists->term_num</td>
				<td>$lists->term_duration</td>
				<td>$lists->start_dt</td>
				<td align=center width=20><a title='Edit' class='btn btn-mini btn-warning' id='editbutton' qtable='$table' qid='$lists->id' href='javascript:void(0)'><i class='icon-edit'></a></td>
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
		loadform('includes/mtb/mtb_scy_db.php?lang=$lang&$requestparam&page='+$(this).attr('page')+'&limit=$limit','#entry','#pageloadtext');
	});
	
	$("select[name=limit]").change(function() {
		loadform('includes/mtb/mtb_scy_db.php?lang=$lang&$requestparam&page=1&limit='+$(this).val(),'#entry','#limitloadtext')
	});
	
	$('.tooltips').tooltip();
</script>
eot;
?>