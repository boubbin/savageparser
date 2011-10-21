<?php
	include('parser/mysql_functions.php');
	$q = $_GET['q'];
	$q = mysql_escape($q);
        $next = 0;
	$result = get_matching_playernames_for_sring_without_already_added_vips($q);
        echo '<table style="line-height:10px"><tr><td><table style="line-height:10px">';
	while ($row = mysql_fetch_assoc($result)) {
                $next++;
                if ($next == 12) {
                        $next = 1;
                        echo '</table></td><td>';
                        echo '<table style="line-height:10px">';
                } 
		echo "<tr><td><a href=# onclick=\"document.forms['addvip'].username.value = '$row[playername]'; clearsearch(); return false;\")>$row[playername]</td><td> ($row[playerid])</a></td></tr>";
	}
	echo "</td></tr></table>";
?>