<?php
	require_once('/usr/share/jpgraph/jpgraph.php');
	require_once('/usr/share/jpgraph/jpgraph_line.php');
	require_once('mysql_functions.php');
        require_once('../get_functions.php');
	$playername = $_GET['playername'];
        $period = $_GET['period'];
        if (empty($period)&&$period != 0) { $period = 30; }
        if ($period == 7) { $period = time() - (7*24*60*60); }
        if ($period == 15) { $period = time() - (15*24*60*60); }
        if ($period == 30) { $period = get_period(); }
        if ($period == 60) { $period = time() - (60*24*60*60); }
        if ($period == 0) { $period = 0; }
        $matches = get_number_of_action_player_matches_for_playerid_for_period($_GET['playerid']);
	$result = get_sfs_for_playerid_verbose($_GET['playerid'], $period);
	$x1 = array(0);
	$y1 = array(0);
	$x2 = array(0);
	$y2 = array(0);
	$cexp  = 0;
	$ctime = 0;
	$i = 1;
	$date1 = 0;
	$date2 = 0;
	while ($row = mysql_fetch_assoc($result)) {
		if ($date1 == 0) { $date1 = date("d/m/Y", $row['date']); }
		$date2 = date("d/m/Y", $row['date']);
		$exp  = $row['exp'];
		$time = $row['time'];
		$cexp  += $exp;
		$ctime += $time;
		$x1[$i] = $i;
		$x2[$i] = $i;
		$y1[$i] = round($cexp/($ctime/60),0);
		$y2[$i] = round($exp/($time/60),0);
		$i++;
	}
	if ($i <= 40) { $label_interval = 1; }
	else if ($i <= 70) { $label_interval = 2; }
	else if ($i <= 140) { $label_interval = 4; }
	else { $label_interval = 10; }
	if (empty($y1[1]) && empty($y2[1]) && empty($x1[1]) && empty($x2[1])) { $y1 = array(0,0); $y2 = array(0,0); $x2 = array(0,0); $x1 = array(0,0); }
        $label_interval = round($i/21+1,0);
	// Create the graph. These two calls are always required
	$graph = new Graph(710,350);
	$graph->SetScale("textlin");
	$graph->img->SetMargin(50,50,50,50);
	$graph->xaxis->SetFont(FF_FONT1,FS_NORMAL);
	$graph->xaxis->SetTextLabelInterval($label_interval);
	//$graph->xaxis->Hide();
	$graph->title->Set("Average SF for player $playername\n($date1 - $date2)");
	$graph->xaxis->title->Set("Matches played ($matches)");
	$graph->yaxis->title->Set("Skill Factor");
	$graph->legend->Pos(0.01,0.08,"right","center");
	//$graph->SetShadow();
	// Create the linear plot
	$lineplot1=new LinePlot($y1, $x1);
	$lineplot1->SetLegend("Average SF");
	$lineplot1->SetColor("blue");
	$lineplot1->SetWeight(3);
	
	$lineplot2=new LinePlot($y2, $x2);
	$lineplot2->SetLegend("SF");
	$lineplot2->SetColor("red");
	$lineplot2->value->SetColor("darkred");
	$lineplot2->value->Show();
	// Add the plot to the graph
	$graph->Add($lineplot2);
	$graph->Add($lineplot1);
	// Display the graph
	$graph->Stroke();

?>