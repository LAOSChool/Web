<?php 
$headers = array();
$headers[] = "auth_key: $auth_key";

$userdata = callapi($headers,'','','api/users/myprofile');
$userdatas = explode("\n",$userdata['output']);
$myprofile = json_decode(end($userdatas));

$gets['filter_to_user_id'] = $myprofile->id;

$msgapi = callapi($headers,'',$gets,'api/messages');
$msgdatas = explode("\n",$msgapi['output']);
$msgdata = json_decode(end($msgdatas));
//print_r($msgdata);
$i=0;
?>

	
<div class="news" id="news">
	<div class="container">
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#msgcome" aria-controls="msgcome" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-save"></span> <?php lang('msgcome') ?></a></li>
			<li role="presentation"><a href="#msgsent" aria-controls="msgsent" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-share-alt"></span> <?php lang('msgsent') ?></a></li>
			<li role="presentation"><a href="#sendmsg" aria-controls="sendmsg" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-edit"></span> <?php lang('sendmsg') ?></a></li>
			<li role="presentation"><a href="javascript:;" id="loadtext"></a></li>
		</ul>

		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="msgcome">...</div>
			<div role="tabpanel" class="tab-pane" id="msgsent">...</div>
			<div role="tabpanel" class="tab-pane" id="sendmsg">...</div>
		</div>
		  
	</div>
</div>

<div class="modal fade" id="msgmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php lang('msgdetail') ?></h4>
      </div>
		<div class="modal-body">
			
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal"><?php lang('close') ?></button>
		</div>
    </div>
  </div>
</div>

<script>

loadform('<?php domain(0) ?>/includes/admin/msg/msg_manager_db.php?entry=msgcome&getby=filter_to_user_id&language=<?php echo $language ?>','#msgcome','#loadtext');
loadform('<?php domain(0) ?>/includes/admin/msg/msg_manager_db.php?entry=msgsent&getby=filter_from_user_id&language=<?php echo $language ?>','#msgsent','#loadtext');
loadform('<?php domain(0) ?>/includes/admin/msg/msg_manager_form.php?language=<?php echo $language ?>','#sendmsg','#loadtext');

$('#msgmodal').on('show.bs.modal', function (event) {
	var button = $(event.relatedTarget);
	$('.modal-body').html(button.data('content'));
})

</script>
<div id="contact" class="contact">
</div>