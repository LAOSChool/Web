<?php 
$headers = array();
$headers[] = "auth_key: $auth_key";

$userdata = callapi($headers,'','','api/users/myprofile');
$userdatas = explode("\n",$userdata['output']);
$myprofile = json_decode(end($userdatas));

$gets['filter_to_user_id'] = $myprofile->id;

$ntfapi = callapi($headers,'',$gets,'api/messages');
$ntfdatas = explode("\n",$ntfapi['output']);
$ntfdata = json_decode(end($ntfdatas));
//print_r($ntfdata);
$i=0;
?>

	
<div class="news" id="news">

	<div class="container">
	
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#ntfcome" aria-controls="ntfcome" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-comment"></span> <?php lang('ntf') ?></a></li>
		</ul>

		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="ntfcome">...</div>
		</div>
		  
	</div>
	</div>

<div class="modal fade" id="ntfmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php lang('ntfdetail') ?></h4>
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

loadform('<?php domain(0) ?>/includes/admin/ntf/ntf_manager_db.php?entry=ntfcome&getby=filter_to_user_id&language=<?php echo $language ?>','#ntfcome','#loadtext');
loadform('<?php domain(0) ?>/includes/admin/ntf/ntf_manager_db.php?entry=ntfsent&getby=filter_from_user_id&language=<?php echo $language ?>','#ntfsent','#loadtext');

$('#ntfmodal').on('show.bs.modal', function (event) {
	var button = $(event.relatedTarget);
	$('.modal-body').html(button.data('content'));
	$('.modal-title').html(button.data('title'));
})

</script>
<div id="contact" class="contact">
</div>