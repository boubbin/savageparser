<?php
	require('../mysql_functions.php');
        require('../../get_functions.php');
	//Report all Errors
	ini_set("display_errors", "1");
	error_reporting(1); 
	 
	//Set content type
	header('content-type: image/jpeg');
	 
	//Store the values of our date in separate variables
	$playername = $_GET['playername'];
        $playername = str_replace("'", "", $playername);
        $playername = str_replace("<", "", $playername);
        $playername = str_replace("-", "", $playername);
        $playername = str_replace("!", "", $playername);
        $playername = str_replace("[", "", $playername);
        $playername = str_replace("`", "", $playername);
	$playerid = playername_to_playerid($playername);
	$result = get_30days_stats_for_player($playerid);
        $teams = get_30days_teams_played_for_playerid($playerid);
        $record = get_30days_winloss_for_playerid($playerid);
        $record = "Record: $record[0]-$record[1]";
        $row = mysql_fetch_row($teams);
        $team1 = $row[0];
        $row = mysql_fetch_row($teams);
        $team2 = $row[0];
	while ($row = mysql_fetch_row($result)) {
		$sf      = round($row[0],2);
		$kills   = "Kills: ".number_format($row[1]);
		$deaths  = "Deaths: ".number_format($row[2]);
		$assists = "Assists : ".number_format($row[3]);
		$bd      = "BD: ". number_format($row[4]);
		$gold 	 = "Gold: ".number_format($row[5]);
		$healed  = "Healed: ".number_format($row[6]);
		$hours	 = "Playtime(h): ".round($row[7],2);
		$souls	 = "Souls: ".number_format($row[8]);
                $team1   = "Human: ".$team1." times";
                $team2   = "Beast: ".$team2." times";
	}
        $adv = "http://savage.juhlamoka.fi";
        $playername .= " (sf $sf)";
	//Load our base image 
	$image = imagecreatefrompng('tausta.png');
	$image_width = imagesx($image);
	 
	//Setup colors and font file 
	$white = imagecolorallocate($image, 255, 255, 255);
        $yellow   = imagecolorallocate($image, 200, 200, 20);
	$black = imagecolorallocate($image, 0, 0, 0);
	$font_path = '/usr/share/fonts/truetype/freefont/FreeSans.ttf';
	 
	imagettftext($image, 13, 0, 40, 40, $yellow, $font_path, $playername);
	imagettftext($image, 11, 0, 40, 60, $white, $font_path, $hours);
	imagettftext($image, 11, 0, 40, 80, $white, $font_path, $record);
	imagettftext($image, 11, 0, 40, 100, $white, $font_path, $team1);
	imagettftext($image, 11, 0, 40, 120, $white, $font_path, $team2);
        imagettftext($image, 10, 0, 50, 140, $white, $font_path, $adv);
	imagejpeg($image, '', 100);
	 
	//Clear up memory;
	imagedestroy($image);

?>
