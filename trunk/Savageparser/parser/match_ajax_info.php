<?php
require('../get_functions.php');
require('../misc_functions.php');
require('mysql_functions.php');
if (!isset($_GET['playerid']) || !isset($_GET['matchid'])) { echo "Error while loading match stats"; return 0; }
$playerid = $_GET['playerid'];
$matchid = $_GET['matchid'];

function echo_commander_match_stats_for_playerid_on_match($playerid, $matchid) {
        $map    = get_mapname_for_matchid($matchid);
        $mapimg = get_mapname_for_map(strtolower($map));
        $result = get_commander_match_stats_for_playerid_on_match($playerid, $matchid);
        $side   = get_commander_playside_for_playerid_on_match($playerid, $matchid);
        $wl     = get_winloss_string_for_side_on_matchid($side, $matchid);
        $date   = date("d/m/Y", get_date_of_matchid($matchid));
        foreach ($result as $key => $value) { $$key = $value; }
        $playername	= playerid_to_playername($playerid);
        $exp		= number_format($exp);
        $healed   	= number_format($healed);
        $gold		= number_format($gold);
        $repair		= number_format($repaired);
        $dmg 		= number_format($dmg);
        $width          = 35;
        echo "
                <font size=1>Commander stats on <a href=index.php?action=match_info&matchid=$matchid>$matchid</a> for <b>$playername</b><br>
                <b>$playername</b> played on $side and $wl<br>
                Map was $map and it was played $date<br>
                <img src=../images/maps/$mapimg width=150 height=150><br><br>
                <table style=\"border:solid 1px;\">
                <tr><td><img src=../images/stats/hours.png width=$width></td><td><font size=2>Time played<br><font size=2><b>$duration</b></td>
                <td><img src=../images/stats/exp.png width=$width></td><td><font size=2>Experience<br><font size=2><b>$exp</td></tr>
                <tr><td><img src=../images/stats/orders.png width=$width></td><td><font size=2>Orders per min<br><font size=2><b>$orders</td>
                <td><img src=../images/stats/healed.png width=$width></td><td><font size=2>Healed<br><font size=2><b>$healed</td></tr>
                <tr><td><img src=../images/stats/gold.png width=$width></td><td><font size=2>Gold earned<br><font size=2><b>$gold</td>
                <td><img src=../images/stats/kills.png width=$width></td><td><font size=2>Kills<br><font size=2><b>$kills</td></tr>
                <tr><td><img src=../images/stats/buffs.png width=$width></td><td><font size=2>Buffs cast<br><font size=2><b>$buffs</td>
                <td><img src=../images/stats/debuffs.png width=$width></td><td><font size=2>Debuffs Cast<br><font size=2><b>$debuffs</td></tr>
                <tr><td><img src=../images/stats/repair.png width=$width></td><td><font size=2>Buildings erected<br><font size=2><b>$erected</td>
                <td><img src=../images/stats/repair.png width=$width></td><td><font size=2>Repaired<br><font size=2><b>$repaired</td></tr>
                <tr><td><img src=../images/stats/dmg.png width=$width></td><td><font size=2>Player damage<br><font size=2><b>$dmg</td>
                <td><img src=../images/stats/razed.png width=$width></td><td><font size=2>Buildings razed<br><font size=2><b>$razed</td></tr>
                </table>
        ";
        echo "<br>Click inside this window to close it";
}

function echo_actionplayer_match_stats_for_playerid_on_match($playerid, $matchid) {
        $map    = get_mapname_for_matchid($matchid);
        $mapimg = get_mapname_for_map(strtolower($map));
        $result = get_actionplayer_match_stats_for_playerid_on_match($playerid, $matchid);
        $side   = get_actionplayer_playside_for_playerid_on_match($playerid, $matchid);
        $winner = get_winner_for_matchid($matchid);
        $date   = date("d/m/Y", get_date_of_matchid($matchid));
        foreach ($result as $key => $value) { $$key = $value; }
        $playername	= playerid_to_playername($playerid);
        $sf             = number_format($exp/$time,2);
        $exp		= number_format($exp);
        $healed 	= number_format($healed);
        $gold		= number_format($gold);
        $repair		= number_format($repair);
        $dmg 		= number_format($dmg);
        $bd		= number_format($bd);
        $kd             = number_format($kd, 2);
        $width          = 35;
        echo "
                <font size=1>Actionplayer stats on <a href=index.php?action=match_info&matchid=$matchid>$matchid</a> for <b>$playername</b><br>
                <b>$playername</b> played on $side, $winner won<br>
                Map was $map and it was played $date<br>
                <img src=../images/maps/$mapimg width=150 height=150><br><br>
                <table style=\"border:solid 1px;\">
                <tr><td><img src=../images/stats/hours.png width=$width></td><td><font size=2>Time played<br><font size=2><b>$duration</b></td>
                <td><img src=../images/stats/exp.png width=$width></td><td><font size=2>Exp/min<br><font size=2><b>$sf</td></tr>
                <tr><td><img src=../images/stats/exp.png width=$width></td><td><font size=2>Experience<br><font size=2><b>$exp</td>
                <td><img src=../images/stats/dmg.png width=$width></td><td><font size=2>Player damage<br><font size=2><b>$dmg</td></tr>
                <tr><td><img src=../images/stats/kills.png width=$width></td><td><font size=2>Kills<br><font size=2><b>$kills</td>
                <td><img src=../images/stats/deaths.png width=$width></td><td><font size=2>Deaths<br><font size=2><b>$deaths</td></tr>
                <tr><td><img src=../images/stats/kd.png width=$width></td><td><font size=2>KD-ratio<br><font size=2><b>$kd</td>
                <td><img src=../images/stats/souls.png width=$width></td><td><font size=2>Souls spent<br><font size=2><b>$souls</td></tr>
                <tr><td><img src=../images/stats/healed.png width=$width></td><td><font size=2>Healed<br><font size=2><b>$healed</td>
                <td><img src=../images/stats/res.png width=$width></td><td><font size=2>Resurrections<br><font size=2><b>$res</td></tr>
                <tr><td><img src=../images/stats/gold.png width=$width></td><td><font size=2>Gold gained<br><font size=2><b>$gold</td>
                <td><img src=../images/stats/bd.png width=$width></td><td><font size=2>Building damage<br><font size=2><b>$bd</td></tr>
                <tr><td><img src=../images/stats/razed.png width=$width></td><td><font size=2>Buildings razed<br><font size=2><b>$razed</td>
                <td><img src=../images/stats/repair.png width=$width></td><td><font size=2>Repaired<br><font size=2><b>$repair</td></tr>
                <tr><td><img src=../images/stats/assists.png width=$width></td><td><font size=2>Assists<br><font size=2><b>$assists</td>
                <td><img src=../images/stats/npc.png width=$width></td><td><font size=2>NPC kills<br><font size=2><b>$npc</td></tr>

                </table>
                </center>
        ";
        echo "<br>Click inside this window to close it";
}
$position = $_GET['position'];
if ($position == "player" && is_actionplayer_match_for_playerid($matchid, $playerid)) {
        echo_actionplayer_match_stats_for_playerid_on_match($playerid, $matchid);
} else {
        echo_commander_match_stats_for_playerid_on_match($playerid, $matchid);
}
?>
