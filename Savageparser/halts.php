<?php 
require('essentials.php');
$query = "SELECT ip, COUNT(sub.playerid) as kpl, GROUP_CONCAT(playername) as playername FROM players, (SELECT DISTINCT(playerid) as playerid, ip FROM stats WHERE ip IS NOT NULL) as sub WHERE players.playerid = sub.playerid GROUP BY ip HAVING kpl > 1 ORDER BY playername;";
open_mysql_connection();
$result = mysql_query($query);
mysql_close();
echo "<table><tr><td>Number of alts</td><td>Alts</td></tr>";
echo '<div style="line-height: 1px;">';
while ($row = mysql_fetch_row($result)) {
	$times = $row[1];
	$names = $row[2];
	echo "<tr><td><font size=2>$times</td><td><font size=2>$names</td></tr>";
}
echo "</table></div>";
	
?>
