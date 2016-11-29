<?php
include('../../config.php');
$id = $_REQUEST['id'];
?>

<?php

echo<<<EOT
		 <table class="table table-hover">
			<thead> 
				<tr> 
					<th><b>$id</b></th>
				</tr> 
			</thead> 
			<tbody> 
			<tr>
				<td align=center>
					<center>
						<img src="$id">
					</center>
				</td>
			</tr>
		</table>	
EOT;
?>