<?php

function get_match_stats_from_stats_file($file) {
        $str = array();
        $file = "/home/boubbino/public_html/savage/stats_files/files/$file";
        $str = file_get_contents($file);
        $str = unserialize($str);
        $str['player_stats'] = unserialize(stripslashes($str['player_stats']));
        $str['commander_stats'] = unserialize(stripslashes($str['commander_stats']));
        $str['team'] = unserialize(stripslashes($str['team']));
        return $str;
}

function is_valid_matchid($matchid) {
        if (ctype_digit($matchid)) { return true; }
        if ($matchid > 1000 && $matchid < 3000000) { return true; }
}

function stats_file_exists_for_matchid($matchid) {
        $file = "/home/boubbino/public_html/savage/stats_files/files/$matchid";
        if (file_exists($file)) { return true; }
        return false;
}

function remove_cookie() {
        global $cookie_stats;
        unlink($cookie_stats);
}

function remove_cookie_error_report() {
	echo "
		<font color=red><h3>ERROR WITH HTTP-REQUEST!</h3><br>
		(this is fatal error, please report this to admin)<br>
		<b>After reporting, you can try again what you were doing</b>
	";
	remove_cookie();
        die();
}


function is_valid_player_stat_info($stats) {
	$d = 1; // quick debug, 1 = on, 0 = off
        if (count($stats) != 17) { return FALSE; }
	list($exp, $dmg, $kills, $assists, $souls, $npc, $healed, $res, $gold, $repair, $bd, $razed, $deaths, $kd, $duration, $sf, $team) = $stats;
	if (!is_numeric($exp)) { if ($d) echo 1; return FALSE; }
	if (!is_numeric($dmg)) { if ($d) echo 2; return FALSE; }
	if (!is_numeric($kills)) { if ($d) echo 3; return FALSE; }
	if (!is_numeric($assists)) { if ($d) echo 4; return FALSE; }
	if (!is_numeric($souls)) { if ($d) echo 5; return FALSE; }
	if (!is_numeric($npc)) { if ($d) echo 6; return FALSE; }
	if (!is_numeric($healed)) { if ($d) echo 7; return FALSE; }
	if (!is_numeric($res)) { if ($d) echo 8; return FALSE; }
	if (!is_numeric($gold)) { if ($d) echo 9; return FALSE; }
	if (!is_numeric($repair)) { if ($d) echo 10; return FALSE; }
	if (!is_numeric($bd)) { if ($d) echo 11; return FALSE; }
	if (!is_numeric($bd)) { if ($d) echo 12; return FALSE; }
	if (!is_numeric($razed)) { if ($d) echo 13; return FALSE; }
	if (!is_numeric($deaths)) { if ($d) echo 14; return FALSE; }
	if (!preg_match("/[0-9][0-9]:[0-9][0-9]:[0-9][0-9]/", $duration)) { if ($d) echo 15; return FALSE; }
	if (!is_numeric($sf)) { if ($d) echo 16; return FALSE; }
	return TRUE;
}

function is_valid_commander_stat_info($stats) {
	$d = 1; // quick debug, 1 = on, 0 = off
        if (count($stats) != 13) { print_r($stats); return FALSE; }
	list($exp, $orders, $gold, $erected, $repaired, $razed, $buffs, $healed, $debuffs, $dmg, $kills, $duration, $team) = $stats;
	if (!is_numeric($exp)) { if ($d) echo 2; return FALSE; }
	if (!is_numeric($orders)) { if ($d) echo 3; return FALSE; }
	if (!is_numeric($gold)) { if ($d) echo 4; return FALSE; }
	if (!is_numeric($erected)) { if ($d) echo 5; return FALSE; }
	if (!is_numeric($repaired)) { if ($d) echo 6; return FALSE; }
	if (!is_numeric($razed)) { if ($d) echo 7; return FALSE; }
	if (!is_numeric($buffs)) { if ($d) echo 8; return FALSE; }
	if (!is_numeric($healed)) { if ($d) echo 9; return FALSE; }
	if (!is_numeric($debuffs)) { if ($d) echo 10; return FALSE; }
	if (!is_numeric($dmg)) { if ($d) echo 11; return FALSE; }
	if (!is_numeric($kills)) { if ($d) echo 12; return FALSE; }
	if (!preg_match("/[0-9][0-9]:[0-9][0-9]:[0-9][0-9]/", $duration)) { if ($d) echo 1; return FALSE; }
	return TRUE;
}


function is_valid_match_stat_info($stats) {
	$d = 1; // quick debug, 1 = on, 0 = off
	$statlist = array();
        if (!isset($stats)) { return FALSE; }
        if (!isset($stats[0][0])) { return FALSE; }
	$matchid = $stats[0][0];
	$general_stats = implode(", ", $stats[0]);
	$team1_stats = implode(", ", $stats[1]);
	$team2_stats = implode(", ", $stats[2]);
	$commanders = $stats[3];
	$players_team1 = $stats[4];
	$players_team2 = $stats[5];
	if (count($stats[0]) < 5) { if ($d) echo -2;  return FALSE; }
	if (count($stats[1]) < 13) { if ($d) echo -1;  return FALSE; }
	if (count($stats[2]) < 13) { if ($d) echo -1;  return FALSE; }
	// array_push($statlist, $general_stats, $team1_stats, $team2_stats);
	list($matchid, $date, $duration, $map, $winner) = $stats[0];
	list($player_dmg_team1, $kills_team1, $assists_team1, $souls_team1, $healed_team1, $res_team1, $gold_team1, $repaired_team1, $npc_team1, $bd_team1, $razed_team1, $deaths_team1, $kd_team1) = $stats[1];
	list($player_dmg_team2, $kills_team2, $assists_team2, $souls_team2, $healed_team2, $res_team2, $gold_team2, $repaired_team2, $npc_team2, $bd_team2, $razed_team2, $deaths_team2, $kd_team2) = $stats[2];
	if (!is_numeric($matchid) || $matchid < 7000) { if ($d) echo 1; return FALSE; }
	if (!is_numeric($date) || $date < 1000000000) { if ($d) echo 2; return FALSE; }
	if (!preg_match("/[0-9][0-9]:[0-9][0-9]:[0-9][0-9]/", $duration)) { if ($d) echo 3; return FALSE; }
	if ($map == "" || $map == "N/A") { if ($d) echo 4; return FALSE; }
	if ($winner != 1 && $winner != 2) { if ($d) echo 5; return FALSE; }
	if (!is_numeric($player_dmg_team1)) { if ($d) echo 6; return FALSE; }
	if (!is_numeric($player_dmg_team2)) { if ($d) echo 7; return FALSE; }
	if (!is_numeric($kills_team1)) { if ($d) echo 8; return FALSE; }
	if (!is_numeric($kills_team2)) { if ($d) echo 9; return FALSE; }
	if (!is_numeric($assists_team1)) { if ($d) echo 10; return FALSE; }
	if (!is_numeric($assists_team2)) { if ($d) echo 11; return FALSE; }
	if (!is_numeric($souls_team1)) { if ($d) echo 12; return FALSE; }
	if (!is_numeric($souls_team2)) { if ($d) echo 13; return FALSE; }
	if (!is_numeric($healed_team1)) { if ($d) echo 14; return FALSE; }
	if (!is_numeric($healed_team2)) { if ($d) echo 15; return FALSE; }
	if (!is_numeric($res_team1)) { if ($d) echo 16; return FALSE; }
	if (!is_numeric($res_team2)) { if ($d) echo 17; return FALSE; }
	if (!is_numeric($gold_team1)) { if ($d) echo 18; return FALSE; }
	if (!is_numeric($gold_team2)) { if ($d) echo 19; return FALSE; }
	if (!is_numeric($repaired_team1)) { if ($d) echo 20; return FALSE; }
	if (!is_numeric($repaired_team2)) { if ($d) echo 21; return FALSE; }
	if (!is_numeric($npc_team1)) { if ($d) echo 22; return FALSE; }
	if (!is_numeric($npc_team2)) { if ($d) echo 23; return FALSE; }
	if (!is_numeric($bd_team1)) { if ($d) echo 24; return FALSE; }
	if (!is_numeric($bd_team2)) { if ($d) echo 25; return FALSE; }
	if (!is_numeric($razed_team1)) { if ($d) echo 26; return FALSE; }
	if (!is_numeric($razed_team2)) { if ($d) echo 27; return FALSE; }
	if (!is_numeric($deaths_team1)) { if ($d) echo 28; return FALSE; }
	if (!is_numeric($deaths_team2)) { if ($d) echo 29; return FALSE; }
	if (count($players_team1)>60) { if ($d) echo 30; return FALSE; }
	if (count($players_team2)>60) { if ($d) echo 31; return FALSE; }
        if (count($players_team1) != count(array_unique($players_team1))) { if ($d) echo 32;  return FALSE; }
        if (count($players_team2) != count(array_unique($players_team2))) { if ($d) echo 33;  return FALSE; }
	if (count($commanders)!=2) { if ($d) echo 34; return FALSE; }
	return TRUE;
}

function add_player_participant($playerid, $playername) {
	if (!player_exists($playerid)) {
		add_new_player($playerid, $playername);
	}
}

function add_player($playerid, $playername) {
        if (is_array($playerid) || is_array($playername) || empty($playerid) || empty($playername)) { return; }
        if ($playerid == 0) { return; }
	if (!player_exists($playerid)) {
		add_new_player($playerid, $playername);
	} else {
                $playername_in_db = playerid_to_playername($playerid);
                if ($playername_in_db != $playername) {
                        update_playername_for_playerid($playerid, $playername);
                        echo " Updated $playername_in_db to $playername, ";
                }
        }
}

function save_match_stats($stats) {
	$statlist = array();
	$matchid = $stats[0][0];
	$general_stats = implode(", ", $stats[0]);
	$team1_stats = implode(", ", $stats[1]);
	$team2_stats = implode(", ", $stats[2]);
	$commanders = $stats[3];
	$players_team1 = array_unique($stats[4]);
	$players_team2 = array_unique($stats[5]);
	array_push($statlist, $general_stats, $team1_stats, $team2_stats);
	save_stats_for_single_match($matchid, $statlist);
	save_participants_in_match_transaction($matchid, $commanders, $players_team1, $players_team2);
	// save_participants_in_match($matchid, $commanders, $players_team1, $players_team2);

}

function save_participants_in_match_transaction($matchid, $commanders, $players_team1, $players_team2) {
	$team = 0;
	$inserts = array();
	$updates = array();
	flush();
	foreach ($commanders as $commander) {
		$team++;
		$line = explode(",", $commander);
		$playerid = $line[0];
		$playername = $line[1];
		add_player($playerid, $playername);
		if (!participant_record_exists_for_commander($matchid, $playerid, $team)) {
			if (match_exists_for_playerid($matchid, $playerid)==0) {
				$query = "INSERT INTO commanderstats (`id`, `matchid`, `playerid`, `exp`, `orders`, `gold`, `erected`, `repaired`, `razed`, `buffs`, `healed`, `debuffs`, `dmg`, `kills`, `duration`, `team`) VALUES (NULL, '$matchid', '$playerid', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '$team');";
				array_push($inserts, $query);
			} else {
				$query = "UPDATE commanderstats SET `matchid` = '$matchid', `playerid` = '$playerid', `exp` = '0', `orders` = '0', `gold` = '0', `erected` = '0', `repaired` = '0', `razed` = '0', `buffs` = '0', `healed` = '0', `debuffs` = '0', `dmg` = '0', `kills` = '0', `duration` = '0' WHERE `commanderstats`.`matchid` = '$matchid' AND `commanderstats`.`playerid` = '$playerid';";
				array_push($updates, $query);
			}
		}
	}
	foreach ($players_team1 as $player_team1) {
		$line = explode(",", $player_team1);
		$playerid = $line[0];
		$playername = $line[1];
		add_player($playerid, $playername);
		if (!participant_record_exists_for_player($matchid, $playerid, 1)) {
			if (match_exists_for_playerid($matchid, $playerid)==0) {
				$query = "INSERT INTO stats (`id`, `matchid`, `playerid`, `exp`, `dmg`, `kills`, `assists`, `souls`, `npc`, `healed`, `res`, `gold`, `repair`, `bd`, `razed`, `deaths`, `kd`, `duration`, `sf`, `team`) VALUES (NULL, '$matchid', '$playerid', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1');";
				array_push($inserts, $query);
			} else {
				$query = "UPDATE stats SET `matchid` = '$matchid', `playerid` = '$playerid', `exp` = '0', `dmg` = '0', `kills` = '0', `assists` = '0', `souls` = '0', `npc` = '0', `healed` = '0', `res` = '0', `gold` = '0', `repair` = '0', `bd` = '0', `razed` = '0', `deaths` = '0', `kd` = '0', `duration` = '0', `sf` = '0' WHERE `stats`.`matchid` = '$matchid' AND `stats`.`playerid` = '$playerid';";
				array_push($updates, $query);
			}
		}
	}
	foreach ($players_team2 as $player_team2) {
		$line = explode(",", $player_team2);
		$playerid = $line[0];
		$playername = $line[1];
		add_player($playerid, $playername);
		if (!participant_record_exists_for_player($matchid, $playerid, 2)) {
			if (match_exists_for_playerid($matchid, $playerid)==0) {
				$query = "INSERT INTO stats (`id`, `matchid`, `playerid`, `exp`, `dmg`, `kills`, `assists`, `souls`, `npc`, `healed`, `res`, `gold`, `repair`, `bd`, `razed`, `deaths`, `kd`, `duration`, `sf`, `team`) VALUES (NULL, '$matchid', '$playerid', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '2');";
				array_push($inserts, $query);
			} else {
				$query = "UPDATE stats SET `matchid` = '$matchid', `playerid` = '$playerid', `exp` = '0', `dmg` = '0', `kills` = '0', `assists` = '0', `souls` = '0', `npc` = '0', `healed` = '0', `res` = '0', `gold` = '0', `repair` = '0', `bd` = '0', `razed` = '0', `deaths` = '0', `kd` = '0', `duration` = '0', `sf` = '0' WHERE `stats`.`matchid` = '$matchid' AND `stats`.`playerid` = '$playerid';";
				array_push($updates, $query);
			}
		} 
	}

	$querys = array_merge($inserts, $updates);
	save_participant_records_all_together($querys);
}


function save_participants_in_match($matchid, $commanders, $players_team1, $players_team2) {
	$team = 0;
	foreach ($commanders as $commander) {
		$team++;
		$line = explode(",", $commander);
		$playerid = $line[0];
		$playername = $line[1];
		add_player($playerid, $playername);
		if (!participant_record_exists_for_commander($matchid, $playerid, $team)) {
			$stats = array(0,0,0,0,0,0,0,0,0,0,0,0,$team);
			save_commander_stats_for_match($playerid, $matchid, $stats);
		}
	}
	foreach ($players_team1 as $player_team1) {
		$line = explode(",", $player_team1);
		$playerid = $line[0];
		$playername = $line[1];
		add_player($playerid, $playername);
		if (!participant_record_exists_for_player($matchid, $playerid, 1)) {
			$stats = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1);
			save_player_stats_for_match($playerid, $matchid, $stats);
		}
	}
	foreach ($players_team2 as $player_team2) {
		$line = explode(",", $player_team2);
		$playerid = $line[0];
		$playername = $line[1];
		add_player($playerid, $playername);
		if (!participant_record_exists_for_player($matchid, $playerid, 2)) {
			$stats = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2);
			save_player_stats_for_match($playerid, $matchid, $stats);
		} 

	}
}


function get_match_stats_from_html($html) {
	//print_r($html);
        if ($html === false) { return array(); }
	$stats = array();		// Master array
	$general_info = array();	// map, duration etc
	$match_info_team1 = array();	// total kills etc of the team
	$match_info_team2 = array();	//
	$players_team1 = array();	// players in team1
	$players_team2 = array();	//
	$commanders = array();		// commanders human first

	$general_info_line = '<!-- Start Prime -->';
	$match_info_line = '<!-- Player Damage -->';
	$players_info_line = '<!-- Top -->';

	$general_info_tags = array("Match ID: ", "Played: ", " &nbsp; Duration:", "Map Name: ", " -", " Mb");
	$match_info_tags = array("Team 1: ", "Team 2: ");
	$player_tags = array("Total Players", "Average Skill Factor", "Clan:", "Disconnected Players", "Action Players", " N/A", "&nbsp;");
	$player_tags_line1 = array("<td><a href=\"http://savage2.com/en/player_stats.php?id=", "</a></td>");
	$player_tags_line2 = array("\" class=w12>&nbsp;", "\" class=gr12>&nbsp;", "\" class=w12>", "\" class=gr12>", " ");
	$player_tags_line3 = array("&nbsp;");
	$commander_tags_line1 = array(
		"<a href=\"javascript:showIt('pspec');\" ONCLICK=\"getCommMatchInfo(",
		")\"><div style= \"padding-top:23px;\">",
		"</div></a>",
		"&nbsp;"
	);
	$commander_tags_line2 = array(")\"><div style=\"padding-top:23px;\">");

	$index = 0;
	foreach ($html as $line) {
		//echo "$line\n";
		if ($players_info_line == 1) {
			$html = trim($line);
			$line = trim(strip_tags($line));
			$line = str_replace($player_tags, "", $line);
			if ($line == "" || is_numeric($line)) { continue; }
			if ($line == "Top Rated Matches") { $players_info_line = 0; continue;  }
			if (preg_match("/<\/div><\/a>$/", $html)) {
				$index++;
				if ($line == '-=Ophelia=-' || $line == '-=Jereziah=-') {
					$commanderline = "0,0";
				} else {
					$commanderline = str_replace($commander_tags_line1, "", $html);
					$commanderline = str_replace($commander_tags_line2, ",", $commanderline);
					$commarr = explode(",", $commanderline);
					$commanderline = trim("$commarr[1],$commarr[2]");
				}
				array_push($commanders, $commanderline);
				//echo "\nCommander: $commanderline";
			} else {
				$playerline = str_replace($player_tags_line1, "", $html);
				$playerline = str_replace($player_tags_line2, ",", $playerline);
				$playerline = str_replace($player_tags_line3, "", $playerline);
				if ($index == 1) {
					array_push($players_team1, $playerline);
				} else {
					array_push($players_team2, $playerline);
				}
				//echo "\nPlayer: $playerline";
			}
		} else if ($match_info_line == 1) {
			$end = trim($line);
			$line = trim(strip_tags($line));
			if ($end == '<!-- End Prime -->') { $index = 0; $match_info_line = 0; continue; }
			if ($line == "") { continue; }	
			$index++;
			$line = str_replace($match_info_tags, "", $line);
			$line = str_replace(",", "", $line);
			if ($index == 1) {
				array_push($match_info_team1, $line);
			} else {
				array_push($match_info_team2, $line);
				$index = 0;
			} 
			//echo "\nMatchinfo: $line";
		} else if ($general_info_line == 1) {
			$line = trim(strip_tags($line));
			if ($line == "") { continue; }
			if ($line == "Team 1" || $line == "Team 2") {
				$line = str_replace("Team ", "", $line);
				array_push($general_info, $line);
				$index = 0;
				$general_info_line = 0;	
				continue;
			}
			$index++;
			$line = str_replace($general_info_tags, "", $line);
			if ($index == 1 || $index == 3) {
				array_push($general_info, $line);
			} else if ($index == 2) {
				$arr = explode(" ", $line);
                                if (count($arr)<3) { $arr = array(0,0,0); }
				list($date, $time, $duration) = $arr;
				$date = strtotime("$date $time");
				array_push($general_info, $date, $duration);
			} 
			//echo "\nGeneral info: $line";
		} else if (strcmp($general_info_line, trim($line)) == 0) {
			$general_info_line = 1;
		} else if (strcmp($match_info_line, trim($line)) == 0) {
			$match_info_line = 1;
		} else if (strcmp($players_info_line, trim($line)) == 0) {
			$players_info_line = 1;
		} else {
			// echo "\nHTML: $line";
		}
	}
        $players_team1 = array_unique($players_team1);
        $players_team2 = array_unique($players_team2);
	array_push($stats, $general_info, $match_info_team1, $match_info_team2, $commanders, $players_team1, $players_team2);
	return $stats;
}

function get_match_stats_for_match($matchid) {
	global $cookie_stats;
	$url = "www.savage2replays.com/match_replay.php?mid=$matchid";
	$html = curl_get_request("www.savage2replays.com", $url, $cookie_stats);
        if ($html === false) { return false; }
	$stats = get_match_stats_from_html($html);
	return $stats;
}

function get_player_stats_for_match($playerid, $matchid) {
	global $cookie_stats;
	$url = "www.savage2.com/en/get_player_match_stats.php?aid=$playerid&mid=$matchid";
	$html = curl_get_request("www.savage2.com", $url, $cookie_stats);
        if ($html === false) { return false; }
	$stats = get_player_match_stats_from_html($html);
        if ($stats === false) { return false; }
        if (count($stats)==2) { return array(); }
	if ($stats['14'] == "00:00:00") {
		$url = "www.savage2.com/en/get_comm_match_stats.php?aid=$playerid&mid=$matchid";
		$html = curl_get_request("www.savage2.com", $url, $cookie_stats);
                if ($html === false) { return false; }
		$stats = get_player_match_stats_from_html($html);
                if (count($stats) == 2) { return array(); }
	}
	return $stats;
}


function get_player_match_stats_from_html($html) {
	$stats = array(0);
	$lookfor = '<table border=0 cellpadding=0 cellspacing=0 width=380 class=w12>';
	$index = 0;
	foreach ($html as $line) {
		// echo $line;
		if ($lookfor == "1") {
			$line = trim(strip_tags($line));
			if ($line == "") { continue; }
			$stats[$index] = str_replace(",", "", $line);
			$index++;
		} else if (strcmp($lookfor, trim($line)) == 0) {
			$lookfor = "1";
		}
	}
	array_push($stats, "0");
	return $stats;
}


function get_player_matchids_on_page($playerid, $page) {
	global $cookie_stats;
	$host = "www.savage2.com";
	$url = "www.savage2.com/en/get_match_list.php?aid=$playerid&page=$page";
	$html = curl_get_request($host, $url, $cookie_stats);
        if ($html === false) { return false; }
	$matches = get_14_matches_from_html($html);
	return $matches;
}

function get_14_matches_from_html($html) {
	$matches = array();
	$matchid = 0;
	foreach ($html as $line) {
		$line = trim(strip_tags($line));
		//echo "$line\n<br>";
		if (is_numeric($line)) {
			$matchid = $line;
		} else if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{2}/", "$line")) {
				$matches[$matchid] = $line;
				//echo "=> $matchid @ $line\n<br>";
		} else  { continue; }
	}
	return $matches;
}

function get_playername_from_html($html) {
	$playername = false;
        $clan       = false;
        if ($html[7] != '<script language="JavaScript">') { return false; }
        $pline = trim(strip_tags($html[1336]));
        $cline = trim(strip_tags($html[1338]));
        if (strlen($pline) > 12) { return false; }
        if ($cline == "N/A") { $cline = ""; }
        $playername = $pline;
        $clan       = "";
	return $clan.$playername;
}


?>
