<?php
	include('parser/mysql_functions.php');
	$q = $_GET['q'];
        $id = $_GET['id'];
	$q = mysql_escape($q);
        $next = 0;
	$result = get_matching_playernames_for_sring($q);
        echo '<table style="line-height:10px"><tr><td><table style="line-height:10px">';
	while ($row = mysql_fetch_assoc($result)) {
                $next++;
                if ($next == 12) {
                        $next = 1;
                        echo '</table></td><td>';
                        echo '<table style="line-height:10px">';
                } 
		echo "<tr><td><a href=# onclick=\"document.forms['match_search'].txt$id.value = '$row[playername]'; document.forms['match_search'].playerid$id.value = '$row[playerid]'; match_search_clearsearch($id); return false;\")>$row[playername]</td><td> ($row[playerid])</a></td></tr>";
	}
	echo "</td></tr></table>";
?>