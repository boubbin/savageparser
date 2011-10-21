<?php

function get_playtime_for_playerid_for_period($playerid, $period) {
        $query = "SELECT (SUM(TIME_TO_SEC(s.duration))/60/60) as time FROM matches m, stats s WHERE m.matchid = s.matchid AND m.date >= '$period' AND s.playerid = '$playerid'";
        open_mysql_connection();
        $result = mysql_query($query);
        $result = mysql_fetch_assoc($result);
        mysql_close();
        return $result['time'];
}

function get_players_who_killed_playerid_for_period($playerid, $period) {
        $query = "SELECT COUNT(*) as times, playername FROM eventlog e, matches m, players p WHERE e.target_playerid = '$playerid' AND e.action = 'killed' AND performer_playerid NOT IN (0, $playerid) AND e.matchid = m.matchid AND m.date >= '$period'  AND e.performer_playerid = p.playerid GROUP BY playername ORDER BY 1 DESC LIMIT 10";
        open_mysql_connection();
        $result = mysql_query($query);
        mysql_close();
        return $result;
}

function get_killed_players_for_playerid_for_period($playerid, $period) {
        $query = "SELECT COUNT(*) as times, playername FROM eventlog e, matches m, players p WHERE e.performer_playerid = '$playerid' AND e.action = 'killed' AND target_playerid NOT IN (0, $playerid) AND e.matchid = m.matchid AND m.date >= '$period' AND e.target_playerid = p.playerid GROUP BY playername ORDER BY 1 DESC LIMIT 10";
        open_mysql_connection();
        $result = mysql_query($query);
        mysql_close();
        return $result;
}

function get_spawned_unit_for_playerid_for_period($playerid, $period) {
        $query = "SELECT COUNT(*) as times, target_unit FROM eventlog e, matches m WHERE e.performer_playerid = '$playerid' AND e.action = 'spawn' AND e.matchid = m.matchid AND m.date >= '$period' GROUP BY e.target_unit ORDER BY 1 DESC";
        open_mysql_connection();
        $result = mysql_query($query);
        mysql_close();
        return $result;
}

function get_destroyed_gadgets_for_playerid_for_period($playerid, $period) {
        $query = "SELECT ".get_sql_select_transpose_for_killed_gadgets()." FROM eventlog e, matches m WHERE e.performer_playerid = '$playerid' AND e.matchid = m.matchid AND m.date >= '$period'";
        open_mysql_connection();
        $result = mysql_query($query);
        mysql_close();
        return $result;
}
function get_destroyed_buildings_for_playerid_for_period($playerid, $period) {
        $query = "SELECT ".get_sql_select_transpose_for_killed_buildings()." FROM eventlog e, matches m WHERE e.performer_playerid = '$playerid' AND e.matchid = m.matchid AND m.date >= '$period'";
        open_mysql_connection();
        $result = mysql_query($query);
        mysql_close();
        return $result;
}

function get_destroyed_units_for_playerid_for_period($playerid, $period) {
        $query = "SELECT ".get_sql_select_transpose_for_killed_units()." FROM eventlog e, matches m WHERE e.performer_playerid = '$playerid' AND e.matchid = m.matchid AND m.date >= '$period'";
        open_mysql_connection();
        $result = mysql_query($query);
        mysql_close();
        return $result;
}


function get_ingame_connections_from_all_countries() {
        $query = "SELECT * FROM lolw ORDER BY connections DESC";
        open_mysql_connection();
        $result = mysql_query($query);
        mysql_close();
        return $result;
}

function get_average_sf_for_team_for_period($team, $period) {
        $query = "SELECT AVG(sf_team$team) FROM matches WHERE date > '$period';";
        open_mysql_connection();
        $result = mysql_query($query);
        $result = mysql_fetch_row($result);
        return $result[0];
}

function add_new_server($id, $name, $ip, $desc) {
        $query = "INSERT INTO servers (id, name, ip, description) VALUES ('$id', '$name', INET_ATON('$ip'), '$desc')";
        open_mysql_connection();
        mysql_query($query);
        mysql_close();
}

function server_exists_for($id) {
        $query = "SELECT id FROM servers WHERE id = '$id' LIMIT 1";
        open_mysql_connection();
        $result = mysql_query($query);
        mysql_close();                
        if (mysql_num_rows($result)==1) { return true; }
        return false;
}

function add_server($id, $name, $ip, $desc) {
        if (!server_exists_for($id)) { add_new_server($id, $name, $ip, $desc); }
}

function get_matching_matches_stats_for_attributes($_POST) {
        $where = " WHERE 1 ";
        $query = "SELECT * FROM matches m";
        open_mysql_connection();
        foreach ($_POST as $key => $value) { if (!is_array($value)) $$key = mysql_real_escape_string($value); }
        mysql_close();
        if ($map != "Any")       { $where .= " AND m.map = '$map'"; }
        if ($server != "Any")    { $where .= " AND m.server = '$server'"; }
        if ($winner != "Any")    { $where .= " AND m.winner = '$winner'"; }
        if (!empty($date))       {
                list($month, $day, $year) = explode("/", $date);
                $date = mktime(0, 0, 0, $month, $day, $year);
                $where .= "AND date > '$date'";
        }
        if (!empty($length) && $length_operator != "Any")      {
                if ($length_operator == "lt") { $where .= " AND TIME_TO_SEC(m.duration)/60 < '$length'"; }
                if ($length_operator == "gt") { $where .= " AND TIME_TO_SEC(m.duration)/60 > '$length'"; }
        }
        if (!empty($sf_team1) && $sf_operator_team1 != "Any")      {
                if ($sf_operator_team1 == "lt") { $where .= " AND m.sf_team1 < '$sf_team1'";; }
                if ($sf_operator_team1 == "gt") { $where .= " AND m.sf_team1 > '$sf_team1'"; }
        }
        if (!empty($sf_team2) && $sf_operator_team2 != "Any")      {
                if ($sf_operator_team2 == "lt") { $where .= " AND m.sf_team2 < '$sf_team2'"; }
                if ($sf_operator_team2 == "gt") { $where .= " AND m.sf_team2 > '$sf_team2'"; }
        }
        if (isset($_POST['playerids'])) {
                $sub_query = get_duplicate_matches_query_for_playerids_array($_POST['playerids']);
                $where .= " AND matchid IN ($sub_query)";
        }
        $query .= $where;
        $query .= " ORDER BY matchid DESC";
        open_mysql_connection();
        $result = mysql_query($query);
        return $result;
}

function get_different_server_names() {
        $query = "SELECT id, name as server FROM servers WHERE ip IS NOT NULL AND name IS NOT NULL ORDER BY id";
        open_mysql_connection();
        $result = mysql_query($query);
        return $result;
}

function get_different_mapnames_and_playcounts() {
        $query = "SELECT DISTINCT(map), COUNT(*) as playtimes FROM matches GROUP BY map ORDER BY playtimes DESC;";
        open_mysql_connection();
        $result = mysql_query($query);
        return $result;
}

function get_individual_match_stats_for_match_for_team($matchid, $team = 1) {
        $query = "SELECT exp/(TIME_TO_SEC(s.duration)/60) as gamesf, s.* FROM stats s, matches m WHERE s.matchid = m.matchid AND s.matchid = '$matchid' AND s.team = $team ORDER BY gamesf DESC";
        open_mysql_connection();
        $result = mysql_query($query);
        return $result;        
}

function get_overall_match_info_for_matchid($matchid) {
        $query = "SELECT matchid, date, map, duration, IF(winner = 1, 'Humans', 'Beasts') as winner, sf_team1, sf_team2 FROM matches WHERE matchid = '$matchid'";
        open_mysql_connection();
        $result = mysql_query($query);
        return $result;
}

function get_overall_match_stats_for_matchid_for_team($matchid, $team = 1) {
        $columns = "IF($team = 1, 'Humans', 'Beasts') as team, sf_team$team, player_dmg_team$team, kills_team$team, assists_team$team, souls_team$team, healed_team$team, res_team$team, gold_team$team, repaired_team$team, npc_team$team, bd_team$team, razed_team$team, deaths_team$team, kd_team$team";
        $query = "SELECT $columns FROM matches WHERE matchid = '$matchid'";
        open_mysql_connection();
        $result = mysql_query($query);
        return $result;
}

function get_last_matches_for_playerid($playerid, $matches = 10) {
        $query = "SELECT sub.matchid, sub.str, sub.sf, map, duration, date FROM matches, ((SELECT 'Action player' as str, matchid, exp/(TIME_TO_SEC(duration)/60) as sf FROM stats WHERE playerid = '$playerid' ORDER BY matchid DESC LIMIT $matches)
                  UNION
                  (SELECT 'Commander' as str, matchid, exp/(TIME_TO_SEC(duration)/60) as sf FROM commanderstats WHERE playerid = '$playerid' ORDER BY matchid DESC LIMIT $matches)) as sub
                  WHERE sub.matchid = matches.matchid
                  ORDER by matchid DESC LIMIT $matches";
        open_mysql_connection();
        $result = mysql_query($query);
        return $result;
}
function get_last_matches($matches = 10) {
        $query = "SELECT matchid, date, map, duration, IF(winner = 1, 'Humans', 'Beasts') as winner, sf_team1, sf_team2 FROM matches ORDER by matchid DESC LIMIT $matches";
        open_mysql_connection();
        $result = mysql_query($query);
        return $result;
}

function get_alt_accounts_for_ip($ip) {
        $query = "SELECT DISTINCT(stats.playerid), playername FROM stats, players WHERE ip = INET_ATON('$ip') AND stats.playerid = players.playerid AND stats.playerid != '$_SESSION[playerid]' UNION SELECT DISTINCT(commanderstats.playerid), playername FROM commanderstats, players WHERE ip = INET_ATON('$ip') AND commanderstats.playerid = players.playerid AND commanderstats.playerid != '$_SESSION[playerid]'";
        //echo $query;
        open_mysql_connection();
        $result = mysql_query($query);
        return $result;
}

function get_10_most_popular_profiles() {
        $query = "SELECT u.playername, p.playerid, visits FROM profiles p , players u WHERE u.playerid = p.playerid ORDER BY visits DESC LIMIT 10";
        open_mysql_connection();
        $result = mysql_query($query);
        return $result;
}

function profile_exists_for_playerid($playerid) {
        $query = "SELECT playerid FROM profiles WHERE playerid = '$playerid' LIMIT 1";
        open_mysql_connection();
        $result = mysql_query($query);
        $result = mysql_fetch_row($result);
        if ($playerid == $result[0]) { return true; }
        return false;
}

function increase_visit_count_for_profile($playerid) {
        if (profile_exists_for_playerid($playerid)) {
                $query = "UPDATE profiles SET visits = visits+1 WHERE playerid = '$playerid' LIMIT 1;";
        } else {
                $query = "INSERT INTO profiles (playerid, visits) VALUES ('$playerid', '1')";
        }
        open_mysql_connection();
        $result = mysql_query($query);
	mysql_close();
}

function get_3_newest_users() {
        $query = "SELECT playerid, playername FROM users ORDER BY id DESC LIMIT 3";
        open_mysql_connection();
        $result = mysql_query($query);
        mysql_close();
        return $result;
}

function get_10_recently_active_users() {
        $query = "SELECT playerid, playername, lastlog FROM users WHERE lastlog > 0 ORDER BY lastlog DESC LIMIT 10";
        open_mysql_connection();
        $result = mysql_query($query);
        mysql_close();
        return $result;
}

function update_lastsf_for_playerid($playerid, $sf) {
        $query = "UPDATE users SET lastsf = '$sf' WHERE playerid = '$playerid' LIMIT 1;";
        open_mysql_connection();
        $result = mysql_query($query);
	mysql_close();  
}

function update_lastlog_for_playerid($playerid, $time) {
        $query = "UPDATE users SET lastlog = '$time' WHERE playerid = '$playerid' LIMIT 1;";
        open_mysql_connection();
        $result = mysql_query($query);
	mysql_close();
}

function get_users_playername_for_playerid($playerid) {
        $query = "SELECT playername FROM users WHERE playerid = '$playerid'";
        open_mysql_connection();
        $result = mysql_query($query);
        $result = mysql_fetch_row($result);
        mysql_close();
        return $result[0];
}
function get_users_playerid_for_playername($playername) {
        $query = "SELECT playerid FROM users WHERE playername = '$playername'";
        open_mysql_connection();
        $result = mysql_query($query);
        $result = mysql_fetch_row($result);
        mysql_close();
        return $result[0];
}
function get_users_userlevel_for_playerid($playerid) {
        $query = "SELECT userlevel FROM users WHERE playerid = '$playerid'";
        open_mysql_connection();
        $result = mysql_query($query);
        $result = mysql_fetch_row($result);
        mysql_close();
        return $result[0];
}
function get_users_lastlog_for_playerid($playerid) {
        $query = "SELECT lastlog FROM users WHERE playerid = '$playerid'";
        open_mysql_connection();
        $result = mysql_query($query);
        $result = mysql_fetch_row($result);
        mysql_close();
        return $result[0];
}
function get_users_lastsf_for_playerid($playerid) {
        $query = "SELECT lastsf FROM users WHERE playerid = '$playerid'";
        open_mysql_connection();
        $result = mysql_query($query);
        $result = mysql_fetch_row($result);
        mysql_close();
        return $result[0];
}
function get_users_lastip_for_playerid($playerid) {
        $query = "SELECT ip FROM users WHERE playerid = '$playerid'";
        open_mysql_connection();
        $result = mysql_query($query);
        $result = mysql_fetch_row($result);
        mysql_close();
        return $result[0];
}

function is_valid_password_for_playername($password, $playername) {
        $password .= get_salt();
        $password  = SHA1($password);
        $query = "SELECT playername, password FROM users WHERE playername = '$playername'";
        open_mysql_connection();
        $result = mysql_query($query);
        if (mysql_num_rows($result) == 0) { return 0; }
        $result = mysql_fetch_row($result);
        mysql_close();
        $mysql_playername = $result[0];
        $mysql_password   = $result[1];
        if ((strtolower($mysql_playername) == strtolower($playername)) && ($mysql_password == $password)) { return 1; }
        return 2;
}

function user_exists($playerid) {
        $query = "SELECT playerid FROM users WHERE playerid = '$playerid'";
        open_mysql_connection();
        $result = mysql_query($query);
        $result = mysql_fetch_row($result);
        mysql_close();
        if ($playerid == $result[0]) { return true; }
        return false;      
}

function add_new_user($playerid, $playername, $password, $level, $lastlogin, $lastsf, $ip) {
        $password .= get_salt();
        $password  = SHA1($password);
        $query1 = "INSERT INTO users (id, playerid, playername, password, userlevel, lastlog, lastsf, ip) VALUES ('NULL', '$playerid', '$playername', '$password', '$level', '$lastlogin', '$lastsf', INET_ATON('$ip'));";
        $query2 = "INSERT INTO profiles (playerid, visits) VALUES ('$playerid', '0');";
        open_mysql_connection();
        $result1 = mysql_query($query1);
        if (!$result1 || mysql_affected_rows() != 1) { return false; }
        $result2 = mysql_query($query2);
        if (!$result2 && mysql_affected_rows() != 1) { return false; }
        return true;
}

function get_playtime_for_commander_in_range($playerid, $period1, $period2) {
        $query = "SELECT (SUM(TIME_TO_SEC(c.duration))/60/60) as time FROM matches m, commanderstats c WHERE m.matchid = c.matchid AND m.date >= '$period1' AND m.date < '$period2' AND c.playerid = '$playerid'";
        open_mysql_connection();
        $result = mysql_query($query);
        $result = mysql_fetch_row($result);
        mysql_close();
        return $result[0];
}
function get_num_of_matches_for_commander_in_range($playerid, $period1, $period2) {
        $query = "SELECT COUNT(c.matchid) as matches FROM matches m, commanderstats c WHERE m.matchid = c.matchid AND m.date >= '$period1' AND m.date < '$period2' AND c.playerid = '$playerid'";
        open_mysql_connection();
        $result = mysql_query($query);
        $result = mysql_fetch_row($result);
        mysql_close();
        return $result[0];
}
function get_number_of_players_ingame_in_period($period) {
        $query = "SELECT COUNT(players) FROM (SELECT playerid as players FROM stats, matches WHERE stats.matchid = matches.matchid AND matches.date >= '$period' UNION ALL SELECT playerid as players FROM commanderstats, matches WHERE commanderstats.matchid = matches.matchid AND matches.date >= '$period') as sub";
        open_mysql_connection();
        $result = mysql_query($query);
        $result1 = mysql_fetch_row($result);
        $result2 = mysql_fetch_row($result);
        mysql_close();
        return $result1[0]+$result2[0];
}

function get_number_of_unique_players_ingame_in_period($period) {
        $query = "SELECT COUNT(DISTINCT(players)) FROM (SELECT DISTINCT(playerid) as players FROM stats, matches WHERE stats.matchid = matches.matchid AND matches.date >= '$period' UNION SELECT DISTINCT(playerid) as players FROM commanderstats, matches WHERE commanderstats.matchid = matches.matchid AND matches.date >= '$period') as sub";
        open_mysql_connection();
        $result = mysql_query($query);
        $result1 = mysql_fetch_row($result);
        mysql_close();
        return $result1[0];
}

function get_number_of_unique_players_ingame_daily_for_period($period) {
        $query = "SELECT FROM_UNIXTIME(date, \"%W\") as weekday, FROM_UNIXTIME(date, \"%m/%d\") as day, COUNT(DISTINCT(playerid)) as unique_players FROM stats, matches WHERE stats.matchid = matches.matchid AND matches.date >= '$period' GROUP BY day ORDER BY day LIMIT 1, 100;";
        open_mysql_connection();
        $result = mysql_query($query);
        mysql_close();
        return $result;
}

function get_number_of_unique_and_nonunique_players_ingame_daily_for_period($period) {
        $query = "SELECT FROM_UNIXTIME(date, \"%W\") as weekday, FROM_UNIXTIME(date, \"%m/%d\") as day, COUNT(stats.playerid) as players, COUNT(DISTINCT(stats.playerid)) as unique_players FROM stats, matches WHERE stats.matchid = matches.matchid AND matches.date >= '$period' GROUP BY day ORDER BY day LIMIT 1, 100;";
        open_mysql_connection();
        $result = mysql_query($query);
        mysql_close();
        return $result;
}


function contest_is_recent($id) {
        $period = time();
        $query = "SELECT 1 FROM contests WHERE id = '$id' AND end <= '$period';";
        open_mysql_connection();
        $result = mysql_query($query);
        $result = mysql_fetch_row($result);
        mysql_close();
        if (!$result) { return false; }
        return true;
}

function contest_is_upcoming($id) {
        $period = time();
        $query = "SELECT 1 FROM contests WHERE id = '$id' AND start >= '$period';";
        open_mysql_connection();
        $result = mysql_query($query);
        $result = mysql_fetch_row($result);
        mysql_close();
        if (!$result) { return false; }
        return true;
}

function get_contestors_results_in_contest_for_period_with_not_enough_playtime_dynamic_queries($id, $period1, $period2, $playtime = 5) {
        $functionid  = get_functionid_for_contestid($id);
        $query       = get_corresponding_mysql_query_for_contest_functionid($functionid, 0);
        eval("\$query = \"$query\";");
        open_mysql_connection();
        $result = mysql_query($query);
        mysql_close();
        return $result;
}
/*
 * Use dunamic queries function instead this is soooo static
 *
function get_contestors_results_in_contest_for_period_with_not_enough_playtime($id, $period1, $period2, $playtime = 5) {
        $column     = 'stat';
        $functionid = get_functionid_for_contestid($id);
        $stat       = get_corresponding_mysql_column_for_contest_functionid($functionid);
        $query      = "SELECT p.playername, SUM(s.$stat)/(SUM(TIME_TO_SEC(s.duration))/60) as $column, COUNT(*) as matches, SUM(TIME_TO_SEC(s.duration))/60/60 as time FROM contestors c, stats s, matches m, players p WHERE c.contestid = '$id' AND s.playerid = c.playerid AND p.playerid = c.playerid AND m.matchid = s.matchid AND m.date >= '$period1' AND m.date <= '$period2' GROUP BY s.playerid HAVING time < '$playtime' ORDER BY $column DESC";
        open_mysql_connection();
        $result = mysql_query($query);
        mysql_close();
        return $result;
}
*/
function get_contestors_results_in_contest_for_period_with_enough_playtime_dynamic_queries($id, $period1, $period2, $playtime = 5) {
        $functionid  = get_functionid_for_contestid($id);
        $query       = get_corresponding_mysql_query_for_contest_functionid($functionid, 1);
        eval("\$query = \"$query\";");
        //echo $query;
        open_mysql_connection();
        $result = mysql_query($query);
        mysql_close();
        return $result;
}
/*
 * Use dunamic queries function instead this is soooo static
 *
function get_contestors_results_in_contest_for_period_with_enough_playtime($id, $period1, $period2, $playtime = 5) {
        $column = 'stat';
        $functionid = get_functionid_for_contestid($id);
        $stat       = get_corresponding_mysql_column_for_contest_functionid($functionid);
        $query  = "SELECT p.playername, SUM(s.$stat)/(SUM(TIME_TO_SEC(s.duration))/60) as $column, COUNT(*) as matches, SUM(TIME_TO_SEC(s.duration))/60/60 as time FROM contestors c, stats s, matches m, players p WHERE c.contestid = '$id' AND s.playerid = c.playerid AND p.playerid = c.playerid AND m.matchid = s.matchid AND m.date >= '$period1' AND m.date <= '$period2' GROUP BY s.playerid HAVING time >= '$playtime' ORDER BY $column DESC";
        open_mysql_connection();
        $result = mysql_query($query);
        mysql_close();
        return $result;
}
*/
function get_contest_info_for_contestid($id) {
        $query = "SELECT * FROM contests WHERE id = '$id';";
        open_mysql_connection();
        $result = mysql_query($query);
        mysql_close();
        return $result;
}

function get_functionid_for_contestid($id) {
        $query = "SELECT functionid FROM contests WHERE id = '$id';";
        open_mysql_connection();
        $result = mysql_query($query);
        $result = mysql_fetch_row($result);
        mysql_close();
        return $result[0];
}

function get_recent_contests() {
        $period = time();
        $query = "SELECT * FROM contests WHERE end < '$period';";
        open_mysql_connection();
        $result = mysql_query($query);
        mysql_close();
        return $result;
}

function get_upcoming_contests() {
        $period = time();
        $query = "SELECT * FROM contests WHERE start > '$period';";
        open_mysql_connection();
        $result = mysql_query($query);
        mysql_close();
        return $result;
}

function get_ongoing_contests_without_upcoming() {
        $period = time();
        $query = "SELECT * FROM contests WHERE end > '$period' AND start <= '$period';";
        open_mysql_connection();
        $result = mysql_query($query);
        mysql_close();
        return $result;        
}

function get_ongoing_contests() {
        $period = time();
        $query = "SELECT * FROM contests WHERE end > '$period';";
        open_mysql_connection();
        $result = mysql_query($query);
        mysql_close();
        return $result;
}

function get_contestors_in_contest($id) {
        $query = "SELECT * FROM contestors WHERE contestid = '$id'";
        open_mysql_connection();
        $result = mysql_query($query);
        mysql_close();
        return $result;
}

function contest_for_functionid_exists($functionid) {
        $time = time();
        $query = "SELECT id FROM contests WHERE functionid = '$functionid' AND end > '$time' LIMIT 1;";
        open_mysql_connection();
        $result = mysql_query($query);
        $result = mysql_num_rows($result);
        mysql_close();
        if ($result == 1) { return true; }
        return false;
}

function contest_for_id_exists($contest_id) {
        $query = "SELECT id FROM contests WHERE id = '$contest_id' LIMIT 1;";
        open_mysql_connection();
        $result = mysql_query($query);
        $result = mysql_num_rows($result);
        mysql_close();
        if ($result == 1) { return true; }
        return false;
}

function contest_for_name_exists($contest_name) {
        $contest_name = mysql_escape($contest_name);
        $query = "SELECT id FROM contests WHERE name = '$contest_name' LIMIT 1;";
        open_mysql_connection();
        $result = mysql_query($query);
        $result = mysql_num_rows($result);
        mysql_close();
        if ($result == 1) { return true; }
        return false;
}

function contestor_exists_for_contest($playerid, $contestid) {
        $query = "SELECT playerid FROM contestsors WHERE playerid = '$playerid' AND contestid = '$contestid' LIMIT 1;";
        open_mysql_connection();
        $result = mysql_query($query);
        $result = mysql_affected_rows();
        mysql_close();
        if ($result == 1) { return true; }
        return false;
}

function add_new_contest($contest_name, $contest_description, $contest_function, $contest_start, $contest_end, $contest_playtime) {
        $query = "INSERT INTO contests (id, name, description, functionid, start, end, min_playtime) VALUES (NULL, '$contest_name', '$contest_description', '$contest_function', '$contest_start', '$contest_end', '$contest_playtime')";
        open_mysql_connection();
        mysql_query($query);
        $result = mysql_affected_rows();
        mysql_close();
        if ($result == 1) { return true; }
        return false;
}

function add_contestor_for_contest($playerid, $contestid) {
        $query = "INSERT INTO contestors (playerid, contestid) VALUES ('$playerid', '$contestid')";
        open_mysql_connection();
        mysql_query($query);
        $result = mysql_affected_rows();
        mysql_close();
        if ($result == 1) { return true; }
        return false;
}

function get_number_of_unique_playerids_in_period($period) {
        $query = "SELECT COUNT(playerid) FROM (SELECT playerid FROM (SELECT s.playerid FROM stats s, matches m WHERE s.matchid = m.matchid AND m.date > '$period' UNION SELECT s.playerid FROM commanderstats s, matches m WHERE s.matchid = m.matchid AND m.date > '$period') as sub ORDER BY playerid DESC) sub;";
        open_mysql_connection();
        $result = mysql_query($query);
        $result = mysql_fetch_row($result);
        mysql_close();
        return $result[0];
}

function get_longest_commander_winstreak_for_playerid($playerid) {
        $query = "SELECT COUNT(*) as streak FROM (SELECT @r := @r + (COALESCE(@won, won) <> won) AS series, @won := won as win FROM( SELECT @r := 0, @won := NULL) vars, (SELECT c.playerid, m.matchid, IF(winner = team, 1, 0) as won FROM matches m, commanderstats c WHERE c.playerid = '$playerid' AND m.matchid = c.matchid AND c.duration > 0 AND m.date >= '0' ORDER BY matchid) as sub) as sub WHERE win = 1 GROUP BY series ORDER BY streak DESC LIMIT 1";
        open_mysql_connection();
        $result = mysql_query($query);
        $result = mysql_fetch_row($result);
        mysql_close();
        return $result[0];
}

function get_highest_winstreak_for_players_in_commander_contest_in_date_range($id, $period1, $period2) {
        return;
}

function get_ongoing_commander_winstreak_for_playerid($playerid) {
        $query1 = "SELECT IF(winner = team, 1, 0) as won FROM matches m, commanderstats c WHERE c.playerid = '$playerid' AND m.matchid = c.matchid AND c.duration > 0 AND m.date >= '0' ORDER BY m.matchid DESC LIMIT 1;";
        $query2 = "SELECT COUNT(*) as streak FROM (SELECT @r := @r + (COALESCE(@won, won) <> won) AS series, @won := won as win FROM( SELECT @r := 0, @won := NULL) vars, (SELECT c.playerid, m.matchid, IF(winner = team, 1, 0) as won FROM matches m, commanderstats c WHERE c.playerid = '$playerid' AND m.matchid = c.matchid AND c.duration > 0 AND m.date >= '0' ORDER BY matchid) as sub) as sub WHERE win = 1 GROUP BY series DESC LIMIT 1;";
        open_mysql_connection();
        $result1 = mysql_query($query1);
        $result1 = mysql_fetch_row($result1);
        if ($result1[0]==0) { mysql_close(); return 0; }
        $result2 = mysql_query($query2);
        $result2 = mysql_fetch_row($result2);
        mysql_close();
        return $result2[0];
}

function get_number_of_chat_messages_for_period($period) {
        $query = "SELECT COUNT(userid) FROM chat WHERE date >= '$period'";
        open_mysql_connection();
        $result = mysql_query($query);
        $result = mysql_fetch_row($result);
        mysql_close();
        return $result[0];
}

function get_last_x_chat_messages($x) {
        $query = "SELECT * FROM (SELECT * FROM chat ORDER BY date DESC LIMIT 0, $x) as sub ORDER BY sub.date ASC";
        open_mysql_connection();
        $result = mysql_query($query);
        mysql_close();
        return $result;       
}

function get_last_x_chat_posters_userids_for_period($x, $period) {
        $query = "SELECT userid FROM chat WHERE date >= '$period' ORDER BY date DESC LIMIT 0, $x";
        open_mysql_connection();
        $result = mysql_query($query);
        mysql_close();
        return $result;
}

function save_chat_message_from_userid_at_time($message, $userid, $time) {
        $query = "INSERT INTO chat (userid, date, content) VALUES ('$userid','$time', '$message');";
        open_mysql_connection();
        $result = mysql_query($query);
        mysql_close();
}

function get_distictive_number_of_actionplayer_matches_clan_has_played_for_period($clan, $period) {
        $query = "SELECT COUNT(DISTINCT(matches.matchid)) as total FROM stats, matches, players WHERE stats.playerid = players.playerid AND playername LIKE '$clan%' AND stats.duration > 0 AND date >= '$period' AND stats.matchid = matches.matchid AND matches.duration > 0 ORDER BY stats.matchid ASC";
        open_mysql_connection();
        $result = mysql_query($query);
        $result = mysql_fetch_row($result);
        mysql_close();
        return $result[0];
}

function get_sf_components_for_clan_for_period($clan, $period) {
        $query  = "SELECT matches.matchid, playername, exp, (TIME_TO_SEC(stats.duration)/60) as time FROM stats, matches, players WHERE stats.playerid = players.playerid AND playername LIKE '$clan%' AND stats.duration > 0 AND date >= '$period' AND stats.matchid = matches.matchid AND matches.duration > 0 ORDER BY stats.matchid ASC;";
        open_mysql_connection();
        $result = mysql_query($query);
        mysql_close();
        return $result;
}

function get_playernames_for_clan($clan) {
        $query = "SELECT playername FROM players WHERE playername LIKE '$clan%';";
        open_mysql_connection();
        $result = mysql_query($query);
	mysql_close();
        return $result;
}

function get_playernames_for_players_from_clan_who_has_been_playing_within_period($clan, $period) {
        $query = "SELECT DISTINCT(playername) FROM players, stats, matches WHERE playername LIKE '$clan%' AND players.playerid = stats.playerid AND matches.matchid = stats.matchid AND matches.date >= '$period' AND stats.duration != 0;";
        open_mysql_connection();
        $result = mysql_query($query);
	mysql_close();
        return $result;
}


function get_total_commander_winloss_ratio_for_playerid($playerid) {
        return "0.5";
}

function update_playername_for_playerid($playerid, $playername) {
        $query = "UPDATE players SET playername = '$playername' WHERE playerid = '$playerid' LIMIT 1;";
        open_mysql_connection();
        $result = mysql_query($query);
	mysql_close();
        return $result;
}

function get_number_of_matches_played_for_period_grouped_by_weekday($period) {
        $query = "SELECT weekday, count(weekday) as matches FROM (SELECT date, FROM_UNIXTIME(date, '%W') as weekday FROM matches WHERE date >= '$period') as sub GROUP BY sub.weekday ORDER BY matches DESC LIMIT 0, 7;";
        open_mysql_connection();
        $result = mysql_query($query);
	mysql_close();
        return $result;
}

function get_number_of_daily_matches_for_period($period) {
        $query = "SELECT day, count(day) as matches FROM (SELECT date, FROM_UNIXTIME(date, '%d.%m') as day FROM matches WHERE date >= '$period') as sub GROUP BY sub.day ORDER BY date ASC LIMIT 1, 300;";
        open_mysql_connection();
        $result = mysql_query($query);
	mysql_close();
        return $result;
}

function get_playerids_which_needs_playername($players) {
        $query = "SELECT DISTINCT(playerid) FROM stats WHERE duration > 0 AND playerid NOT IN (SELECT playerid FROM players) ORDER BY playerid ASC LIMIT $players;";
        open_mysql_connection();
        $result = mysql_query($query);
	mysql_close();
        return $result;
}

function get_number_of_random_vip_players_which_needs_an_update($update) {
        $lastupdate = time() - (60*60*4);
 	$query = "SELECT playerid FROM vip WHERE vip.lastupdate <= '$lastupdate' AND vip.lastupdate IS NOT NULL ORDER BY vip.lastupdate ASC LIMIT 0, $update";
        echo $query;
        open_mysql_connection();
        $result = mysql_query($query);
	mysql_close();
        return $result;

}

function get_number_of_actionplayer_matches_for_all_vips_for_period($period) {
        $query = "SELECT count(s.matchid) matches, p.playername FROM matches m, stats s, players p
                 WHERE m.matchid = s.matchid AND m.date > '$period' AND
                 p.playerid = s.playerid
                 GROUP BY playername
                 ORDER BY matches DESC LIMIT 0, 10";
        open_mysql_connection();
        $result = mysql_query($query);
	mysql_close();
        return $result;
}

function get_total_commander_exp_for_playerid($playerid) {
 	$query = "SELECT SUM(exp) as exp FROM commanderstats c WHERE c.playerid = '$playerid' AND c.duration != 0'";
        open_mysql_connection();
        $result = mysql_query($query);
	mysql_close();
	$result = mysql_fetch_row($result);  
        return $result[0];
}

function get_actionplayer_stats_for_clan_for_period($clan, $period) {
	$query = "SELECT SUM(exp) as exp, (SUM(stats.exp)/(SUM(TIME_TO_SEC(stats.duration))/60)) as sf, SUM(kills)/SUM(deaths) as kd , SUM(kills) as kills, SUM(deaths) as deaths, SUM(assists) as assists, SUM(bd) as bd, SUM(gold) as gold, SUM(healed) as healed, SUM(TIME_TO_SEC(stats.duration)/60/60) as duration, SUM(souls) as souls, SUM(dmg) as dmg, SUM(res) as res, SUM(razed) as razed, SUM(npc) as npc, SUM(repair) as repair FROM stats, players,matches WHERE stats.playerid = players.playerid AND players.playername LIKE '$clan%' AND stats.duration != 0 AND stats.matchid = matches.matchid AND matches.date >= '$period'";
        open_mysql_connection();
        $result = mysql_query($query);
	mysql_close();
	return mysql_fetch_assoc($result);

}

function get_actionplayer_stats_for_playerid_for_period($playerid, $period) {
	$query = "SELECT SUM(exp) as exp, (SUM(stats.exp)/(SUM(TIME_TO_SEC(stats.duration))/60)) as sf, SUM(kills)/SUM(deaths) as kd , SUM(kills) as kills, SUM(deaths) as deaths, SUM(assists) as assists, SUM(bd) as bd, SUM(gold) as gold, SUM(healed) as healed, SUM(TIME_TO_SEC(stats.duration)/60/60) as duration, SUM(souls) as souls, SUM(dmg) as dmg, SUM(res) as res, SUM(razed) as razed, SUM(npc) as npc, SUM(repair) as repair FROM stats, players,matches WHERE stats.playerid = players.playerid AND stats.playerid = '$playerid' AND stats.duration != 0 AND stats.matchid = matches.matchid AND matches.date >= '$period'";
        open_mysql_connection();
        $result = mysql_query($query);
	mysql_close();
	return mysql_fetch_assoc($result);
}
function get_commander_stats_for_playerid_for_period($playerid, $period) {
        $query = "SELECT SUM(razed) as razed, SUM(exp) as exp, SUM(repaired) as repaired, SUM(kills) as kills, SUM(erected) as erected, SUM(debuffs) as debuffs, SUM(buffs) as buffs, SUM(orders) as orders, SUM(gold) as gold, SUM(healed) as healed, SUM(dmg) as dmg, SUM(TIME_TO_SEC(commanderstats.duration)/60/60) as duration FROM commanderstats, players,matches WHERE commanderstats.playerid = players.playerid AND commanderstats.playerid = '$playerid' AND commanderstats.duration != 0 AND commanderstats.matchid = matches.matchid AND matches.date >= '$period'";
        open_mysql_connection();
        $result = mysql_query($query);
	mysql_close();
	return mysql_fetch_assoc($result);
}


function no_games_in_30days_for_playerid($playerid) {
        return false;
        $query = "SELECT (COUNT(s.matchid)+COUNT(c.matchid)) as matches FROM stats, commanderstats WHERE playerid = '$playerid'";
        echo $query;
        open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
        $result = mysql_fetch_row($result);
        if ($result==0) { return true; }
        return false;
}

function get_winner_for_matchid($matchid) {
        $query = "SELECT winner FROM matches WHERE matchid = '$matchid'";
        open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
        $result = mysql_fetch_array($result);
        if ($result[0] == 1) { return "Humans"; }
        return "Beasts";

}

function get_date_of_matchid($matchid) {
        $query = "SELECT date FROM matches WHERE matchid = '$matchid'";
        open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
        $result = mysql_fetch_array($result);
        return $result[0];
}

function get_winloss_string_for_side_on_matchid($side, $matchid) {
        $query = "SELECT winner FROM matches WHERE matchid = '$matchid'";
        open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
        $result = mysql_fetch_array($result);
        if ($result[0] == 1 && $side == "Humans") { return "won"; }
        if ($result[0] == 2 && $side == "Beasts") { return "won"; }
        return "lost";
}

function get_actionplayer_playside_for_playerid_on_match($playerid, $matchid) {
        $query = "SELECT s.team as team FROM stats s, matches m WHERE s.playerid = '$playerid' AND s.matchid = '$matchid' AND s.matchid = m.matchid";
        open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
        $result = mysql_fetch_array($result);
        if ($result[0]==1) { return "Humans"; }
        return "Beasts";
}

function get_commander_playside_for_playerid_on_match($playerid, $matchid) {
        $query = "SELECT s.team as team FROM commanderstats s, matches m WHERE s.playerid = '$playerid' AND s.matchid = '$matchid' AND s.matchid = m.matchid";
        open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
        $result = mysql_fetch_array($result);
        if ($result[0]==1) { return "Humans"; }
        return "Beasts";
}

function get_mapname_for_matchid($matchid) {
        $query = "SELECT map FROM matches WHERE matchid = '$matchid'";
        open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
        $result = mysql_fetch_array($result);
        return $result[0];
}

function get_actionplayer_date_range_of_matches_in_period_for_clan($clan, $period) {
        $query = "SELECT MIN(date), MAX(date) FROM stats s, matches m, players p WHERE s.matchid = m.matchid AND m.date >= $period AND s.playerid = p.playerid AND p.playername LIKE '$clan%'";
        open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
        $result = mysql_fetch_array($result);
        return $result;
}

function get_actionplayer_date_range_of_matches_in_period_for_playerid($playerid, $period = 0) {
        $query = "SELECT MIN(date), MAX(date) FROM stats s, matches m WHERE s.matchid = m.matchid AND m.date >= '$period' AND s.playerid = '$playerid' AND s.duration != '0'";
        open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
        $result = mysql_fetch_array($result);
        return $result;
}

function get_commander_date_range_of_matches_in_period_for_playerid($playerid, $period = 0) {
        $query = "SELECT MIN(date), MAX(date) FROM commanderstats s, matches m WHERE s.matchid = m.matchid AND m.date >= $period AND s.playerid = '$playerid'";
        open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
        $result = mysql_fetch_array($result);
        return $result;
}

function is_commander_match_for_playerid($matchid, $playerid) {
        $query = "SELECT matchid FROM commanderstats WHERE playerid = '$playerid' AND matchid = '$matchid' LIMIT 1";
        open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
        $result = mysql_fetch_row($result);
        if (empty($result)) { return FALSE; }
        return true;
}

function is_actionplayer_match_for_playerid($matchid, $playerid) {
        $query = "SELECT matchid FROM stats WHERE playerid = '$playerid' AND matchid = '$matchid' LIMIT 1";
        open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
        $result = mysql_fetch_row($result);
        if (empty($result)) { return false; }
        return true;
}

function get_commander_match_stats_for_playerid_on_match($playerid, $matchid) {
        $query = "SELECT * FROM commanderstats WHERE playerid = '$playerid' AND matchid = '$matchid' LIMIT 1";
        open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
        return mysql_fetch_assoc($result);
}

function get_actionplayer_match_stats_for_playerid_on_match($playerid, $matchid) {
        $query = "SELECT *, TIME_TO_SEC(duration)/60 as time FROM stats WHERE playerid = '$playerid' AND matchid = '$matchid' LIMIT 1";
        open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
        return mysql_fetch_assoc($result);
}

function remove_vip_if_exists($playerid, $playername) {
        $query = "DELETE FROM vip WHERE playerid = '$playerid' LIMIT 1";
        open_mysql_connection();
	mysql_query($query);
	mysql_close();
}

function get_matchids_for_period($period = 0) {
        if ($period != 0) { $period = get_period(); }
        $query = "SELECT matchid FROM matches WHERE date > '$period' ORDER BY matchid DESC";
	open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
        return $result;
}

function get_30days_playmates_for_playerid($playerid) {
        $period = get_period();
        $query = "SELECT playername, sub.times FROM players, (SELECT COUNT(stats.playerid) as times, stats.playerid FROM stats, (SELECT s.matchid FROM matches m, stats s WHERE m.matchid = s.matchid AND s.playerid = '$playerid' AND m.date >= '$period' AND team != 0) as sub WHERE sub.matchid = stats.matchid GROUP BY stats.playerid ORDER BY times DESC LIMIT 1,15) as sub WHERE players.playerid = sub.playerid";
        // echo $query;
        open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
        return $result;    
}


function get_number_of_matches_won_by_team($team, $period = 0) {
        if ($period != 0) { $period = get_period(); }
        $query = "SELECT count(winner) as kpl FROM matches WHERE date > '$period' AND winner = '$team' GROUP BY winner";
	open_mysql_connection();
	$result = mysql_fetch_row(mysql_query($query));
	mysql_close();
        return $result[0];
}

function get_teams_played_for_commander_playerid_for_period($playerid, $period) {
        $period = get_period();
        $query = "SELECT count(team) as times, team FROM matches m, commanderstats s WHERE m.matchid = s.matchid AND s.playerid = '$playerid' AND m.date >= '$period' AND team != 0 GROUP by team";
	open_mysql_connection();
        //echo $query;
	$result = mysql_query($query);
        $team1 = mysql_fetch_assoc($result);
        $team2 = mysql_fetch_assoc($result);
	mysql_close();
        return "$team1[times]-$team2[times]";
}

function get_30days_teams_played_for_playerid($playerid) {
        $period = get_period();
        $query = "SELECT count(team) as team, team FROM matches m, stats s WHERE m.matchid = s.matchid AND s.playerid = '$playerid' AND m.date >= '$period' AND team != 0 GROUP by team";
	open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
        return $result;
}

function user_is_logged_on_website($userid) {
        $query = "SELECT lastlogin FROM users WHERE userid = '$userid';";
 	open_mysql_connection();
        $result = mysql_query($query);
        mysql_close();
        $time = mysql_fetch_row($result);
        $time = time() - $time[0];
        if ($time > 1800) { return FALSE; } else { return TRUE; }
}

function update_lastlogin_for_username($username) {
	$time = time();
	$query = "UPDATE users SET lastlogin = '$time' WHERE username = '$username';";
	open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
}

function add_new_website_user_record($username, $playerid, $level, $referal) {
	$userid = md5($username);
	$query = "INSERT INTO users (username, userid, playerid, info, level) VALUES ('$username', '$userid', '$playerid', '$referal', '$level');";
	open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
}

function add_new_vip_record_for_playerid($playerid) {
	$query = "INSERT INTO vip (playerid) VALUES ('$playerid');";
	open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
}

function playerid_is_vip($playerid) {
	$query = "SELECT 1 FROM vip WHERE playerid = '$playerid' LIMIT 0, 1";
	open_mysql_connection();
	$result = mysql_query($query);
	$result = mysql_num_rows($result);
	mysql_close();
	if ($result == 1) { return TRUE; }
	return FALSE;
}

function website_user_exists($userid) {
	$query = "SELECT 1 FROM users WHERE userid = '$userid' LIMIT 0, 1";
	open_mysql_connection();
	$result = mysql_query($query);
	$result = mysql_num_rows($result);
	mysql_close();
	if ($result == 1) { return TRUE; }
	return FALSE;
}

function get_matching_playernames_for_sring($string) {
	$username = mysql_escape($string);
        $query = "SELECT * FROM players p WHERE playername = '$username' UNION SELECT * FROM players WHERE playername LIKE '%$username%' LIMIT 0, 33";
	open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
	return $result;
}

function get_matching_playernames_for_sring_without_already_added_users($string) {
	$username = mysql_escape($string);
	$query = "SELECT playername, playerid FROM players p WHERE playername = '$username' OR playername LIKE '%$username%' AND p.playerid NOT IN (SELECT playerid FROM users) LIMIT 0, 10";
	open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
	return $result;
}

function get_matching_playernames_for_sring_without_already_added_vips($string) {
	$username = mysql_escape($string);
	$query = "SELECT playername, playerid FROM players p WHERE playername = '$username' OR playername LIKE '%$username%' AND p.playerid NOT IN (SELECT playerid FROM vip) LIMIT 0, 30";
	open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
	return $result;
}

function get_all_user_all_info() {
	$query = "SELECT * FROM users";
	open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
	return $result;
}

function get_lastlogin_for_userid($userid) {
 	$query = "SELECT lastlogin FROM users WHERE MD5(LOWER(username)) = '$userid'";
	open_mysql_connection();
	$result = mysql_query($query);
	$result = mysql_fetch_row($result);
	mysql_close();
	return $result[0];
}

function get_userlevel_for_userid($userid) {
	$query = "SELECT level FROM users WHERE MD5(LOWER(username)) = '$userid' OR MD5(username) = '$userid'";
	open_mysql_connection();
	$result = mysql_query($query);
	$result = mysql_fetch_row($result);
	mysql_close();
	return $result[0];
}


function get_which_side_playerid_plays_the_most($playerid) {
	$query = "SELECT playerid, playername, COUNT(sub.team), sub.team FROM (SELECT sub.playerid, playername, matchid, team FROM players JOIN (SELECT playerid, matchid, team FROM stats WHERE playerid = '194827' AND team NOT LIKE 0 UNION SELECT playerid, matchid, team FROM commanderstats WHERE playerid = '194827' AND team NOT LIKE 0) as sub ON sub.playerid = players.playerid) as sub GROUP BY team";
	open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
	return $result;
}

function password_set_for_username($username) {
	$query = "SELECT username FROM users WHERE username = '$username' AND password IS NOT NULL";
	open_mysql_connection();
	$result = mysql_query($query);
	$result = mysql_num_rows($result);
	mysql_close();
	if ($result == 1) { return TRUE; }
	return FALSE;
}

function referal_exists($username, $referal) {
	$query = "SELECT info FROM users WHERE username = '$username' AND password IS NULL";
	open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
	$refer = mysql_fetch_assoc($result);
	if ($refer['info'] == $referal) { return TRUE; }
	return FALSE;
}

function activate_account($username, $password) {
	$userid = md5($username);
	$time = time();
	$query = "UPDATE users SET password = '$password', info = '' WHERE userid = '$userid' LIMIT 1";
	open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
	if ($result) { return TRUE; }
	return FALSE;
}

function username_exists($username) {
	$query = "SELECT password FROM users WHERE username = '$username'";
	open_mysql_connection();
	$result = mysql_query($query);
	$result = mysql_num_rows($result);
	mysql_close();
	if ($result == 1) { return TRUE; }
	return FALSE;
}


function correct_password_for_username($username, $password) {
	$query = "SELECT password FROM users WHERE LOWER(username) = LOWER('$username') AND password = '$password'";
	open_mysql_connection();
	$result = mysql_query($query);
	$result = mysql_num_rows($result);
	mysql_close();
	if ($result == 1) { return TRUE; }
	return FALSE;
}

function get_number_of_commander_matches_for_playerid_for_period($playerid, $period = 0) {
        $query = "SELECT count(s.matchid) FROM commanderstats s, matches m WHERE m.matchid = s.matchid AND m.date >= '$period' AND s.playerid = '$playerid' AND s.duration > '0'";
	open_mysql_connection();
	$result = mysql_query($query);
	$result = mysql_fetch_array($result);
	mysql_close();
        if (empty($result[0])) { return 0; }
	return $result[0];
}

function get_number_of_actionplayer_matches_for_clan_for_period($clan, $period = 0) {
        $query = "SELECT count(s.matchid) FROM stats s, matches m, players p WHERE m.matchid = s.matchid AND m.date >= '$period' AND s.playerid = p.playerid AND p.playername LIKE '$clan%' AND s.duration != '0'";
        open_mysql_connection();
	$result = mysql_query($query);
	$result = mysql_fetch_array($result);
	mysql_close();
	return $result[0];
}

function get_number_of_actionplayer_matches_for_playerid_for_period($playerid, $period = 0) {
        $query = "SELECT count(s.matchid) FROM stats s, matches m WHERE m.matchid = s.matchid AND m.date >= '$period' AND s.playerid = '$playerid' AND s.duration > '0'";
	open_mysql_connection();
	$result = mysql_query($query);
	$result = mysql_fetch_array($result);
	mysql_close();
	return $result[0];
}

function get_number_of_matches_from_playerid($playerid) {
	$query = "	SELECT matchid FROM stats WHERE playerid = '$playerid'
			UNION
			SELECT matchid FROM commanderstats WHERE playerid = '$playerid'";
	open_mysql_connection();
	$result = mysql_query($query);
	$result = mysql_num_rows($result);
	mysql_close();
	return $result;
}


function get_matching_playername_for($playername) {
	open_mysql_connection();
	$playername = mysql_real_escape_string($playername);
	$query = "SELECT playerid, playername FROM players WHERE playername LIKE '%$playername%'";
	$result = mysql_query($query);
	mysql_close();
	return $result;
}

function get_duplicate_matches_query_for_playerids_array($playerids) {
        $array_length = count($playerids);
        if ($array_length == 1) {
               if (!is_numeric($playerids[0])) { return "SELECT 1 FROM stats WHERE 1=2;"; }
                $query = "SELECT matchid FROM stats WHERE playerid = '$playerids[0]'";
        } else {
                $i = 0;
                foreach ($playerids as $playerid) {
                        if (!is_numeric($playerid)) { continue; }
                        if (!$i) { $where = " playerid = '$playerid'"; $i++; }
                        else { $where .= " OR playerid = '$playerid'"; }
                }
                $commander = "SELECT playerid, matchid FROM commanderstats WHERE $where";
                $action    = "SELECT playerid, matchid FROM stats WHERE $where";
                $query     = "SELECT matchid FROM (SELECT COUNT(sub.matchid) as kpl, matchid FROM ($action UNION $commander) as sub GROUP by matchid HAVING kpl = '$array_length') as sub";
        }
        return $query;
}

function get_duplicate_matches_from_playerids($playerid1, $playerid2) {
	$query = "	SELECT COUNT(sub.matchid) as kpl, matchid FROM (
				SELECT playerid, c.matchid FROM commanderstats c WHERE c.playerid = $playerid1 OR playerid = $playerid2
				UNION
				SELECT playerid, s.matchid FROM stats s WHERE s.playerid = $playerid1 OR playerid = $playerid2
			) as sub
			GROUP by matchid
			HAVING kpl > 1
			ORDER BY kpl DESC";
        echo $query;
	open_mysql_connection();
	$result = mysql_query($query);
        mysql_close();
	return $result;
}

function get_time_binded_ladder_ordered_by($column) {
	$period = get_period();
	$h = "(SUM(TIME_TO_SEC(stats.duration))/60/60)";
	$m = "(SUM(TIME_TO_SEC(stats.duration))/60)";
	$t1  = "stats";
	$t2  = "matches";
	$t3  = "players";
	$c  = "playername, $t1.playerid, (SUM(stats.exp)/(SUM(TIME_TO_SEC(stats.duration))/60)) as _sf, ";
	$c .= "(SUM(TIME_TO_SEC($t1.duration))/60/60) as _time, ";
	$c .= "SUM(exp) AS _exp, (SUM(kills)/$m) AS _kills, (SUM(souls)/$h) AS _souls, (SUM(deaths)/$h) AS _deaths, SUM(kills)/SUM(deaths) as _kd, (SUM(healed)/$m) AS _healed, ";
	$c .= "(SUM(res)/$h) AS _res, (SUM(gold)/$m) AS _gold, (SUM(repair)/$m) AS _repair, (SUM(razed)/$h) AS _razed, (SUM(dmg)/$m) AS _dmg, (SUM(bd)/$m) AS _bd";
	$from = "$t1, $t2, $t3";
	$w0 = "$t1.matchid = $t2.matchid";
	$w1 = "$t1.duration > 0";
	$w2 = "$t2.date >= '$period'";
	$w3 = "$t1.playerid = $t3.playerid";
	$query = "SELECT * FROM (SELECT $c FROM $from WHERE $w0 AND $w1 AND $w2 AND $w3 GROUP BY playername ORDER by _$column DESC) as sub WHERE sub._time >= 1 LIMIT 100;";
	open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
	return $result;
}

function get_ladder_ordered_by($column) {
	$period = get_period();
	$t1  = "stats";
	$t2  = "matches";
	$t3  = "players";
	$c  = "playername, $t1.playerid, (SUM(stats.exp)/(SUM(TIME_TO_SEC(stats.duration))/60)) as _sf, ";
	$c .= "(SUM(TIME_TO_SEC($t1.duration))/60/60) as _time, ";
	$c .= "SUM(exp) AS _exp, SUM(kills) AS _kills, SUM(souls) AS _souls, SUM(deaths) AS _deaths, SUM(kills)/SUM(deaths) as _kd, SUM(healed) AS _healed, ";
	$c .= "SUM(res) AS _res, SUM(gold) AS _gold, SUM(repair) AS _repair, SUM(razed) AS _razed, SUM(dmg) AS _dmg, SUM(bd) AS _bd";
	$from = "$t1, $t2, $t3";
	$w0 = "$t1.matchid = $t2.matchid";
	$w1 = "$t1.duration > 0";
	$w2 = "$t2.date >= '$period'";
	$w3 = "$t1.playerid = $t3.playerid";
	$query = "SELECT * FROM (SELECT $c FROM $from WHERE $w0 AND $w1 AND $w2 AND $w3 GROUP BY playername ORDER by _$column DESC) as sub WHERE sub._time >= 1 LIMIT 100;";
	open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
	return $result;
}

function get_all_matches_of_playerid($playerid) {
	$query = "SELECT * FROM stats, matches WHERE stats.playerid = '$playerid' AND matches.matchid = stats.matchid AND stats.duration > 0 ORDER by stats.matchid ASC";
	open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
	return $result;
}

function get_30days_most_played_with_playerid($playerid) {
	$period = get_period();
	$query = "SELECT playerid, COUNT(map) as kpl, map FROM commanderstats c, matches m WHERE m.duration > 0 AND m.date > '$period' AND playerid = '$playerid' AND m.matchid = c.matchid GROUP BY map ORDER BY kpl DESC";
	open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
	return $result;
}

function get_played_maps_for_playerid_for_period($playerid, $period = 0) {
	if ($period == "0") { $period = get_period(); }
	if ($playerid == 0) {
		$query  = "SELECT playerid, map, COUNT(map) as times FROM matches m JOIN (SELECT playerid, matchid, team FROM stats s UNION ALL SELECT playerid, matchid, team FROM commanderstats c) as sub ON m.matchid = sub.matchid AND m.date > '$period' GROUP BY map ORDER BY times DESC";
	} else {
		$query  = "SELECT playerid, map, COUNT(map) as times FROM matches m JOIN (SELECT playerid, matchid, team FROM stats s WHERE s.playerid = '$playerid' UNION ALL SELECT playerid, matchid, team FROM commanderstats c WHERE c.playerid = '$playerid') as sub ON m.matchid = sub.matchid AND m.date > '$period' GROUP BY map ORDER BY times DESC";
	}
	open_mysql_connection();
	$result = mysql_query($query);
	// echo $query;
	mysql_close();
	return $result;
}



function get_30days_lf_winloss_for_commander($playerid) {
	$row = get_30days_lf_components_for_playerid($playerid);
	if ($row == 0 || $row == NULL || $row['exp'] == NULL || $row['min'] == 0) { return array(0,0,0); }
	$expmin = $row['exp'] / ($row['min']/60);
	$wins  = $row['winned'];
	$win = $wins/$row['matches'];
	$loss = $row['lossed'];
	$lf = (1/2)*($expmin) + (1/4)*($expmin)*(3.8/5) + (1/4)*($expmin) * ($win);
	return array($lf, $wins, $loss);
}

function get_commander_winloss_for_playerid_for_period($playerid, $period) {
	$t1  = "matches";
	$t2  = "commanderstats";
	$c  = "SUM(IF($t1.winner = $t2.team,1,0)) as winned, ";
	$c .= "SUM(IF($t1.winner != $t2.team,1,0)) as lossed";
	$from = "$t1, $t2";
	$w0 = "$t2.playerid = $playerid";
	$w1 = "$t1.matchid = $t2.matchid";
	$w2 = "$t2.duration > 0";
	$w3 = "$t1.date > '$period'";
	$query = "SELECT $c FROM $from WHERE $w0 AND $w1 AND $w2 AND $w3";
	open_mysql_connection();
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	$winned = $row['winned'];
	$lossed = $row['lossed'];
        if ($winned == '') { $winned = 0; }
        if ($lossed == '') { $lossed = 0; }
	mysql_close();
	return array($winned, $lossed);
}

function get_30days_winloss_for_playerid($playerid) {
        return get_actionplayer_winloss_for_playerid_for_period($playerid, get_period(30));
}

function get_actionplayer_winloss_for_playerid_for_period($playerid, $period) {
	$t1  = "matches";
	$t2  = "stats";
	$c  = "SUM(IF($t1.winner = $t2.team,1,0)) as winned, ";
	$c .= "SUM(IF($t1.winner != $t2.team,1,0)) as lossed";
	$from = "$t1, $t2";
	$w0 = "$t2.playerid = $playerid";
	$w1 = "$t1.matchid = $t2.matchid";
	$w2 = "$t2.duration > 0";
	$w3 = "$t1.date > '$period'";
	$query = "SELECT $c FROM $from WHERE $w0 AND $w1 AND $w2 AND $w3";
	open_mysql_connection();
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	$winned = $row['winned'];
	$lossed = $row['lossed'];
        if ($winned == '') { $winned = 0; }
        if ($lossed == '') { $lossed = 0; }
	mysql_close();
	return array($winned, $lossed);
}

function get_lf_components_for_playerid_for_period_verbose($playerid, $period = 30) {
	$t1  = "matches";
	$t2  = "commanderstats";
	$c  = "$t2.exp, date, ";
	$c .= "TIME_TO_SEC($t2.duration) as time, ";
	$c .= "$t2.matchid, ";
	$c .= "IF($t1.winner = $t2.team,1,0) as winned, ";
	$c .= "IF($t1.winner != $t2.team,1,0) as lossed";
	$from = "$t1, $t2";
	$w0 = "$t2.playerid = $playerid";
	$w1 = "$t1.matchid = $t2.matchid";
	$w2 = "$t2.duration > 0";
	$w3 = "$t1.date > '$period'";
	$query = "SELECT $c FROM $from WHERE $w0 AND $w1 AND $w2 AND $w3 ORDER by matches.date ASC";
	open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
	return $result;
}


function get_30days_lf_components_for_playerid_verbose($playerid) {
	$period = get_period();
	$t1  = "matches";
	$t2  = "commanderstats";
	$c  = "$t2.exp, date, ";
	$c .= "TIME_TO_SEC($t2.duration) as time, ";
	$c .= "$t2.matchid, ";
	$c .= "IF($t1.winner = $t2.team,1,0) as winned, ";
	$c .= "IF($t1.winner != $t2.team,1,0) as lossed";
	$from = "$t1, $t2";
	$w0 = "$t2.playerid = $playerid";
	$w1 = "$t1.matchid = $t2.matchid";
	$w2 = "$t2.duration > 0";
	$w3 = "$t1.date > '$period'";
	$query = "SELECT $c FROM $from WHERE $w0 AND $w1 AND $w2 AND $w3 ORDER by matches.date ASC";
	open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
	return $result;
}

function get_30days_lf_components_for_playerid($playerid) {
	$period = get_period();
	$t1  = "matches";
	$t2  = "commanderstats";
	$c  = "SUM($t2.exp) as exp, ";
	$c .= "SUM(TIME_TO_SEC($t2.duration)) as min, ";
	$c .= "COUNT($t2.matchid) as matches, ";
	$c .= "SUM(IF($t1.winner = $t2.team,1,0)) as winned, ";
	$c .= "SUM(IF($t1.winner != $t2.team,1,0)) as lossed";
	$from = "$t1, $t2";
	$w0 = "$t2.playerid = $playerid";
	$w1 = "$t1.matchid = $t2.matchid";
	$w2 = "$t1.duration > 0";
	$w3 = "$t1.date > '$period'";
	$query = "SELECT $c FROM $from WHERE $w0 AND $w1 AND $w2 AND $w3";
	//echo $query;
	open_mysql_connection();
	$result = mysql_query($query);
	if (!$result) { mysql_close(); return 0; }
	$row = mysql_fetch_assoc($result);
	mysql_close();
	if ($row['matches']==0) { return 0; }
	return $row;
}

function get_sfs_for_clan_for_period_verbose($clan, $period) {
	open_mysql_connection();
        $query = "SELECT playername, TIME_TO_SEC(stats.duration) as time, stats.matchid, exp FROM stats, matches, players WHERE stats.playerid = players.playerid AND playername LIKE '$clan%' AND stats.duration > 0 AND date >= '$period' AND stats.matchid = matches.matchid AND matches.duration > 0 ORDER BY matches.date ASC";
        $result = mysql_query($query);
	mysql_close();
	return $result;
}

function get_sf_for_playerid_for_period($playerid, $period) {
	open_mysql_connection();
	$playerid = mysql_real_escape_string($playerid);
	$query = "SELECT SUM(exp)/(SUM(TIME_TO_SEC(stats.duration))/60) as sf FROM stats, matches WHERE playerid = '$playerid' AND stats.duration > 0 AND date >= '$period' AND stats.matchid = matches.matchid AND matches.duration > 0";
        $result = mysql_query($query);
        $result = mysql_fetch_row($result);
	mysql_close();
	return $result[0];
}

function get_sfs_for_playerid_verbose($playerid, $period) {
	open_mysql_connection();
	$playerid = mysql_real_escape_string($playerid);
	$query = "SELECT TIME_TO_SEC(stats.duration) as time, stats.matchid, exp, date, sf FROM stats, matches WHERE playerid = '$playerid' AND stats.duration > 0 AND date >= '$period' AND stats.matchid = matches.matchid ANd matches.duration > 0 ORDER BY matches.date ASC";
	$result = mysql_query($query);
	mysql_close();
	return $result;
}

function get_30days_sfs_for_playerid_verbose($playerid) {
	open_mysql_connection();
	$playerid = mysql_real_escape_string($playerid);
	$period = get_period();
	$query = "SELECT TIME_TO_SEC(stats.duration) as time, exp, date, sf FROM stats, matches WHERE playerid = '$playerid' AND stats.duration > 0 AND date > '$period' AND stats.matchid = matches.matchid ORDER BY matches.date ASC";
	$result = mysql_query($query);
	mysql_close();
	return $result;
}

function get_30days_sfs_for_playerid($playerid) {
	open_mysql_connection();
	$playerid = mysql_real_Escape_string($playerid);
	$period = get_period();
	$query = "SELECT date, sf FROM stats, matches WHERE playerid = '$playerid' AND stats.duration > 0 AND date > '$period' AND stats.matchid = matches.matchid ORDER BY matches.date ASC";
	$result = mysql_query($query);
	mysql_close();
	return $result;
}

// Highest functions

function get_average_highest_sf_for_all_vips($period = 1) {
	$column = "MAX(stats.sf)";
	$name   = "avg";
        if ($period) {
                $period = get_period();
                $query = "SELECT players.playername, $name FROM vip, players ,(SELECT playerid, $column as $name FROM stats,matches WHERE stats.matchid = matches.matchid AND stats.duration != 0 AND matches.date > '$period' GROUP BY playerid) as sub WHERE sub.playerid = players.playerid AND sub.playerid = vip.playerid ORDER BY sub.$name DESC LIMIT 0, 10";
        } else {
                $query = "SELECT players.playername, $name FROM vip, players ,(SELECT playerid, $column as $name FROM stats,matches WHERE stats.matchid = matches.matchid AND stats.duration != 0 GROUP BY playerid) as sub WHERE sub.playerid = players.playerid AND sub.playerid = vip.playerid ORDER BY sub.$name DESC LIMIT 0, 10";

        }
        open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
	return $result;
}
function get_most_souls_in_game($period = 1) {
	$column = "MAX(stats.souls)";
	$name   = "souls";
        if ($period) {
                $period = get_period();
                $query = "SELECT players.playername, $name FROM vip, players ,(SELECT playerid, $column as $name FROM stats,matches WHERE stats.matchid = matches.matchid AND stats.duration != 0 AND matches.date > '$period' GROUP BY playerid) as sub WHERE sub.playerid = players.playerid AND sub.playerid = vip.playerid ORDER BY sub.$name DESC LIMIT 0, 10";
        } else {
                $query = "SELECT players.playername, $name FROM vip, players ,(SELECT playerid, $column as $name FROM stats,matches WHERE stats.matchid = matches.matchid AND stats.duration != 0 GROUP BY playerid) as sub WHERE sub.playerid = players.playerid AND sub.playerid = vip.playerid ORDER BY sub.$name DESC LIMIT 0, 10";
        }
        open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
	return $result;
}
function get_most_kills_in_game($period = 1) {
	$column = "MAX(stats.kills)";
	$name   = "kills";
        if ($period) {
                $period = get_period();
                $query = "SELECT players.playername, $name FROM vip, players ,(SELECT playerid, $column as $name FROM stats,matches WHERE stats.matchid = matches.matchid AND stats.duration != 0 AND matches.date > '$period' GROUP BY playerid) as sub WHERE sub.playerid = players.playerid AND sub.playerid = vip.playerid ORDER BY sub.$name DESC LIMIT 0, 10";
        } else {
                $query = "SELECT players.playername, $name FROM vip, players ,(SELECT playerid, $column as $name FROM stats,matches WHERE stats.matchid = matches.matchid AND stats.duration != 0 GROUP BY playerid) as sub WHERE sub.playerid = players.playerid AND sub.playerid = vip.playerid ORDER BY sub.$name DESC LIMIT 0, 10";
        }
        open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
	return $result;
}
function get_most_heals_in_game($period = 1) {
	$column = "MAX(stats.healed)";
	$name   = "healed";
        if ($period) {
                $period = get_period();
                $query = "SELECT players.playername, $name FROM vip, players ,(SELECT playerid, $column as $name FROM stats,matches WHERE stats.matchid = matches.matchid AND stats.duration != 0 AND matches.date > '$period' GROUP BY playerid) as sub WHERE sub.playerid = players.playerid AND sub.playerid = vip.playerid ORDER BY sub.$name DESC LIMIT 0, 10";
        } else {
                $query = "SELECT players.playername, $name FROM vip, players ,(SELECT playerid, $column as $name FROM stats,matches WHERE stats.matchid = matches.matchid AND stats.duration != 0 GROUP BY playerid) as sub WHERE sub.playerid = players.playerid AND sub.playerid = vip.playerid ORDER BY sub.$name DESC LIMIT 0, 10";
        }
        open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
	return $result;
}
function get_best_kd($period = 1) {
	$column = "stats.kd as realkd, MAX(CAST(stats.kd as DECIMAL(5,2)))";
	$name   = "kd";
        if ($period) {
                $period = get_period();
                $query = "SELECT players.playername, realkd, $name FROM vip, players ,(SELECT playerid, $column as $name FROM stats,matches WHERE stats.matchid = matches.matchid AND stats.duration != 0 AND matches.date > '$period' GROUP BY playerid) as sub WHERE sub.playerid = players.playerid AND sub.playerid = vip.playerid ORDER BY sub.$name DESC LIMIT 0, 10";
        } else {
                $query = "SELECT players.playername, realkd, $name FROM vip, players ,(SELECT playerid, $column as $name FROM stats,matches WHERE stats.matchid = matches.matchid AND stats.duration != 0 GROUP BY playerid) as sub WHERE sub.playerid = players.playerid AND sub.playerid = vip.playerid ORDER BY sub.$name DESC LIMIT 0, 10";
        }
        open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
	return $result;
}
function get_most_bd($period = 1) {
	$column = "MAX(stats.bd)";
	$name   = "bd";
        if ($period) {
                $period = get_period();
                $query = "SELECT players.playername, $name FROM vip, players ,(SELECT playerid, $column as $name FROM stats,matches WHERE stats.matchid = matches.matchid AND stats.duration != 0 AND matches.date > '$period' GROUP BY playerid) as sub WHERE sub.playerid = players.playerid AND sub.playerid = vip.playerid ORDER BY sub.$name DESC LIMIT 0, 10";
        } else {
                $query = "SELECT players.playername, $name FROM vip, players ,(SELECT playerid, $column as $name FROM stats,matches WHERE stats.matchid = matches.matchid AND stats.duration != 0 GROUP BY playerid) as sub WHERE sub.playerid = players.playerid AND sub.playerid = vip.playerid ORDER BY sub.$name DESC LIMIT 0, 10";
        }
        open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
	return $result;
}

function get_most_repaired($period = 1) {
	$column = "MAX(stats.repair)";
	$name   = "repair";
        if ($period) {
                $period = get_period();
                $query = "SELECT players.playername, $name FROM vip, players ,(SELECT playerid, $column as $name FROM stats,matches WHERE stats.matchid = matches.matchid AND stats.duration != 0 AND matches.date > '$period' GROUP BY playerid) as sub WHERE sub.playerid = players.playerid AND sub.playerid = vip.playerid ORDER BY sub.$name DESC LIMIT 0, 10";
        } else {
                $query = "SELECT players.playername, $name FROM vip, players ,(SELECT playerid, $column as $name FROM stats,matches WHERE stats.matchid = matches.matchid AND stats.duration != 0 GROUP BY playerid) as sub WHERE sub.playerid = players.playerid AND sub.playerid = vip.playerid ORDER BY sub.$name DESC LIMIT 0, 10";
        }
        open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
	return $result;
}
function get_most_deaths_in_game($period = 1) {
	$column = "MAX(stats.deaths)";
	$name   = "deaths";
        if ($period) {
                $period = get_period();
                $query = "SELECT players.playername, $name FROM vip, players ,(SELECT playerid, $column as $name FROM stats,matches WHERE stats.matchid = matches.matchid AND stats.duration != 0 AND matches.date > '$period' GROUP BY playerid) as sub WHERE sub.playerid = players.playerid AND sub.playerid = vip.playerid ORDER BY sub.$name DESC LIMIT 0, 10";
        } else {
                $query = "SELECT players.playername, $name FROM vip, players ,(SELECT playerid, $column as $name FROM stats,matches WHERE stats.matchid = matches.matchid AND stats.duration != 0 GROUP BY playerid) as sub WHERE sub.playerid = players.playerid AND sub.playerid = vip.playerid ORDER BY sub.$name DESC LIMIT 0, 10";
        }
        open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
	return $result;
}

// End of highest functions



function get_database_stats() {
	open_mysql_connection();
	$query = "SELECT table_name, table_rows, data_length, index_length, \n"
    . "round(((data_length + index_length) / 1024 / 1024),2) \"size\"\n"
    . "FROM information_schema.TABLES WHERE table_schema = \"boubbino_savage\"";
	$result = mysql_query($query);
        mysql_close();
	return $result;
}

function get_30days_top_souls($view) {
	$period = get_period();
	$column = "SUM(stats.souls)";
	if ($view==2) {
		$column = "$column/(SUM(TIME_TO_SEC(stats.duration))/60/60)";
	}
	$name   = "souls";
	$query = "SELECT players.playername, $name FROM vip, players ,(SELECT playerid, $column as $name FROM stats,matches WHERE stats.matchid = matches.matchid AND stats.duration != 0 AND matches.date > '$period' GROUP BY playerid ORDER BY $name DESC) as sub WHERE sub.playerid = players.playerid AND sub.playerid = vip.playerid ORDER BY sub.$name DESC";
	open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
	return $result;
}

function get_30days_top_bd($view) {
	$period = get_period();
	$column = "SUM(stats.bd)";
	if ($view==2) {
		$column = "$column/(SUM(TIME_TO_SEC(stats.duration))/60/60)";
	}
	$name   = "db";
	$query = "SELECT players.playername, $name FROM vip, players ,(SELECT playerid, $column as $name FROM stats,matches WHERE stats.matchid = matches.matchid AND stats.duration != 0 AND matches.date > '$period' GROUP BY playerid ORDER BY $name DESC) as sub WHERE sub.playerid = players.playerid AND sub.playerid = vip.playerid ORDER BY sub.$name DESC";
	open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
	return $result;
}

function get_30days_top_repair($view) {
	$period = get_period();
	$column = "SUM(stats.repair)";
	if ($view==2) {
		$column = "$column/(SUM(TIME_TO_SEC(stats.duration))/60)";
	}
	$name   = "repair";
	$query = "SELECT players.playername, $name FROM vip, players ,(SELECT playerid, $column as $name FROM stats,matches WHERE stats.matchid = matches.matchid AND stats.duration != 0 AND matches.date > '$period' GROUP BY playerid ORDER BY $name DESC) as sub WHERE sub.playerid = players.playerid AND sub.playerid = vip.playerid ORDER BY sub.$name DESC";
	open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
	return $result;
}

function get_30days_top_hours($view) {
	$period = get_period();
	$column = "SUM(TIME_TO_SEC(stats.duration))/60/60";
	$name   = "hours";
	$query = "SELECT players.playername, $name FROM vip, players ,(SELECT playerid, $column as $name FROM stats,matches WHERE stats.matchid = matches.matchid AND stats.duration != 0 AND matches.date > '$period' GROUP BY playerid ORDER BY $name DESC) as sub WHERE sub.playerid = players.playerid AND sub.playerid = vip.playerid ORDER BY sub.$name DESC";
	open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
	return $result;

}

function get_30days_top_kd($view) {
	$period = get_period();
	$column = "SUM(stats.kills)/SUM(stats.deaths)";
	$name   = "kd";
	$query = "SELECT players.playername, $name FROM vip, players ,(SELECT playerid, $column as $name FROM stats,matches WHERE stats.matchid = matches.matchid AND stats.duration != 0 AND matches.date > '$period' GROUP BY playerid ORDER BY '$name' DESC) as sub WHERE sub.playerid = players.playerid AND sub.playerid = vip.playerid ORDER BY sub.$name DESC";
	// echo $query;
	open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
	return $result;

}

function get_30days_top_kills($view) {
	$period = get_period();
	$column = "SUM(stats.kills)";
	if ($view==2) {
		$column = "$column/(SUM(TIME_TO_SEC(stats.duration))/60)";
	}	
	$name   = "kills";
	$query = "SELECT players.playername, $name FROM vip, players ,(SELECT playerid, $column as $name FROM stats,matches WHERE stats.matchid = matches.matchid AND stats.duration != 0 AND matches.date > '$period' GROUP BY playerid ) as sub WHERE sub.playerid = players.playerid AND sub.playerid = vip.playerid ORDER BY sub.$name DESC";
	open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
	return $result;
}


function get_30days_top_healers($view) {
	$period = get_period();
	$column = "SUM(stats.healed)";
	if ($view==2) {
		$column = "$column/(SUM(TIME_TO_SEC(stats.duration))/60)";
	}	
	$name   = "healed";
	$query = "SELECT players.playername, $name FROM vip, players ,(SELECT playerid, $column as $name FROM stats,matches WHERE stats.matchid = matches.matchid AND stats.duration != 0 AND matches.date > '$period' GROUP BY playerid ORDER BY '$name' DESC) as sub WHERE sub.playerid = players.playerid AND sub.playerid = vip.playerid ORDER BY sub.$name DESC";
	open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
	return $result;

}


function get_30days_top_deaths($view) {
	$period = get_period();
	$column = "SUM(stats.deaths)";
	if ($view==2) {
		$column = "$column/(SUM(TIME_TO_SEC(stats.duration))/60/60)";
	}	
	$name   = "deaths";
	$query = "SELECT players.playername, $name FROM vip, players ,(SELECT playerid, $column as $name FROM stats,matches WHERE stats.matchid = matches.matchid AND stats.duration != 0 AND matches.date > '$period' GROUP BY playerid ORDER BY $name DESC) as sub WHERE sub.playerid = players.playerid AND sub.playerid = vip.playerid ORDER BY sub.$name DESC";
	open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
	return $result;
}

function get_30days_average_sf_for_all_vips($view) {
	$period = get_period();
	$query = "SELECT players.playername, avg FROM vip, players ,(SELECT playerid, (SUM(stats.exp)/(SUM(TIME_TO_SEC(stats.duration))/60)) as avg FROM stats,matches WHERE stats.matchid = matches.matchid AND stats.duration > 0 AND matches.date > '$period' GROUP BY playerid ORDER BY avg DESC) as sub WHERE sub.playerid = players.playerid AND sub.playerid = vip.playerid ORDER BY sub.avg DESC";
	open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
	return $result;
}


function get_vip_players($sort = "ORDER BY playername DESC") {
	$sort = "ORDER BY playername DESC";
	$query = "SELECT lastupdate, vip.playerid, playername FROM vip,players WHERE vip.playerid = players.playerid $sort";
	open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
	return $result;
}

function playerid_to_playername($playerid) {
	open_mysql_connection();
	$playerid = mysql_real_escape_string($playerid);
	$query = "SELECT playername FROM players WHERE playerid = '$playerid'";
	$result = mysql_query($query);
	mysql_close();
	$row = mysql_fetch_row($result);
	if (empty($row[0])) { return $playerid; }
	return $row[0];

}

function userid_to_playername($userid) {
	open_mysql_connection();
	$query = "SELECT username FROM users WHERE userid = '$userid'";
        // echo " $query ";
	$result = mysql_query($query);
	mysql_close();
	$row = mysql_fetch_row($result);
	if (empty($row[0])) { die("Could not return playername to playerid"); }
	return $row[0];   
}

function playername_to_playerid($playername) {
	open_mysql_connection();
	$playername = mysql_real_escape_string($playername);
	$query = "SELECT playerid FROM players WHERE playername LIKE '%$playername'";
	$result = mysql_query($query);
	mysql_close();
	$row = mysql_fetch_row($result);
	if (empty($row[0])) { die("Could not return playername to playerid"); }
	return $row[0];

}

function get_30days_stats_for_commander($playerid) {
	open_mysql_connection();
	$playerid = mysql_real_escape_string($playerid);
	$period = get_period();
	$query = "SELECT SUM(kills) as kills, SUM(erected) as erected, SUM(debuffs) as deuffs, SUM(buffs) as buffs, SUM(orders) as orders,  SUM(gold) as gold, SUM(healed) as healed, SUM(dmg) as dmg, SUM(TIME_TO_SEC(commanderstats.duration)/60/60) as hours FROM commanderstats, players,matches WHERE commanderstats.playerid = players.playerid AND commanderstats.playerid = '$playerid' AND commanderstats.duration != 0 AND commanderstats.matchid = matches.matchid AND matches.date > '$period'";
	// echo $query;
	$result = mysql_query($query);
	mysql_close();
	return $result;

}

function get_30days_stats_for_player($playerid) {
	open_mysql_connection();
	$playerid = mysql_real_escape_string($playerid);
	$period = get_period();
	$query = "SELECT (SUM(stats.exp)/(SUM(TIME_TO_SEC(stats.duration))/60)) as averagesf, SUM(kills) as kills, SUM(deaths) as deaths, SUM(assists) as assists, SUM(bd) as db, SUM(gold) as gold, SUM(healed) as healed, SUM(TIME_TO_SEC(stats.duration)/60/60) as hours, SUM(souls) as souls FROM stats, players,matches WHERE stats.playerid = players.playerid AND stats.playerid = '$playerid' AND stats.duration != 0 AND stats.matchid = matches.matchid AND matches.date > '$period'";
	$result = mysql_query($query);
	mysql_close();
	return $result;
}


function get_30days_most_kills_in_one_game_for_playerid($playerid) {
 	open_mysql_connection();
	$playerid = mysql_real_escape_string($playerid);
	$last30days = get_period();
	$query = "SELECT MAX(stats.kills) as highkills FROM stats, players,matches WHERE stats.playerid = players.playerid AND stats.playerid = '$playerid' AND stats.duration != 0 AND stats.matchid = matches.matchid AND matches.date >= $last30days";
	$result = mysql_query($query);
	mysql_close();
	$row = mysql_fetch_row($result);
	return $row[0];
}

function get_30days_most_bdmg_in_one_game_for_playerid($playerid) {
 	open_mysql_connection();
	$playerid = mysql_real_escape_string($playerid);
	$last30days = get_period();
	$query = "SELECT MAX(stats.bd) as highbdmg FROM stats, players,matches WHERE stats.playerid = players.playerid AND stats.playerid = '$playerid' AND stats.duration != 0 AND stats.matchid = matches.matchid AND matches.date >= $last30days";
	$result = mysql_query($query);
	mysql_close();
	$row = mysql_fetch_row($result);
	return $row[0];
}

function get_30days_most_souls_in_one_game_for_playerid($playerid) {
 	open_mysql_connection();
	$playerid = mysql_real_escape_string($playerid);
	$last30days = get_period();
	$query = "SELECT MAX(stats.souls) as highsouls FROM stats, players,matches WHERE stats.playerid = players.playerid AND stats.playerid = '$playerid' AND stats.duration != 0 AND stats.matchid = matches.matchid AND matches.date >= $last30days";
	$result = mysql_query($query);
	mysql_close();
	$row = mysql_fetch_row($result);
	return $row[0];
}

function get_30days_most_healed_in_one_game_for_playerid($playerid) {
 	open_mysql_connection();
	$playerid = mysql_real_escape_string($playerid);
	$last30days = get_period();
	$query = "SELECT MAX(stats.healed) as highhealed FROM stats, players,matches WHERE stats.playerid = players.playerid AND stats.playerid = '$playerid' AND stats.duration != 0 AND stats.matchid = matches.matchid AND matches.date >= $last30days";
	$result = mysql_query($query);
	mysql_close();
	$row = mysql_fetch_row($result);
	return $row[0];
}

function get_30days_most_kd_in_one_game_for_playerid($playerid) {
 	open_mysql_connection();
	$playerid = mysql_real_escape_string($playerid);
	$last30days = get_period();
	$query = "SELECT MAX(CAST(stats.kd as DECIMAL(5,2))) as highkd FROM stats, players,matches WHERE stats.playerid = players.playerid AND stats.playerid = '$playerid' AND stats.duration != 0 AND stats.matchid = matches.matchid AND matches.date >= $last30days";
	$result = mysql_query($query);
	mysql_close();
	$row = mysql_fetch_row($result);
	return $row[0];
}

function get_30days_highest_sf_in_one_game_for_playerid($playerid) {
  	open_mysql_connection();
	$playerid = mysql_real_escape_string($playerid);
	$last30days = get_period();
	$query = "SELECT MAX(stats.exp/(TIME_TO_SEC(stats.duration)/60)) as highsf FROM stats, players,matches WHERE stats.playerid = players.playerid AND stats.playerid = '$playerid' AND stats.duration != 0 AND stats.matchid = matches.matchid AND matches.date >= $last30days";
	$result = mysql_query($query);
	mysql_close();
	$row = mysql_fetch_row($result);
        return $row[0];
}

function get_30days_kills_per_minute_for_playerid($playerid) {
 	open_mysql_connection();
	$playerid = mysql_real_escape_string($playerid);
	$last30days = get_period();
	$query = "SELECT (SUM(stats.kills)/(SUM(TIME_TO_SEC(stats.duration))/60)) as kpm FROM stats, players,matches WHERE stats.playerid = players.playerid AND stats.playerid = '$playerid' AND stats.duration != 0 AND stats.matchid = matches.matchid AND matches.date >= $last30days";
	$result = mysql_query($query);
	mysql_close();
	$row = mysql_fetch_row($result);
	return $row[0];
}

function get_30days_damage_per_minute_for_playerid($playerid) {
 	open_mysql_connection();
	$playerid = mysql_real_escape_string($playerid);
	$last30days = get_period();
	$query = "SELECT (SUM(stats.dmg)/(SUM(TIME_TO_SEC(stats.duration))/60)) as dpm FROM stats, players,matches WHERE stats.playerid = players.playerid AND stats.playerid = '$playerid' AND stats.duration != 0 AND stats.matchid = matches.matchid AND matches.date >= $last30days";
	$result = mysql_query($query);
	mysql_close();
	$row = mysql_fetch_row($result);
	return $row[0];
}

function get_30days_bdamage_per_minute_for_playerid($playerid) {
 	open_mysql_connection();
	$playerid = mysql_real_escape_string($playerid);
	$last30days = get_period();
	$query = "SELECT (SUM(stats.bd)/(SUM(TIME_TO_SEC(stats.duration))/60)) as bdpm FROM stats, players,matches WHERE stats.playerid = players.playerid AND stats.playerid = '$playerid' AND stats.duration != 0 AND stats.matchid = matches.matchid AND matches.date >= $last30days";
	$result = mysql_query($query);
	mysql_close();
	$row = mysql_fetch_row($result);
	return $row[0];
}

function get_30days_souls_per_hour_for_playerid($playerid) {
 	open_mysql_connection();
	$playerid = mysql_real_escape_string($playerid);
	$last30days = get_period();
	$query = "SELECT (SUM(stats.souls)/(SUM(TIME_TO_SEC(stats.duration))/60/60)) as spm FROM stats, players,matches WHERE stats.playerid = players.playerid AND stats.playerid = '$playerid' AND stats.duration != 0 AND stats.matchid = matches.matchid AND matches.date >= $last30days";
	$result = mysql_query($query);
	mysql_close();
	$row = mysql_fetch_row($result);
	return $row[0];
}

function get_30days_heal_per_minute_for_playerid($playerid) {
 	open_mysql_connection();
	$playerid = mysql_real_escape_string($playerid);
	$last30days = get_period();
	$query = "SELECT (SUM(stats.healed)/(SUM(TIME_TO_SEC(stats.duration))/60)) as hpm FROM stats, players,matches WHERE stats.playerid = players.playerid AND stats.playerid = '$playerid' AND stats.duration != 0 AND stats.matchid = matches.matchid AND matches.date >= $last30days";
	$result = mysql_query($query);
	mysql_close();
	$row = mysql_fetch_row($result);
	return $row[0];
}

function get_30days_gold_per_minute_for_playerid($playerid) {
 	open_mysql_connection();
	$playerid = mysql_real_escape_string($playerid);
	$last30days = get_period();
	$query = "SELECT (SUM(stats.gold)/(SUM(TIME_TO_SEC(stats.duration))/60)) as gpm FROM stats, players,matches WHERE stats.playerid = players.playerid AND stats.playerid = '$playerid' AND stats.duration != 0 AND stats.matchid = matches.matchid AND matches.date >= $last30days";
	$result = mysql_query($query);
	mysql_close();
	$row = mysql_fetch_row($result);
	return $row[0];
}


function get_30_days_playtime_for_playerid($playerid) {
  	open_mysql_connection();
	$playerid = mysql_real_escape_string($playerid);
	$last30days = get_period();
	$query = "SELECT (SUM(TIME_TO_SEC(stats.duration))/60/60) as playtime FROM stats, players,matches WHERE stats.playerid = players.playerid AND stats.playerid = '$playerid' AND stats.duration != 0 AND stats.matchid = matches.matchid AND matches.date >= $last30days";
	$result = mysql_query($query);
	mysql_close();
	$row = mysql_fetch_row($result);
	return $row[0];
}


function get_30days_kd_for_playerid($playerid) {
 	open_mysql_connection();
	$playerid = mysql_real_escape_string($playerid);
	$last30days = get_period();
	$query = "SELECT (SUM(stats.kills)/SUM(stats.deaths)) as kd FROM stats, players,matches WHERE stats.playerid = players.playerid AND stats.playerid = '$playerid' AND stats.duration != 0 AND stats.matchid = matches.matchid AND matches.date >= $last30days";
	$result = mysql_query($query);
	mysql_close();
	$row = mysql_fetch_row($result);
	return $row[0];
}

function get_30days_average_sf_for_playerid($playerid) {
	open_mysql_connection();
	$playerid = mysql_real_escape_string($playerid);
	$last30days = get_period();
	$query = "SELECT (SUM(stats.exp)/(SUM(TIME_TO_SEC(stats.duration))/60)) as averagesf FROM stats, players,matches WHERE stats.playerid = players.playerid AND stats.playerid = '$playerid' AND stats.duration != 0 AND stats.matchid = matches.matchid AND matches.date >= $last30days";
	$result = mysql_query($query);
	mysql_close();
	$row = mysql_fetch_assoc($result);
	return $row;
}


function info_get_lastpage_for_playerid($playerid) {
	open_mysql_connection();
	$playerid = mysql_real_escape_string($playerid);
	$query = "SELECT lastpage FROM infotable WHERE playerid = '$playerid'";
	$result = mysql_query($query);
	mysql_close();
	$row = mysql_fetch_row($result);
	if ($row[0]>0) { return $row[0]; }
	return 1;
}


function info_set_lastpage_for_playerid($page, $playerid) {
	$query1 = "INSERT IGNORE INTO infotable SET lastpage = '$page', playerid = '$playerid'";
	$query2 = "UPDATE infotable SET lastpage = '$page' WHERE playerid = '$playerid'";
	open_mysql_connection();
	mysql_query($query2);
	mysql_query($query2);
	mysql_close();
}

// FIX THIS ONE!!!!
function fetch_match_info($matchid) {
	return 0;
	$query = "SELECT * FROM commanderstats WHERE matchid = '$matchid'";
	open_mysql_connection();
	$result = mysql_query($query);
	while ($row = mysql_fetch_row($result)) {;
		echo implode(", ", $row);
		echo "<br>";
	}
	mysql_close();
}

function update_update_time_for_playerid($playerid, $last = 0) {
	if ($last == 0) { $last = time(); }
	$query = "UPDATE vip SET lastupdate = '$last' WHERE playerid = '$playerid'";
	open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
}

function add_new_player($playerid, $playername) {
	$query = "INSERT INTO players (playerid, playername) VALUES ('$playerid', '$playername');";
	open_mysql_connection();
	mysql_query($query);
	mysql_close();
}

function update_team_record_for_commander($matchid, $playerid, $team) {
	echo "Updating team record for commander $playerid on $matchid to $team, ";
	$query = "UPDATE commanderstats SET team = '$team' WHERE matchid = '$matchid' AND playerid = '$playerid';";
	open_mysql_connection();
	mysql_query($query);
	mysql_close();		
}

function update_team_record_for_player($matchid, $playerid, $team) {
	echo "Updating team record for player $playerid on $matchid to $team, ";
	$query = "UPDATE stats SET team = '$team' WHERE matchid = '$matchid' AND playerid = '$playerid';";
	open_mysql_connection();
	mysql_query($query);
	mysql_close();		
}

function participant_record_exists_for_commander($matchid, $playerid, $team) {
	$query = "SELECT team FROM commanderstats WHERE matchid = '$matchid' AND playerid = '$playerid'";
	open_mysql_connection();
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	mysql_close();
	if ($row['team'] == "1" || $row['team'] == "2") {
		return TRUE;
	} else if ($row['team'] == "0") {
		update_team_record_for_commander($matchid, $playerid, $team);
		return TRUE;
	}
	return FALSE;
}

function participant_record_exists_for_player($matchid, $playerid, $team) {
	$query = "SELECT team FROM stats WHERE matchid = '$matchid' AND playerid = '$playerid'";
	open_mysql_connection();
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	mysql_close();
	if ($row['team'] == "1" || $row['team'] == "2") {
		return TRUE;
	} else if ($row['team'] == "0") {
		update_team_record_for_player($matchid, $playerid, $team);
		return TRUE;
	}
	return FALSE;
}

function player_exists($playerid) {
	$query = "SELECT playerid FROM players WHERE playerid = '$playerid'";
	open_mysql_connection();
	$result = mysql_query($query);
	$result = mysql_num_rows($result);
	mysql_close();
	if ($result == "1") { return TRUE; }
	return FALSE;
}

function match_exists_new($matchid) {
	$query = "SELECT 1 FROM matches_new WHERE matchid = '$matchid'";
	open_mysql_connection();
	$result = mysql_query($query);
	$rows = mysql_num_rows($result);
	mysql_close();
	if ($rows == "1") { return TRUE; }
	return FALSE;
}

function match_exists($matchid) {
	$query = "SELECT 1 FROM matches WHERE matchid = '$matchid'";
	open_mysql_connection();
	$result = mysql_query($query);
	$rows = mysql_num_rows($result);
	mysql_close();
	if ($rows == "1") { return TRUE; }
	return FALSE;
}


function match_exists_for_playerid($matchid, $playerid) {
        // 1 = 100%
        // 2 =  50%
        // 0 =   0%
	$query1 = "SELECT duration FROM stats WHERE matchid = '$matchid' AND playerid = '$playerid'";
	$query2 = "SELECT duration FROM commanderstats WHERE matchid = '$matchid' AND playerid = '$playerid'";
	open_mysql_connection();
	$player = FALSE;
	$commander = FALSE;
	$result1 = mysql_query($query1);
	$result2 = mysql_query($query2);
	mysql_close();
	while ($row = mysql_fetch_assoc($result1)) {
		if ($row['duration'] == "00:00:00") {
			return 2;
		} else { return 1; }
	}
	while ($row = mysql_fetch_assoc($result2)) {
		if ($row['duration'] == "00:00:00") {
			return 2;
		} else { return 1; }
	}
	return 0;
}


function save_stats_for_single_match($matchid, $stats) {
	list($matchid, $date, $duration, $map, $winner) = explode(", ",$stats[0]);
	list($player_dmg_team1, $kills_team1, $assists_team1, $souls_team1, $healed_team1, $res_team1, $gold_team1, $repaired_team1, $npc_team1, $bd_team1, $razed_team1, $deaths_team1, $kd_team1) = explode(", ", $stats[1]);
	list($player_dmg_team2, $kills_team2, $assists_team2, $souls_team2, $healed_team2, $res_team2, $gold_team2, $repaired_team2, $npc_team2, $bd_team2, $razed_team2, $deaths_team2, $kd_team2) = explode(", ", $stats[2]);
	$query = "INSERT INTO matches (matchid, date, duration, map, winner, player_dmg_team1, kills_team1, assists_team1, souls_team1, healed_team1, res_team1, gold_team1, repaired_team1, npc_team1, bd_team1, razed_team1, deaths_team1, kd_team1, player_dmg_team2, kills_team2, assists_team2, souls_team2, healed_team2, res_team2, gold_team2, repaired_team2, npc_team2, bd_team2, razed_team2, deaths_team2, kd_team2) VALUES ('$matchid', '$date', '$duration', '$map', '$winner', '$player_dmg_team1', '$kills_team1', '$assists_team1', '$souls_team1', '$healed_team1', '$res_team1', '$gold_team1', '$repaired_team1', '$npc_team1', '$bd_team1', '$razed_team1	', '$deaths_team1', '$kd_team1', '$player_dmg_team2', '$kills_team2', '$assists_team2', '$souls_team2', '$healed_team2', '$res_team2', '$gold_team2', '$repaired_team2', '$npc_team2', '$bd_team2', '$razed_team2', '$deaths_team2', '$kd_team2')";
	open_mysql_connection();
	$result = mysql_query($query);
        mysql_close();
}

// FAST PARTICIPANT SAVE
function save_participant_records_all_together($querys) {
	open_mysql_connection();
	mysql_query("SET autocommit=0");
	mysql_query("START TRANSACTION");
	foreach ($querys as $query) { mysql_query($query); }
	mysql_query("COMMIT");
	mysql_close();
}


function save_commander_stats_for_match($playerid, $matchid, $stats) {
	list($exp, $orders, $gold, $erected, $repaired, $razed, $buffs, $healed, $debuffs, $dmg, $kills, $duration, $team) = $stats;
	if (match_exists_for_playerid($matchid, $playerid)==0) {
		$query = "INSERT INTO commanderstats (id, matchid, playerid, exp, orders, gold, erected, repaired, razed, buffs, healed, debuffs, dmg, kills, duration, team) VALUES (NULL, '$matchid', '$playerid', '$exp', '$orders', '$gold', '$erected', '$repaired', '$razed', '$buffs', '$healed', '$debuffs', '$dmg', '$kills', '$duration', '$team');";
	} else {
		$query = "UPDATE commanderstats SET matchid = '$matchid', playerid = '$playerid', exp = '$exp', orders = '$orders', gold = '$gold', erected = '$erected', repaired = '$repaired', razed = '$razed', buffs = '$buffs', healed = '$healed', debuffs = '$debuffs', dmg = '$dmg', kills = '$kills', duration = '$duration' WHERE commanderstats.matchid = '$matchid' AND commanderstats.playerid = '$playerid';";
	}
        open_mysql_connection();
	mysql_query($query);
	//echo mysql_error();
	mysql_close();
}

function save_player_stats_for_match($playerid, $matchid, $stats) {
	list($exp, $dmg, $kills, $assists, $souls, $npc, $healed, $res, $gold, $repair, $bd, $razed, $deaths, $kd, $duration, $sf, $team) = $stats;
	if (match_exists_for_playerid($matchid, $playerid)==0) {
		$query = "INSERT INTO stats (id, matchid, playerid, exp, dmg, kills, assists, souls, npc, healed, res, gold, repair, bd, razed, deaths, kd, duration, sf, team) VALUES (NULL, '$matchid', '$playerid', '$exp', '$dmg', '$kills', '$assists', '$souls', '$npc', '$healed', '$res', '$gold', '$repair', '$bd', '$razed', '$deaths', '$kd', '$duration', '$sf', '$team');";
	} else {
		$query = "UPDATE stats SET matchid = '$matchid', playerid = '$playerid', exp = '$exp', dmg = '$dmg', kills = '$kills', assists = '$assists', souls = '$souls', npc = '$npc', healed = '$healed', res = '$res', gold = '$gold', repair = '$repair', bd = '$bd', razed = '$razed', deaths = '$deaths', kd = '$kd', duration = '$duration', sf = '$sf' WHERE stats.matchid = '$matchid' AND stats.playerid = '$playerid';";
	}
	open_mysql_connection();
	$result = mysql_query($query);
	mysql_close();
}

function mysql_escape($variable) {
	return str_replace('\'', "", $variable);
}


function open_mysql_connection() {
	global $sql_connections;
	$sql_connections++;
	$mysql_host = "localhost";
	$mysql_user = "user";
	$mysql_pass = "pass";
	$mysql_table = "db";
	mysql_pconnect($mysql_host, $mysql_user, $mysql_pass);
	mysql_selectdb($mysql_table);
}



?>
