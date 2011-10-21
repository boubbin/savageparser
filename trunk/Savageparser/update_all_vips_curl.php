<?php

global $cookie_stats;
$cookie_stats = '/tmp/savage_stats_cookie.txt';

require('print_page_functions.php');
require('get_functions.php');
include('parser/index.php');

function curl_randomly_update_number_of_vips($update) {
        global $cookie_stats;
        check_cookies_and_renew_em_if_needed();
	$players = get_number_of_random_vip_players_which_needs_an_update($update);
        $curl = use_curl_object();
        flush();
	while ($row = mysql_fetch_assoc($players)) {
                $time = microtime(true);
                $page = 1;
		$playerid = $row['playerid'];
		$playername = playerid_to_playername($playerid);
                flush();
                global $sql_connections;
                $sql_connections = 0;
                $tab = "\t";
                $nojob = 0;
                $job = 0;
                $jobend = 4;
                $lastsame = 0;
                $breakon  = 4;
                $id1 = 0;
                $id2 = 10000;
                $new_player_matches = 0;
                $new_commander_matches = 0;
                $checked_matches = 0;
                $laptime = microtime(true);
		$lapsleep = 0;
		$matchnum = 0;
                while ($page < 20 || $page > 20) {
                        if (isset($lastmatch)) {
                                if (isset($lastlaplastmatch)) {
                                        if ($lastlaplastmatch == $lastmatch) {
                                                $lastsame++;
                                                echo "Lastmatch has been the same for ($lastsame) rounds.. break?\n";
                                                if ($lastsame == $breakon) { break; }
                                        } else { $lastlaplastmatch = $lastmatch; }
                                } else { $lastlaplastmatch = $lastmatch; }
                        }
                        echo "\n\nPage ($page), work balance: $nojob, work last round (total): $job, num of mysql-queries: $sql_connections\n";
                        $sql_connections = 0;
                        echo "$tab Getting player ($playername) matches on page $page ";
                        $matchids = curl_get_player_matchids_on_page($curl, $playerid, $page);
                        if ($matchids === false) {
                                echo "<br><b>Savage2-website is propably out of reach, try again after few minutes..</b>";
                                return 0;
                        }
                        echo "(".count($matchids)."pcs)\n";
                        foreach ($matchids as $matchid => $date) {
                                $matchnum++;
                                if ($nojob == $jobend) { echo "$tab No work for last ($nojob) matches.. break!\n"; break; }
                                $job = 0;
                                $matchtime = microtime(true);
                                echo "$tab $tab ($matchnum) Matchid: $matchid "; flush();
                                $individual_stats = "";
                                $full_match_stats = "";
                                $sleep1 = 0;
                                $id1++; // = rand(501,1000);get_player_stats_for_match($playerid, $matchid)
                                $id2++; // = rand(0,500);
                                $checked_matches++;
                                flush();
                                if (match_exists_for_playerid($matchid, $playerid)!="1") { flush(); $individual_stats = curl_get_player_stats_for_match($curl, $playerid, $matchid); flush(); sleep($sleep1); }
                                if (!match_exists($matchid)) { flush(); $full_match_stats = curl_get_match_stats_for_match($curl, $matchid); flush(); sleep($sleep1); flush(); }
                                if ($individual_stats === false || $full_match_stats === false) { echo "<br><font color=red>Savage2-webpage is out o reach, try again later!"; return 0; }
                                if (is_array($individual_stats) && empty($individual_stats) && empty($full_match_stats)) { curl_close($curl); remove_cookie(); return false; }
                                if (empty($individual_stats)==FALSE) {
                                        $job++; $nojob = 0;
                                        if (!isset($individual_stats[16])) {
                                                if (is_valid_commander_stat_info($individual_stats)) {
                                                        $new_commander_matches++;
                                                        save_commander_stats_for_match($playerid, $matchid, $individual_stats);
                                                        //print_commander_stats_table($individual_stats);
                                                } else {
                                                        echo " Found invalid individual match stats for match $matchid (Commander), omitting..\n";
                                                }
                                        } else {
                                                if (is_valid_player_stat_info($individual_stats)) {
                                                        $new_player_matches++;
                                                        save_player_stats_for_match($playerid, $matchid, $individual_stats);
                                                        //print_player_stats_table($individual_stats);
                                                } else {
                                                        echo " Found invalid individual match stats for match $matchid (Player), omitting..\n";
                                                }
                                        }
                                }
                                if (empty($full_match_stats)==FALSE) {
                                        $job++; $nojob = 0;
                                        // print_r($full_match_stats);
                                        if (is_valid_match_stat_info($full_match_stats)) {
                                                save_match_stats($full_match_stats);
                                        } else {
                                                echo " Found invalid match stats for match $matchid (Full stats), omitting..\n";
                                        }
                                        // echo_loading_match(0, $id2);
                                }
                                if ($job == 0) {
                                        $matchtime = round(microtime(true) - $matchtime, 2);
                                        echo " ($matchtime seconds)\n";
                                } else if ($job == 1) {
                                        $lapsleep += $sleep1;
                                        $matchtime = round(microtime(true) - $matchtime - $sleep1, 2);
                                        echo " ($matchtime seconds + (sleeped: ".($sleep1)."))\n";
                                } else {
                                        $lapsleep += $sleep1+$sleep1;
                                        $matchtime = round(microtime(true) - $matchtime - ($sleep1+$sleep1), 2);
                                        echo " ($matchtime seconds + (sleeped: ".($sleep1+$sleep1)."))\n";
                                }
                                if ($job == 0) { $nojob++; }
                                $lastmatch = $matchid;
                                flush();
                        }
                        $page++;
                        $laptime = round(microtime(true) - $laptime - $lapsleep,2);
                        echo "$tab $tab Laptime: $laptime seconds + (sleeped: $lapsleep)\n";
                        flush();
                        if ($nojob == $jobend) { break; }
                }
                update_update_time_for_playerid($playerid);
                $time = round(microtime(true) - $time,2);
                echo "Went through ($checked_matches) matches:\n";
                echo "Found ($new_player_matches) new matches for actionplayer\n";
                echo "Found ($new_commander_matches) new matches for commander\n";
                echo "Total time of the operation: $time seconds\n";
	}
        echo "Closed curl\n";
        curl_close($curl);
        return true;
}
$i = 0;
while (!curl_randomly_update_number_of_vips(6)) { $i++; if ($i >= 4) { break; } }
?>
