<?php

global $cookie_stats;
$cookie_stats = '/tmp/savage_stats_cookie.txt';

require('../parser/index.php');
require('../get_functions.php');
require('../print_page_functions.php');
require('../misc_functions.php');

function get_missing_playername_for_playerids($players) {
        check_cookies_and_renew_em_if_needed();
	$result = get_playerids_which_needs_playername($players);
        if (mysql_num_rows($result) == 0) { echo "Nothing to update, all up-to-date!\n"; return 1; }
        $curl = use_curl_object();
	while ($row = mysql_fetch_assoc($result)) {
		$playerid = $row['playerid'];
		$playername = curl_get_playername_for_playerid($curl, $playerid);
                if ($playername === false) {
                        echo "Savage2-website is propably out of reach, try again after few minutes..\n";
                        return 0;
                } else {
                        if (!player_exists($playerid)) {
                                add_new_player($playerid, $playername);
                                echo "Added playername for playerid $playerid: $playername\n";
                        } else {
                                echo "For some reason player $playerid exists already with name: \n".playerid_to_playername($playerid)."";
                        }
                }
        }
	return 1;
}
$i = 0;
while (!get_missing_playername_for_playerids(10)) { $i++; if ($i >= 3) { break; } }

?>