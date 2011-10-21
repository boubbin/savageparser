<?php
error_reporting(E_ALL);

global $cookie_stats;
$cookie_stats = '/tmp/savage_stats_cookie.txt';

require('mysql_functions.php');
require('print_functions.php');
require('http_functions.php');
require('functions.php');

function login_to_s2_mainpage($user, $pass) {
        global $cookie_stats;
	$url = "www.savage2.com/en/remote_login.php";
        $post = array("login" => "$user", "password" => "$pass", "x" => "17", "y" => "11");
        $host = "www.savage2.com";
        return curl_get_request($host, $url, NULL, 0, $post, 1);
}

function remote_login($referer) {
	global $cookie_stats;
	$url = "www.savage2.com/en/remote_login.php";
	if (!$referer) {
		$post = array("login" => "portalhunt", "password" => "portalhunt", "x" => "17", "y" => "11");
		$host = "www.savage2.com";
		curl_get_request($host, $url, $cookie_stats, $referer, $post, 1);
	} else {
		$host = "savage2.com";
		$post = array("login" => "portalhunt", "password" => "portalhunt", "x" => "12", "y" => "8");
		$referer = "http://www.savage2replays.com/match_replay.php?mid=297338";
		curl_get_request($host, $url, $cookie_stats, $referer, $post, 1);
	}
}

function login_to_savage2_website_set_replays_cookie_and_stats_cookie() {
	global $cookie_stats;
	$host = "www.savage2.com";
	$url = "http://www.savage2.com/en/main.php";
	curl_get_request($host, $url, $cookie_stats, 0, array(), 1);
	remote_login(0);
	$host = "www.savage2.com";
	$url = "www.savage2.com/en/goto_last_match.php?id=338371";
	curl_get_request($host, $url, $cookie_stats, 0, array(), 1);
	remote_login(1);
        exec('chmod 777 $cookie_stats');
}

function check_cookies_and_renew_em_if_needed() {
        global $cookie_stats;
	if (!file_exists('/tmp/savage_stats_cookie.txt')) {
		echo "<li><font color=red>There is no cookie file! Rebooted?</font>\n<br>";
		login_to_savage2_website_set_replays_cookie_and_stats_cookie();
	}
}
function get_matchstats_for_matchids($matchids) {
	global $cookie_stats;
        check_cookies_and_renew_em_if_needed();
        $i = 0;
        $invalid = 0;
        foreach ($matchids as $matchid) {
                if (!match_exists($matchid)) {
                        $full_match_stats = get_match_stats_for_match($matchid);
                        if (empty($full_match_stats)) { remove_cookie_error_report(); die(); }
                        if (is_valid_match_stat_info($full_match_stats)) {
                                save_match_stats($full_match_stats);
                                echo "got, "; flush();
                                $invalid = 0;
                                $i++;
			} else { echo " ($matchid) ";flush(); $invalid++; if ($invalid >= 30) { break; } }
                }
        }
}

function get_matchstats_for_matchids_all($matchids) {
	global $cookie_stats;
        check_cookies_and_renew_em_if_needed();
        $i = 0;
        $invalid = 0;
        foreach ($matchids as $matchid) {
                if (!match_exists($matchid)) {
                        $full_match_stats = get_match_stats_for_match($matchid);
                        if (empty($full_match_stats)) { remove_cookie_error_report(); die(); }
                        if (is_valid_match_stat_info($full_match_stats)) {
                                save_match_stats($full_match_stats);
                                echo "got, "; flush();
                                $invalid = 0;
                                $i++;
			} else { echo " ($matchid) ";flush(); $invalid++; if ($invalid >= 700) { break; } }
                }
        }
}

function get_10pages_stats_of_player($playerid, $playername, $page = 1) {
	// die("Parser is in maintenance<br>check back laterz");
	$time = microtime(true);
	global $cookie_stats;
        check_cookies_and_renew_em_if_needed();
	global $sql_connections;
	$sql_connections = 0;
	add_player($playerid, $playername);
	$tab = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	// $page = 1;
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
	echo "<li>Startup, hold on this might take a while!</li>";
	flush();
	while ($page < 30) { // do not go futher than 30 pages ever....
		$laptime = microtime(true);
		$lapsleep = 0;
		$matchnum = 0;
		if (isset($lastmatch)) {
                        if (match_is_played_over_period_days_ago_matchid($lastmatch, 30)) { echo "<font color=red>Lastmatch is already out of 30days.. break</font>\n<br>"; break; }
			if (isset($lastlaplastmatch)) {
				if ($lastlaplastmatch == $lastmatch) {
					$lastsame++;
					echo "<font color=red>Lastmatch has been the same for ($lastsame) rounds.. break?</font>\n<br>";
					if ($lastsame == $breakon) { break; }
				} else { $lastlaplastmatch = $lastmatch; }
			} else { $lastlaplastmatch = $lastmatch; }
		}
		echo "<br><li>Page ($page), work balance: <b>$nojob</b>, work last round (total): <b>$job</b>, num of mysql-queries: <b>$sql_connections</b>\n<br>";
		$sql_connections = 0;
		$matchids = get_player_matchids_on_page($playerid, $page);
                if ($matchids === false) {
                        echo "<br><b>Savage2-website is propably out of reach, try again after few minutes..</b>";
                        return 0;
                }
                echo "$tab Getting player (<font color=#0052FF>$playername</font>) matches on page $page ".count($matchids)."pcs)\n<br>"; flush();
		foreach ($matchids as $matchid => $date) {
			$matchnum++;
			if ($nojob == $jobend) { echo "$tab<br><font color=red>No work for last ($nojob) matches.. break!</font>\n<br>"; break; }
			$job = 0;
			$matchtime = microtime(true);
			echo "$tab $tab ($matchnum) Matchid: <font color=#00FF33>$matchid</font> "; flush();
			$individual_stats = "";
			$full_match_stats = "";
			$sleep1 = 0;
			$id1++; // = rand(501,1000);
			$id2++; // = rand(0,500);
                        $checked_matches++;
			if (match_exists_for_playerid($matchid, $playerid)!="1") { echo_loading_player(1, $id1); flush(); $individual_stats = get_player_stats_for_match($playerid, $matchid); echo_loading_player(0, $id1); flush(); sleep($sleep1); }
			if (!match_exists($matchid)) { echo_loading_match(1, $id2); flush(); $full_match_stats = get_match_stats_for_match($matchid); flush(); sleep($sleep1); echo_loading_match(0, $id2); flush(); }
                        if ($individual_stats === false || $full_match_stats === false) { echo "<br><font color=red>Savage2-webpage is out o reach, try again later!"; return 0; }
                        if (is_array($individual_stats) && is_array($full_match_stats) && empty($individual_stats) && empty($full_match_stats)) { remove_cookie_error_report(); return 0; }
			if (empty($individual_stats)==FALSE) {
				$job++; $nojob = 0;
				if (!isset($individual_stats[16])) {
					if (is_valid_commander_stat_info($individual_stats)) {
                                                $new_commander_matches++;
						save_commander_stats_for_match($playerid, $matchid, $individual_stats);
						//print_commander_stats_table($individual_stats);
					} else {
						echo " <font color=red>Found invalid individual match stats for match $matchid (Commander), omitting..</font>";
					}
				} else {
					if (is_valid_player_stat_info($individual_stats)) {
                                                $new_player_matches++;
						save_player_stats_for_match($playerid, $matchid, $individual_stats);
						//print_player_stats_table($individual_stats);
					} else {
						echo " <font color=red>Found invalid individual match stats for match $matchid (Player), omitting..</font>";
					}
				}
			}
			if (empty($full_match_stats)==FALSE) {
				$job++; $nojob = 0;
				// print_r($full_match_stats);
				if (is_valid_match_stat_info($full_match_stats)) {
					save_match_stats($full_match_stats);
				} else {
					echo " <font color=red>Found invalid match stats for match $matchid (Full stats), omitting..</font>";
				}
				// echo_loading_match(0, $id2);
			}
			if ($job == 0) {
				$matchtime = round(microtime(true) - $matchtime, 2);
				echo " (<b>$matchtime seconds</b>)<br>\n";
			} else if ($job == 1) {
				$lapsleep += $sleep1;
				$matchtime = round(microtime(true) - $matchtime - $sleep1, 2);
				echo " (<b>$matchtime seconds</b> + (sleeped: ".($sleep1).")</b>)<br>\n";
			} else {
				$lapsleep += $sleep1+$sleep1;
				$matchtime = round(microtime(true) - $matchtime - ($sleep1+$sleep1), 2);
				echo " (<b>$matchtime seconds</b> + (sleeped: ".($sleep1+$sleep1).")</b>)<br>\n";
			}
			if ($job == 0) { $nojob++; }
			$lastmatch = $matchid;
			flush();
		}
		$page++;
		$laptime = round(microtime(true) - $laptime - $lapsleep,2);
		echo "<b>$tab $tab Laptime: $laptime seconds + (sleeped: $lapsleep)</b><br>\n";
		flush();
		if ($nojob == $jobend) { break; }
	}
	update_update_time_for_playerid($playerid);
	$time = round(microtime(true) - $time,2);
        echo "<br><b>Went through ($checked_matches) matches:</b>";
	echo "<br><b><li>Found ($new_player_matches) new matches for actionplayer</b></li>";
        echo "<b><li>Found ($new_commander_matches) new matches for commander</b></li>";
        echo "<b>Total time of the operation: $time seconds</b>";
	return 1;
}

?>
