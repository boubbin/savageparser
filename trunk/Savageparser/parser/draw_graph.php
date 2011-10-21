<?php
	require_once('/usr/share/jpgraph/jpgraph.php');
	require_once('/usr/share/jpgraph/jpgraph_line.php');
	require_once('mysql_functions.php');
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
	mysql_close();
	// Create the graph. These two calls are always required
	$graph = new Graph(1200,500);
	$graph->SetScale("textlin");
	$graph->img->SetMargin(50,50,50,50);
	$graph->xaxis->SetFont(FF_FONT1,FS_NORMAL);
	$graph->xaxis->SetTextLabelInterval(1);
	//$graph->xaxis->Hide();
	$graph->title->Set("Ladder SF for player $playername");
	$graph->xaxis->title->Set("Time"); 
	$graph->yaxis->title->Set("Skill Factor");
	$graph->legend->Pos(0.01,0.10,"right","center");
	//$graph->SetShadow();
	// Create the linear plot
	$lineplot1=new LinePlot($y, $x);
	$lineplot1->SetLegend("Average SF");
	$lineplot1->SetColor("blue");
	$lineplot1->SetWeight(3);
	
	// Add the plot to the graph
	$graph->Add($lineplot1);
	// Display the graph
	$graph->Stroke();
?>