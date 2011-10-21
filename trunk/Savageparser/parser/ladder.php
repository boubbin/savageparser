<?php
require('../print_page_functions.php');
require('../echo_mainpage.php');
require('../get_functions.php');
require_once('mysql_functions.php');

function ladder($playername = "boubbin") {;
	$playername = $_GET['playername'];
	$playerid = playername_to_playerid($playername);
	$matches = get_all_matches_of_playerid($playerid);
	$suurin = 0;
	$playtime = (5*60*60);
	$x = array(0);
	$y = array(0);
	$i = 0;
	open_mysql_connection();
	while ($row = mysql_fetch_assoc($matches)) {
		$date = $row['date'];
		$period1 = $date;
		$period2 = $date + (4*7*24*60*60);
		$query = "SELECT * FROM (SELECT SUM(stats.exp)/(SUM(TIME_TO_SEC(stats.duration))/60) as averagesf, SUM(TIME_TO_SEC(stats.duration)) as lol FROM stats, players, matches WHERE stats.playerid = players.playerid AND stats.playerid = '$playerid' AND stats.duration != 0 AND stats.matchid = matches.matchid AND matches.date > '$period1' AND matches.date < '$period2') as sub WHERE sub.lol > $playtime";
		$result = mysql_query($query);
		while ($row = mysql_fetch_assoc($result)) {
			$i++;
			flush();
			$x[$i] = $row['averagesf'];
			$y[$i] = $i;
			if ($row['averagesf'] > $suurin) { $suurin = $row['averagesf']; }
		}
	}
	echo "The biggest ladder SF for $playername is: $suurin";
}
$playername = $_GET['playername'];
ladder($playername);
?>