<?php

	$mres = $dbp->query("select * from `vhs_menu` where lang='$language' order by `order` asc ");
	echo "<li class='active'><a href='$dirfile/$language/'>$lang_home</a></li>";
	while($mres->fetchInto($mrow)){
		echo "<li><a class='scroll' href='#$mrow[tagname]'>$mrow[title]</a></li>\n";
	}
?>