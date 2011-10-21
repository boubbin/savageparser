<?
require('parser/mysql_functions.php');
open_mysql_connection();
$query = "SELECT INET_NTOA(ip) FROM stats WHERE playerid = '636533' AND ip IS NOT NULL";
$result = mysql_query($query);
$result = mysql_fetch_row($result);
if (!empty($result)) { echo "I have it now, not gonna tell you tho.. :)"; }
else { echo "No Sharias ip yet!"; }
mysql_close();
