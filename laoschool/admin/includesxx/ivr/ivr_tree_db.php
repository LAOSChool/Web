<?php

include("../../config.php");
ensure_permission('ivr');
//ensure_role('mod,sadmin,admin');

$com_id = $_SESSION[$config_session]['com_id'];
//$companyinfo = $db->getRow("select * from `companies` where id=$com_id");

$ivrcontent = "";

function getivrcontent($parrent){
	global $db, $com_id, $ivrcontent;
	$res = $db->query("select * from `ivr` where parrent = '$parrent' and com_id='$com_id' order by key_pressed asc");
	if($res->numRows()==0) return;
	while($res->fetchInto($row)){
		$addicon = ($row['parrent']==0)?"icon-microphone":"icon-plus-sign";
		$key_pressed = ($row['parrent']==0)?"":"<i>$row[key_pressed].</i>";
		$ivrcontent .=<<<eot
			<ol class="dd-list">
				<li class="dd-item dd3-item">
					<div class="dd-handle dd3-handle addchirld" qid='$row[id]'><i class='$addicon'></i></div>
					<div class="dd3-content ahref editchirld" qid='$row[id]'>$key_pressed $row[name]</div>
eot;
				getivrcontent($row['id']);
		$ivrcontent.="</li></ol>";

	}
}
getivrcontent(0);
echo<<<eot
<div class="dd" id="nestable_list">
	$ivrcontent
</div>


<script language='javascript'>
	$('#nestable_list').nestable({
		handleClass : 'a',
		group: 1
	});
	
	$('.addchirld').click(function(){
		$('.addchirld').removeClass('selected');
		$('.editchirld').removeClass('selected');
		$(this).addClass('selected');
		parrent = $(this).attr('qid');
		loadform('includes/ivr/ivr_tree_form.php?lang=<?php echo $lang ?>&type=add&parrent='+parrent,'#parameter','#loadtext1');
	});
	
	$('.editchirld').click(function(){
		$('.addchirld').removeClass('selected');
		$('.editchirld').removeClass('selected');
		$(this).addClass('selected');
		id = $(this).attr('qid');
		loadform('includes/ivr/ivr_tree_form.php?lang=<?php echo $lang ?>&type=edit&id='+id,'#parameter','#loadtext1');
	});

	$('.tooltips').tooltip();
</script>
eot;
?>