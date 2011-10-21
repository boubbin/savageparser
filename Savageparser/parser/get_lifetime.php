<?php

error_reporting(E_ALL);

global $cookie_stats;
$cookie_stats = '/tmp/savage_stats_cookie.txt';

function advice_start_page_for_playerid($playerid) {
        $matches  = get_number_of_actionplayer_matches_for_playerid_for_period($playerid, 0);
        $matches += get_number_of_commander_matches_for_playerid_for_period($playerid, 0);
        if ($matches >= 14) {
                $startpage = round($matches/14);
                $url1 = "index.php?action=get_lifetime&playerid=$playerid&page=$startpage&confirmed=1";
                $url2 = "index.php?action=get_lifetime&playerid=$playerid&page=1&confirmed=1";
                $text = 'Hey, it looks like i already got matches from this guy!\nThis means that we should start getting matches further than page one to speed up the process!\n\nI estimated that those matches will reach to page '.$startpage.' on his stats\n\nShould we start from page '.$startpage.'?';
                js_on_confirm_href($text, $url1, $url2);
        }
        flush();
}

function interruption_inform_tell_page($page) {
        $text = 'Oops! Error with http-request, let me renew the cookies for you next time you run this updater!\nJust make sure you start from the page we this process ended, ok?\n\nThe page was: '.$page;
        jsalert($text);
}

function curl_get_lifetime_stats_for_playerid_starting_from_page($playerid, $playername, $page = 1) {
        $time = microtime(true);
        global $cookie_stats;
        global $sql_connections;
        if (!is_numeric($playerid)) { die("trol-olol?"); }
        $playername = playerid_to_playername($playerid);
        if ($page == 1 && !isset($_GET['confirmed'])) { advice_start_page_for_playerid($playerid); }
        $sql_connections = 0;
        $tab = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        $nojob = 0;
        $job = 0;
        $jobend = 4;
        $lastsame = 0;
        $breakon  = 10;
        $id1 = 0;
        $id2 = 10000;
        $new_player_matches = 0;
        $new_commander_matches = 0;
        $checked_matches = 0;
        $laptime = microtime(true);
        $lapsleep = 0;
        $matchnum = 0;
        check_cookies_and_renew_em_if_needed();
        echo "<li>Startup, hold on this <u>WILL take a while</u>";
        echo "<li>Getting 14 matches (one page) will take approximately 60seconds, you do the math..";
        flush();
        while ($page) {
                $laptime = microtime(true);
                if (isset($lastmatch)) {
                        if (isset($lastlaplastmatch)) {
                                if ($lastlaplastmatch == $lastmatch) {
                                        $lastsame++;
                                        echo "Lastmatch has been the same for ($lastsame/$breakon) rounds.. breaking on $breakon<br>";
                                        flush();
                                        if ($lastsame == $breakon) { break; }
                                } else { $lastlaplastmatch = $lastmatch; $lastsame = 0; }
                        } else { $lastlaplastmatch = $lastmatch; }
                }
                echo "<br><br><li>Page ($page), work balance: <b>$nojob</b>, work last round (total): </b>$job</b>, num of mysql-queries: </b>$sql_connections</b><br>";
                $sql_connections = 0;
                echo "$tab Getting player (<font color=#0052FF>$playername</font>) matches on page $page ";
                $matchids = get_player_matchids_on_page($playerid, $page);
                echo "(".count($matchids)."pcs)<br>";
                flush();
                foreach ($matchids as $matchid => $date) {
                        $matchnum++;
                        $job = 0;
                        $matchtime = microtime(true);
                        echo "$tab $tab ($matchnum) Matchid: <font color=#00FF33>$matchid</font>"; flush();
                        $individual_stats = "";
                        $full_match_stats = "";
                        $sleep1 = 0;
                        $id1++;
                        $id2++;
                        $checked_matches++;
                        flush();
                        if (match_exists_for_playerid($matchid, $playerid)!="1") { echo_loading_player(1, $id1); flush(); $individual_stats = get_player_stats_for_match($playerid, $matchid); echo_loading_player(0, $id1); flush(); sleep($sleep1); }
                        if (!match_exists($matchid)) { echo_loading_match(1, $id2); flush(); $full_match_stats = get_match_stats_for_match($matchid); flush(); sleep($sleep1); echo_loading_match(0, $id2); flush(); }
                        if (is_array($individual_stats) && empty($individual_stats) && empty($full_match_stats)) { interruption_inform_tell_page($page); return false; }

                        if (empty($individual_stats)==FALSE) {
                                $job++; $nojob = 0;
                                if (!isset($individual_stats[16])) {
                                        if (is_valid_commander_stat_info($individual_stats)) {
                                                $new_commander_matches++;
                                                save_commander_stats_for_match($playerid, $matchid, $individual_stats);
                                        } else {
                                                echo " Found invalid individual match stats for match $matchid (Commander), omitting..<br>";
                                        }
                                } else {
                                        if (is_valid_player_stat_info($individual_stats)) {
                                                $new_player_matches++;
                                                save_player_stats_for_match($playerid, $matchid, $individual_stats);
                                        } else {
                                                echo " Found invalid individual match stats for match $matchid (Player), omitting..<br>";
                                        }
                                }
                        }
                        flush();
                        if (empty($full_match_stats)==FALSE) {
                                $job++; $nojob = 0;
                                // print_r($full_match_stats);
                                if (is_valid_match_stat_info($full_match_stats)) {
                                        save_match_stats($full_match_stats);
                                } else {
                                        echo " Found invalid match stats for match $matchid (Full stats), omitting..<br>";
                                }
                        }
                        flush();
                        if ($job == 0) {
                                $matchtime = round(microtime(true) - $matchtime, 2);
                                echo " <b>($matchtime seconds)</b><br>";
                        } else if ($job == 1) {
                                $lapsleep += $sleep1;
                                $matchtime = round(microtime(true) - $matchtime - $sleep1, 2);
                                echo " ($matchtime seconds + (sleeped: ".($sleep1)."))<br>";
                        } else {
                                $lapsleep += $sleep1+$sleep1;
                                $matchtime = round(microtime(true) - $matchtime - ($sleep1+$sleep1), 2);
                                echo " ($matchtime seconds + (sleeped: ".($sleep1+$sleep1)."))<br>";
                        }
                        if ($job == 0) { $nojob++; }
                        $lastmatch = $matchid;
                        flush();
                }
                $page++;
                $laptime = round(microtime(true) - $laptime - $lapsleep,2);
                echo "$tab $tab <b>Laptime: $laptime seconds + (sleeped: $lapsleep)</b><br>";
                flush();
        }
        $time = round(microtime(true) - $time,2);
        echo "<br><br><b>Went through ($checked_matches) matches:<br>";
        echo "Found ($new_player_matches) new matches for actionplayer<br>";
        echo "Found ($new_commander_matches) new matches for commander<br>";
        echo "Total time of the operation: $time seconds<br>";
        //curl_close($curl);
        echo "Closed curl</b><br>";
        flush();
        return true;
}
?>