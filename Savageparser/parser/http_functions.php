<?php

function curl_get_match_stats_for_match($curl, $matchid) {
        $host = "www.savage2replays.com";
	$url = "www.savage2replays.com/match_replay.php?mid=$matchid";
	$html = use_curl_object($curl, $host, $url);
        if ($html === false) { return false; }
	$stats = get_match_stats_from_html($html);
	return $stats;
}

function curl_get_player_stats_for_match($curl, $playerid, $matchid) {
        $host = 'www.savage2.com';
	$url = "www.savage2.com/en/get_player_match_stats.php?aid=$playerid&mid=$matchid";
	$html = use_curl_object($curl, "www.savage2.com", $url);
        if ($html === false) { return false; }
	$stats = get_player_match_stats_from_html($html);
        if (count($stats)==2) { return array(); }
	if ($stats['14'] == "00:00:00") {
		$url = "www.savage2.com/en/get_comm_match_stats.php?aid=$playerid&mid=$matchid";
		$html = use_curl_object($curl, $host, $url);
                if ($html === false) { return false; }
		$stats = get_player_match_stats_from_html($html);
                if (count($stats) == 2) { return array(); }
	}
	return $stats;
}

function curl_get_playername_for_playerid($curl, $playerid) {
        $host = 'www.savage2.com';
	$url = "http://www.savage2.com/en/player_stats.php?id=$playerid";
	$html = use_curl_object($curl, $host, $url);
        if ($html === false) { return false; }
	$playername = get_playername_from_html($html);
	return $playername;
}

function curl_get_player_matchids_on_page($curl, $playerid, $page) {
        $host = 'www.savage2.com';
	$url = "www.savage2.com/en/get_match_list.php?aid=$playerid&page=$page";
	$html = use_curl_object($curl, $host, $url);
        if ($html === false) { return false; }
	$matches = get_14_matches_from_html($html);
	return $matches;
}

function use_curl_object($curl = '', $host = '', $url = '') {
 	global $cookie_stats;
        if (empty($curl)) {
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
                curl_setopt($curl, CURLOPT_TIMEOUT, 10);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_stats);
                curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_stats);
                //echo "<b>...curl initialized</b><br>";
                return $curl;
        } else {
                if (0) { // for quick debug
                        $rand = rand(1,100);
                        $file = fopen("/tmp/".time()."-$rand.txt", 'w');
                        curl_setopt($curl, CURLOPT_STDERR , $file);
                        curl_setopt($curl, CURLOPT_VERBOSE , 1);
                }
                $headers = array(
                        "Host: $host",
                        'User-Agent: Mozilla/5.0 (X11; Linux i686) AppleWebKit/534.25 (KHTML, like Gecko) Ubuntu/10.04 Chromium/12.0.705.0 Chrome/12.0.705.0 Safari/534.25',
                        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                        'Accept-Encoding: deflate',
                        'Accept-Language: fi-FI,fi;q=0.8,en-US;q=0.6,en;q=0.4',
                        'Accept-Charset: ISO-8859-1;q=0.7,*;q=0.3\r\n'
                );
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($curl, CURLOPT_URL, $url);
                $result = curl_exec($curl);
                if ($result === false) {
                        echo "<font color=red>HTTP-request failed: ".curl_error($curl).", error code: ".curl_errno($curl)."</font>";
                        return false;
                }
                $html = explode("\n", $result);
                if (isset($file)) { fclose($file); }
                return $html;
        }
}

function curl_get_request($host, $url, $cookie = array(), $referer = "0", $post = array(), $follow = 0) {
	global $cookie_stats;
	global $cookie_replays;
        if ($referer != "0") {
                $headers = array(
                        "Host: $host",
                        'User-Agent: Mozilla/5.0 (X11; Linux i686) AppleWebKit/534.25 (KHTML, like Gecko) Ubuntu/10.04 Chromium/12.0.705.0 Chrome/12.0.705.0 Safari/534.25',
                        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                        'Accept-Encoding: deflate',
                        'Accept-Language: fi-FI,fi;q=0.8,en-US;q=0.6,en;q=0.4',
                        'Accept-Charset: ISO-8859-1;q=0.7,*;q=0.3',
                        'Referer: http://www.savage2replays.com/match_replay.php?mid=29733'
                );
	} else {
                $headers = array(
                        "Host: $host",
                        'User-Agent: Mozilla/5.0 (X11; Linux i686) AppleWebKit/534.25 (KHTML, like Gecko) Ubuntu/10.04 Chromium/12.0.705.0 Chrome/12.0.705.0 Safari/534.25',
                        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                        'Accept-Encoding: deflate',
                        'Accept-Language: fi-FI,fi;q=0.8,en-US;q=0.6,en;q=0.4',
                        'Accept-Charset: ISO-8859-1;q=0.7,*;q=0.3'
                );
        }
	$fields_string = '';
	$curl = curl_init();
	if (!empty($post)) {
		foreach($post as $key => $value) {
			$fields_string .= $key.'='.$value.'&';
		}
		rtrim($fields_string,'&');
		curl_setopt($curl, CURLOPT_POST,count($post));
		curl_setopt($curl, CURLOPT_POSTFIELDS,$fields_string);
	}
	if ($follow) {
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
	}
	if (1) { // for quick debug
		$rand = rand(1,100);
		$file = fopen("/tmp/".time()."-$rand.txt", 'w');
		curl_setopt($curl, CURLOPT_STDERR , $file);
		curl_setopt($curl, CURLOPT_VERBOSE , 1);
	}
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($curl, CURLOPT_TIMEOUT, 10);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie);
	curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie);
	curl_setopt($curl, CURLOPT_URL, $url);
        $result = curl_exec($curl);
        if ($result === false) {
                echo "<font color=red>HTTP-request failed: ".curl_error($curl).", error code: ".curl_errno($curl)."</font>";
                return false;
        }
	$html = explode("\n", $result);
	curl_close($curl);
	if (isset($file)) { fclose($file); }
	return $html;
}


?>