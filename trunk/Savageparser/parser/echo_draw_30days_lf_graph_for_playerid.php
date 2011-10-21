<?php
	require_once('/usr/share/jpgraph/jpgraph.php');
	require_once('/usr/share/jpgraph/jpgraph_line.php');
	require_once('mysql_functions.php');
        require_once('../get_functions.php');
	$playername = $_GET['playername'];
        // $matches = get_number_of_action_player_matches_for_playerid_for_period($_GET['playerid']);
	$result = get_30days_lf_components_for_playerid_verbose($_GET['playerid']);
	$x1 = array(0);
	$y1 = array(0);
	$x2 = array(0);
	$y2 = array(0);
	$cexp  = 1;
	$ctime = 1;
	$cwin  = 0;
	$clos  = 0;
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
		$wins  = $row['winned'];
		$loss = $row['lossed'];
		$cwin += $wins;
		$clos += $loss;
		if ($exp == "0" || $time == "0") { continue; }
		$expmin = $row['exp'] / ($row['time']/60);
		$cexpmin = $cexp / ($ctime/60);
		if ($cwin == "0") {
			if ($wins == "1") { $cwin = 0.75; } else { $cwin = 0.25; }
		} else {
			$cwin = $cwin/($cwin+$clos);
		}
		if ($cwin <= 0.25) { $win = 0.25; }
		if ($cwin >= 0.75) { $win = 0.75; }
		$clf = (1/2)*($cexpmin) + (1/4)*($cexpmin)*(4/5) + (1/4)*($cexpmin) * ($win);
		$lf  = (1/2)*($expmin) + (1/4)*($expmin)*(4/5) + (1/4)*($expmin) * ($win);
		$y1[$i] = round($clf,0);
		$y2[$i] = round($lf,0);

		$x1[$i] = $i;
		$x2[$i] = $i;
		$i++;
	}
	if ($i <= 40) { $label_interval = 1; }
	else if ($i <= 70) { $label_interval = 2; }
	else if ($i <= 140)  { $label_interval = 4; }
	if (empty($y1[1]) && empty($y2[1]) && empty($x1[1]) && empty($x2[1])) { $y1 = array(0,0); $y2 = array(0,0); $x2 = array(0,0); $x1 = array(0,0); }
	// Create the graph. These two calls are always required
	$graph = new Graph(710,350);
	$graph->SetScale("textlin");
	$graph->img->SetMargin(50,50,50,50);
	$graph->xaxis->SetFont(FF_FONT1,FS_NORMAL);
	$graph->xaxis->SetTextLabelInterval(2);
	//$graph->xaxis->Hide();
	$graph->title->Set("Average LF for player $playername\n($date1 - $date2)");
	$graph->xaxis->title->Set("Matches played");
	$graph->yaxis->title->Set("Leadership Factor");
	$graph->legend->Pos(0.01,0.08,"right","center");
	//$graph->SetShadow();
	// Create the linear plot
	$lineplot1=new LinePlot($y1, $x1);
	$lineplot1->SetLegend("Average LF");
	$lineplot1->SetColor("blue");
	$lineplot1->SetWeight(3);
	
	$lineplot2=new LinePlot($y2, $x2);
	$lineplot2->SetLegend("LF");
	$lineplot2->SetColor("red");
	$lineplot2->value->SetColor("darkred");
	$lineplot2->value->Show();
	// Add the plot to the graph
	$graph->Add($lineplot2);
	$graph->Add($lineplot1);
	// Display the graph
	$graph->Stroke();

?>