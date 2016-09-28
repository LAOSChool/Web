<?php 
	include('../../config.php');

	ensure_permission('ivr');
	//ensure_role('mod,sadmin,admin');

	$id = $_REQUEST['id'];
	
	$res = $db->query("select * from `ivr` where id=$id");
	$res->fetchInto($dt);
	
	$music = "$dirfile/contents/ivr/$dt[content]";
?>

<?php


echo<<<EOT
		 <table class="table table-hover">
			<thead> 
				<tr> 
					<th><b>$dt[name]</b></th>
				</tr> 
			</thead> 
			<tbody> 
			<tr>
				<td align=center>
					<center>
						<audio controls autoplay preload='auto' id='html5player'>
							<source src="$music" type="audio/mpeg">
							Your browser do not support HTML5
						</audio>
						<p><a class='btn btn-mini btn-info' onclick='alert("Right click and choose Save link as...");return false' href="$music"><i class="icon-download-alt"></i> Download this sound</a></p>
					</center>
				</td>
			</tr>
		</table>
EOT;
?>
