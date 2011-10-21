<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
echo "<table>";
require('../essentials.php');
$query = "SELECT DISTINCT(unionstats30.playerid) as playerid, playername FROM unionstats30, players WHERE ip >= 1548517376 AND ip <= 1548615679 AND unionstats30.playerid = players.playerid ORDER BY playerid ASC;";
open_mysql_connection();
$result = mysql_query($query);
while ($row = mysql_fetch_assoc($result)) {
    echo "<tr><td><font size=2>$row[playerid]</td><td><font size=2>$row[playername]</td></tr>";
}
echo "</table>";
?>
