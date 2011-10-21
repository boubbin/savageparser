<?php

require('/home/boubbino/public_html/savage/essentials.php');

function deploy_stats_for_matchdid($matchid, $q = 0) {
        if (!is_valid_matchid($matchid, $q)) { if (!$q) echo "Deploy failed: not valid matchid"; return 0; }
        if (match_exists($matchid)) { if (!$q) echo "Deploy failed: match exists"; return 1; }
        if (!stats_file_exists_for_matchid($matchid)) { if (!$q) echo "Deploy failed: no such file";  return 0; }
        if (!is_valid_stats_info_for_existing_stats_file($matchid, $q)) { return 1; }
        if (!deploy_match_stats_from_existing_stats_file_for_valid_matchid($matchid, $q)) { return 0; }
        if (!$q) echo "OK";
        return 1;
}

function deploy_match_stats_from_existing_stats_file_for_valid_matchid($matchid, $q = 0) {
        $stats                  = get_match_stats_from_stats_file($matchid);
        $player_stats           = $stats['player_stats'];
        $commander_stats        = $stats['commander_stats'];
        $team_stats             = $stats['team'];
        $matchid                = $stats['match_id'];

        $player_stats_query     = form_player_stats_save_query($matchid, $player_stats);
        $commander_stats_query  = form_commander_stats_save_query($matchid, $commander_stats);
        $match_stats_query      = form_match_stats_query($matchid, $stats);

        open_mysql_connection();
        $result1 = mysql_query($player_stats_query);
        $result2 = mysql_query($commander_stats_query);
        $result3 = mysql_query($match_stats_query);
        mysql_close();

        if (!$result1) { if (!$q) echo "         Failed query: $player_stats_query"; }
        if (!$result2) { if (!$q) echo "         Failed query: $commander_stats_query"; }
        if (!$result3) { if (!$q) echo "         Failed query: $match_stats_query"; }
        if ($result1 || $result2 || $result3) { return true; }
        return false;
}

function form_player_stats_save_query($matchid, $player_stats) {
        $query = "INSERT INTO stats
                (id, matchid, playerid, exp, dmg, kills, assists, souls, npc, healed, res, gold, repair, bd, razed, deaths, kd, duration, sf, team, ip)
                VALUES\t\t";
        $first = 0;
        foreach ($player_stats as $entry) {
                $str            = '';
                $playerid       = $entry['account_id'];
                $clan           = $entry['clan_name'];
                $team           = $entry['team'];
                $exp            = $entry['exp'];
                $kills          = $entry['kills'];
                $deaths         = $entry['deaths'];
                $assists        = $entry['assists'];
                $souls          = $entry['souls'];
                $razed          = $entry['razed'];
                $dmg            = $entry['pdmg'];
                $bd             = $entry['bdmg'];
                $npc            = $entry['npc'];
                $healed         = $entry['hp_healed'];
                $res            = $entry['res'];
                $gold           = $entry['gold'];
                $repair         = $entry['hp_repaired'];
                $duration       = "SEC_TO_TIME($entry[secs])";
                $sf             = $entry['sf'];
                $ip             = $entry['ip'];
                if ($ip == "N/A" || empty($ip)) { $ip = "NULL"; }
                if ($deaths == 0 || $kills == 0) { $kd = 'NULL'; }
                else { $kd = $kills/$deaths; }
                if (!$first) { $first = 1; }
                else { $str .= ",\t\t"; }
                $str    .= "(NULL, '$matchid', '$playerid', '$exp', '$dmg', '$kills', '$assists', '$souls', '$npc', '$healed', '$res', '$gold', '$repair', '$bd', '$razed', '$deaths', '$kd', $duration, '$sf', '$team', INET_ATON('$ip'))";
                $query  .= $str;
        }
        return $query;
}
function form_commander_stats_save_query($matchid, $player_stats) {
        $query = "INSERT INTO commanderstats
                (id, matchid, playerid, exp, orders, gold, erected, repaired, razed, buffs, healed, debuffs, dmg, kills, duration, team, ip)
                VALUES\t\t";
        $first = 0;
        foreach ($player_stats as $entry) {
                $str            = '';
                $playerid       = $entry['account_id'];
                $clan           = $entry['clan_name'];
                $team           = $entry['c_team'];
                $exp            = $entry['c_exp'];
                $kills          = $entry['c_kills'];
                $razed          = $entry['c_razed'];
                $dmg            = $entry['c_pdmg'];
                $healed         = $entry['c_hp_healed'];
                $gold           = $entry['c_gold'];
                $duration       = "SEC_TO_TIME($entry[c_secs])";
                $ip             = $entry['ip'];
                $erected        = $entry['c_builds'];
                $buffs          = $entry['c_debuffs'];
                $debuffs        = $entry['c_buffs'];
                $orders         = $entry['c_orders'];
                if ($ip == "N/A" || empty($ip)) { $ip = "NULL"; }
                if (!$first) { $first = 1; }
                else { $str .= ",\t\t"; }
                $str    .= "(NULL, '$matchid', '$playerid', '$exp', '$orders', '$gold', '$erected', '0', '$razed', '$buffs', '$healed', '$debuffs', '$dmg', '$kills', $duration, '$team', INET_ATON('$ip'))";
                $query  .= $str;
        }
        return $query;
}
function form_match_stats_query($matchid, $stats) {
        $date           = $stats['date'];
        $server         = $stats['svr_id'];
        $map            = $stats['map'];
        $duration       = $stats['time'];
        $winner         = $stats['winner'];
        $sf_team1       = $stats['team']['1']['avg_sf'];
        $sf_team2       = $stats['team']['2']['avg_sf'];
        $dmg_team1      = 0;
        $kills_team1    = 0;
        $assists_team1  = 0;
        $souls_team1    = 0;
        $healed_team1   = 0;
        $res_team1      = 0;
        $gold_team1     = 0;
        $repaired_team1 = 0;
        $npc_team1      = 0;
        $bd_team1       = 0;
        $razed_team1    = 0;
        $deaths_team1   = 0;
        $dmg_team2      = 0;
        $kills_team2    = 0;
        $assists_team2  = 0;
        $souls_team2    = 0;
        $healed_team2   = 0;
        $res_team2      = 0;
        $gold_team2     = 0;
        $repaired_team2 = 0;
        $npc_team2      = 0;
        $bd_team2       = 0;
        $razed_team2    = 0;
        $deaths_team2   = 0;
        foreach ($stats['player_stats'] as $stat) {
                $team = $stat['team'];
                if ($team == 1) {
                        $dmg_team1      += $stat['pdmg'];
                        $kills_team1    += $stat['kills'];
                        $assists_team1  += $stat['assists'];
                        $souls_team1    += $stat['souls'];
                        $healed_team1   += $stat['hp_healed'];
                        $res_team1      += $stat['res'];
                        $gold_team1     += $stat['gold'];
                        $repaired_team1 += $stat['hp_repaired'];
                        $npc_team1      += $stat['npc'];
                        $bd_team1       += $stat['bdmg'];
                        $razed_team1    += $stat['razed'];
                        $deaths_team1   += $stat['deaths'];
                }  else {
                        $dmg_team2      += $stat['pdmg'];
                        $kills_team2    += $stat['kills'];
                        $assists_team2  += $stat['assists'];
                        $souls_team2    += $stat['souls'];
                        $healed_team2   += $stat['hp_healed'];
                        $res_team2      += $stat['res'];
                        $gold_team2     += $stat['gold'];
                        $repaired_team2 += $stat['hp_repaired'];
                        $npc_team2      += $stat['npc'];
                        $bd_team2       += $stat['bdmg'];
                        $razed_team2    += $stat['razed'];
                        $deaths_team2   += $stat['deaths'];
                }
        }
        if ($kills_team1 == 0 || $deaths_team1 == 0) { $kd_team1 = "NULL"; }
        else { $kd_team1 = $kills_team1/$deaths_team1; }
        if ($kills_team2 == 0 || $deaths_team2 == 0) { $kd_team2 = "NULL"; }
        else { $kd_team2 = $kills_team2/$deaths_team2; }
        $query = "INSERT INTO matches
        (matchid, date, server, duration, map, winner, sf_team1, sf_team2, player_dmg_team1, kills_team1, assists_team1, souls_team1, healed_team1, res_team1, gold_team1, repaired_team1, npc_team1, bd_team1, razed_team1, deaths_team1, kd_team1, player_dmg_team2, kills_team2, assists_team2, souls_team2, healed_team2, res_team2, gold_team2, repaired_team2, npc_team2, bd_team2, razed_team2, deaths_team2, kd_team2)
        VALUES
        ('$matchid', '$date', '$server', '$duration', '$map', '$winner', '$sf_team1', '$sf_team2', '$dmg_team1', '$kills_team1', '$assists_team1', '$souls_team1', '$healed_team1', '$res_team1', '$gold_team1', '$repaired_team1', '$npc_team1', '$bd_team1', '$razed_team1', '$deaths_team1', '$kd_team1', '$dmg_team2', '$kills_team2', '$assists_team2', '$souls_team2', '$healed_team2', '$res_team2', '$gold_team2', '$repaired_team2', '$npc_team2', '$bd_team2', '$razed_team2', '$deaths_team2', '$kd_team2')";
        return $query;
}
function is_valid_stats_info_for_existing_stats_file($matchid, $q = 0) {
        $stats = get_match_stats_from_stats_file($matchid);
        $playercount = count($stats['player_stats']);
        $winner      = $stats['winner'];
        if ($playercount < 1 || $playercount > 100) { if (!$q) echo "Not valid stat-info (pcount: $playercount)"; return false; }
        if (empty($stats['date'])) { if (!$q) echo "Not valid stat-info (empty date)";return false; }
        if (empty($stats['time'])) { if (!$q) echo "Not valid stat-info (empty time)";return false; }
        if ($winner != 1 && $winner != 2) { if (!$q) echo "Not valid stat-info (winner: $winner)";return false; }
        return true;
}
if (isset($_GET['q'])) { $q = 1; }
else { $q = 0; }
flush();
$handle = opendir('/home/boubbino/public_html/savage/stats_files/files');
while (false !== ($file = readdir($handle))) {
        if (preg_match('/event/', $file) || !is_numeric($file)) { continue; }
        echo "\n";
        if (!$q) echo "file: $file: ";
        if (deploy_stats_for_matchdid($file, $q) == 1) {
                copy("/home/boubbino/public_html/savage/stats_files/files/$file", "/home/boubbino/public_html/savage/stats_files/bu_files/$file");
                unlink("/home/boubbino/public_html/savage/stats_files/files/$file");
        }
}
if ($q) {
        if (isset($_GET['url'])) { js_href($_GET['url']); }
        else { js_href("../index.php"); }
}


?>