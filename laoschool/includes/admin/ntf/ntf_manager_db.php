<?php
include("../../../config.php");

$page = $_REQUEST['page'];
$limit = $_REQUEST['limit'];
$entry = $_REQUEST['entry'];
$getby = $_REQUEST['getby'];
if($getby=='') $getby = "filter_to_user_id";

//echo $entry;

if( $limit =='') $limit = 15;
if($page=='') $page = 1;
$pages = ($page-1) * $limit;

$requestparam .= "";
foreach($_REQUEST as $key=>$value){
	$requestparam .= "$key=$value&";
}
$requestparam = substr($requestparam,0,-1);


$auth_key = $_SESSION[$config_session]['auth_key'];
$headers = array();
$headers[] = "auth_key: $auth_key";

$userdata = callapi($headers,'','','api/users/myprofile');
$userdatas = explode("\n",$userdata['output']);
$myprofile = json_decode(end($userdatas));


$gets[$getby] = $myprofile->id;
//$gets['filter_from_user_id'] = $myprofile->id;
$gets['from_row'] = $pages;
$gets['max_result'] = $limit;

$ntfapi = callapi($headers,'',$gets,'api/notifies');
$ntfdatas = explode("\n",$ntfapi['output']);
$ntfdata = json_decode(end($ntfdatas));

echo<<<eot
<div class="row">
  <div class="col-md-12">
	<table class="table table-hover">
		<thead> 
			<tr>
				<th colspan=2>$lang_sender</th>
				<th>$lang_senddate</th>
				<th>$lang_ntftitle</th>
				<th>$lang_ntfcontent</th>
			</tr>
		</thead> 
eot;
	echo "<tbody>";
		foreach($ntfdata->list as $lists){
			if($lists->frm_user_photo=='') $lists->frm_user_photo = "$dirfile/images/no-avatar.jpg";
			$lists->content = nl2br($lists->content);
			$lists->sortcontent = truncate_str($lists->content,40);
			$lists->sortitle = truncate_str($lists->title,35);
			
			$status = ($lists->is_read==0)?"active":"";
			
			$imgcontent = "";
			foreach($lists->notifyImages as $notifyImage){
				$imgcontent .= "<p class='text-center'>
					<img width='70%' src='$notifyImage->file_url' class='img-responsive center-block'>
					<small>$notifyImage->caption</small>
				</p>";
			}
			
			echo<<<eot
			<tr class='$status'>
				<td class="text-center">
					<img title='$lists->from_user_name' src="$lists->frm_user_photo" width='30' class="tooltips img-responsive" />
				</td>
				
				<td class="text-center">$lists->from_user_name</td>
				<td>$lists->sent_dt</td>
				<td>$lists->sortitle</td>
				<td>
					<a href='javascript:;' data-title="<b>$lists->title</b>" data-content="$lists->content<hr>$imgcontent" class='fulltext' data-toggle="modal" data-target="#ntfmodal">
						[$lang_msgread]
					</a>
				</td>
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
	</div>
	<div class="col-md-12">
		<nav>
			<ul class="pager">
				<li><a href='javascript:;' class='changepage$entry' page='$back'><span aria-hidden="true"><span class='glyphicon glyphicon-chevron-left'></span></a></a></li>
				<li class="active"><a href="javascript:;"><b>$page</b>/$ceil</span> <span class="sr-only"></a></li>
				<li><a href="javascript:;" class='changepage$entry' page='$next'><span class='glyphicon glyphicon-chevron-right'></span></a></li>
			</ul>
		</nav>
	</div>
</div>

<script language='javascript'>
	$('.changepage$entry').click(function(){
		loadform('$dirfile/includes/admin/ntf/ntf_manager_db.php?$requestparam&page='+$(this).attr('page')+'&limit=$limit','#$entry','#pageloadtext');
	});

	$('.tooltips').tooltip();
</script>
eot;
?>