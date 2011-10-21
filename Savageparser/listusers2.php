<?php
	include('parser/mysql_functions.php');
	$q = $_GET['q'];
	$q = mysql_escape($q);
	$result = get_matching_playernames_for_sring_without_already_added_users($q);
	echo '<table style="line-height:10px">';
	while ($row = mysql_fetch_assoc($result)) {
		$playername = preg_replace("/\[.*\]/i", "", $row['playername']);
		echo "<tr><td><a href=# onclick=\"document.forms['adduser'].new_username.value = '$playername'; document.forms['adduser'].new_playerid.value = '$row[playerid]'; document.forms['adduser'].new_referal.value = MD5(MD5('$playername')); clearsearch2(); return false;\")>$row[playername]</td><td> ($row[playerid])</td></a><tr>";
	}
	echo "</table>";
?>