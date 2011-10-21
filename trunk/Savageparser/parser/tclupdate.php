<?php

error_reporting(0);

global $cookie_stats;
$cookie_stats = '/tmp/savage_stats_cookie.txt';

require('mysql_functions.php');
require('print_functions.php');
require('http_functions.php');
require('functions.php');


function remote_login($referer) {
	global $cookie_stats;
	$url = "www.savage2.com/en/remote_login.php";
;
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
}

function update_stats_for_player($playerid, $playername, $page = 1) {
	$time = microtime(true);
	global $cookie_stats;
	if (!file_exists('/tmp/savage_stats_cookie.txt')) {
		login_to_savage2_website_set_replays_cookie_and_stats_cookie();
	}
	global $sql_connections;
	$sql_connections = 0;
	// $page = 1;
	$nojob = 0;
	$job = 0;
	$jobend = 4;
	$lastsame = 0;
	$breakon  = 4;
        $new_player_matches = 0;
        $new_commander_matches = 0;
        $checked_matches = 0;
	while ($page < 2) { // do not go futher than 20 pages ever..
		$matchnum = 0;
		if (isset($lastmatch)) {
			if (isset($lastlaplastmatch)) {
				if ($lastlaplastmatch == $lastmatch) {
					$lastsame++;
					if ($lastsame == $breakon) { break; }
				} else { $lastlaplastmatch = $lastmatch; }
			} else { $lastlaplastmatch = $lastmatch; }
		}
		$matchids = get_player_matchids_on_page($playerid, $page);
		foreach ($matchids as $matchid => $date) {
			$matchnum++;
			if ($nojob == $jobend) { break; }
			$job = 0;
			$individual_stats = "";
			$full_match_stats = "";
                        $checked_matches++;
			if (match_exists_for_playerid($matchid, $playerid)!="1") { $individual_stats = get_player_stats_for_match($playerid, $matchid); }
			if (!match_exists($matchid)) { $full_match_stats = get_match_stats_for_match($matchid); }
			if (is_array($individual_stats) && empty($individual_stats) && empty($full_match_stats)) { return "-1"; }
			if (empty($individual_stats)==FALSE) {
				$job++; $nojob = 0;
				if (!isset($individual_stats[16])) {
					if (is_valid_commander_stat_info($individual_stats)) {
                                                $new_commander_matches++;
						save_commander_stats_for_match($playerid, $matchid, $individual_stats);
					}
				} else {
					if (is_valid_player_stat_info($individual_stats)) {
                                                $new_player_matches++;
						save_player_stats_for_match($playerid, $matchid, $individual_stats);
					}
				}
			}
			if (empty($full_match_stats)==FALSE) {
				$job++; $nojob = 0;
				if (is_valid_match_stat_info($full_match_stats)) {
					save_match_stats($full_match_stats);
				}
			}
			if ($job == 0) { $nojob++; }
			$lastmatch = $matchid;
		}
		$page++;
		if ($nojob == $jobend) { break; }
	}
	update_update_time_for_playerid($playerid);
	return "$checked_matches,$new_player_matches,$new_commander_matches";
}

$playerid = $argv[1];
$playername = $argv[2];
echo update_stats_for_player($playerid, $playername);
?>
