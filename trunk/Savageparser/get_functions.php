<?php

function get_corresponding_icon_for_event_object($object) {
        $object = strtolower($object);
        if ($object == 'spawn portal') { return "spawn_portal.png"; }
        if ($object == 'static spire') { return "static_spire.png"; }
        if ($object == 'chlorophilic spire') { return "chloro_spire.png"; }
        if ($object == 'charm shrine') { return "charm_shrine.png"; }
        if ($object == 'predator den') { return "predator_den.png"; }
        if ($object == 'sanctuary') { return "sanctuary.png"; }
        if ($object == 'sublair') { return "sublair.png"; }
        if ($object == 'grove mine') { return "grove_mine.png"; }
        if ($object == 'strata spire') { return "strata_spire.png"; }
        if ($object == 'nexus') { return "nexus.png"; }
        if ($object == 'lair') { return "lair.png"; }
        if ($object == 'cannon tower') { return "cannon_tower.png"; }
        if ($object == 'shield tower') { return "shield_tower.png"; }
        if ($object == 'siege workshop') { return "siege_workshop.png"; }
        if ($object == 'academy') { return "academy.png"; }
        if ($object == 'monastery') { return "monastery.png"; }
        if ($object == 'garrison') { return "garrison.png"; }
        if ($object == 'hell shrine') { return "hell_shrine.png"; }
        if ($object == 'steam mine') { return "steam_mine.png"; }
        if ($object == 'arrow tower') { return "arrow_tower.png"; }
        if ($object == 'armory') { return "armory.png"; }
        if ($object == 'stronghold') { return "stronghold.png"; }
        if ($object == 'sentry bat') { return "sentry_bat.png"; }
        if ($object == 'mana fountain') { return "mana_well.png"; }
        if ($object == 'poison venus') { return "venus.png"; }
        if ($object == 'demo charge') { return "demo_charge.png"; }
        if ($object == 'electric eye') { return "electric_eye.png"; }
        if ($object == 'devourer') { return "devourer.png"; }
        if ($object == 'malphas') { return "malpha.png"; }
        if ($object == 'revenant') { return "revenant.png"; }
        if ($object == 'behemoth') { return "behemoth.png"; }
        if ($object == 'tempest') { return "tempest.png"; }
        if ($object == 'shaman') { return "shaman.png"; }
        if ($object == 'predator') { return "predator.png"; }
        if ($object == 'hunter') { return "hunter.png"; }
        if ($object == 'shape shifter') { return "shape_shifter.png"; }
        if ($object == 'conjurer') { return "conjurer.png"; }
        if ($object == 'battering ram') { return "battering_ram.png"; }
        if ($object == 'steambuchet') { return "steambuchet.png"; }
        if ($object == 'chaplain') { return "chaplain.png"; }
        if ($object == 'legionnaire') { return "legionaire.png"; }
        if ($object == 'savage') { return "savage.png"; }
        if ($object == 'marksman') { return "scout.png"; }
        if ($object == 'builder') { return "builder.png"; }
        if ($object == 'steam turret') { return "steam_turret.png"; }
        if ($object == 'shield generator') { return "shield_gen.png"; }
        if ($object == 'ammo depot') { return "ammo_depot.png"; }
        if ($object == 'spectator') { return "spectator.png"; }
        if ($object == 'playername') { return "playername.png"; }
}

function get_sql_select_transpose_for_killed_buildings() {
        $str = "";
        foreach (get_event_building_array() as $object) {
                if (empty($str)) { $str  = "SUM(IF(action = 'killed',IF(target_unit = '$object',1,0),0)) as '$object'"; }
                else             { $str .= ", SUM(IF(action = 'killed',IF(target_unit = '$object',1,0),0)) as '$object'"; }
        }
        return $str;
}

function get_sql_select_transpose_for_killed_units() {
        $str = "";
        foreach (get_event_unit_array() as $object) {
                if (empty($str)) { $str  = "SUM(IF(action = 'killed',IF(target_unit = '$object',1,0),0)) as '$object'"; }
                else             { $str .= ", SUM(IF(action = 'killed',IF(target_unit = '$object',1,0),0)) as '$object'"; }
        }
        return $str;
}

function get_sql_select_transpose_for_killed_gadgets() {
        $str = "";
        foreach (get_event_gadget_array() as $object) {
                if (empty($str)) { $str  = "SUM(IF(action = 'killed',IF(target_unit = '$object',1,0),0)) as '$object'"; }
                else             { $str .= ", SUM(IF(action = 'killed',IF(target_unit = '$object',1,0),0)) as '$object'"; }
        }
        return $str;
}


function get_event_building_array() {
        return array('Sublair', 'Stronghold', 'Strata Spire', 'Steam Mine', 'Static Spire', 'Siege Workshop', 'Shield Tower', 'Sanctuary', 'Academy', 'Armory', 'Arrow Tower', 'Cannon Tower', 'Charm Shrine', 'Chlorophilic Spire', 'Garrison', 'Grove Mine', 'Hell Shrine', 'Lair', 'Monastery', 'Nexus', 'Predator Den');
}
function get_event_unit_array() {
        return array('Tempest', 'Steambuchet', 'Shape Shifter', 'Shaman', 'Savage', 'Revenant', 'Predator', 'Marksman', 'Malphas', 'Legionnaire', 'Hunter', 'Devourer', 'Conjurer', 'Chaplain', 'Builder', 'Behemoth', 'Battering Ram');
}
function get_event_gadget_array() {
        return array('Electric Eye', 'Steam Turret', 'Spawn Portal', 'Shield Generator', 'Sentry Bat', 'Poison Venus', 'Mana Fountain', 'Demo Charge', 'Ammo Depot');
}

        

function get_limit() { return " LIMIT 0, 15"; }

function get_period($period = 30) {
        if ($period == 0) { return 0; }
        return time() - ($period*24*60*60); }

function is_odd($number) { return($number & 1); }

function get_list_of_contestors_in_contest($id) {
        $result = get_contestors_in_contest($id);
        $row = mysql_fetch_assoc($result);
        if (!$row) { return "No contestors"; }
        $str = playerid_to_playername($row['playerid']);
        while ($row = mysql_fetch_assoc($result)) { $str .= "<br> ".playerid_to_playername($row['playerid']); }
        return $str;

}

function get_corresponding_header_icon_url_for_contest_functionid($id) {
        if ($id == 1)  { return "../images/contest/damage.png"; }
        if ($id == 2)  { return "../images/contest/kills.png"; }
        if ($id == 3)  { return "../images/contest/deaths.png"; }
        if ($id == 4)  { return "../images/contest/assists.png"; }
        if ($id == 5)  { return "../images/contest/souls.png"; }
        if ($id == 6)  { return "../images/contest/npc.png"; }
        if ($id == 7)  { return "../images/contest/healed.png"; }
        if ($id == 8)  { return "../images/contest/repair.png"; }
        if ($id == 9)  { return "../images/contest/bd.png"; }
        if ($id == 10) { return "../images/contest/winstreak.png"; }
        return "../images/contest/other.png";
}

function get_corresponding_stat_icon_url_for_contest_functionid($id) {
        if ($id == 1)  { return "../images/stats/dmg.png"; }
        if ($id == 2)  { return "../images/stats/kills.png"; }
        if ($id == 3)  { return "../images/stats/deaths.png"; }
        if ($id == 4)  { return "../images/stats/assists.png"; }
        if ($id == 5)  { return "../images/stats/souls.png"; }
        if ($id == 6)  { return "../images/stats/npc.png"; }
        if ($id == 7)  { return "../images/stats/healed.png"; }
        if ($id == 8)  { return "../images/stats/repair.png"; }
        if ($id == 9)  { return "../images/stats/bd.png"; }
        if ($id == 10) { return "../images/stats/orders.png"; }
        return "../images/stats/rank.png";
}

function get_string_label_for_contest_functionid($id) {
        if ($id == 1)  { return "Damage/min"; }
        if ($id == 2)  { return "Kills/min"; }
        if ($id == 3)  { return "Deaths/hour"; }
        if ($id == 4)  { return "Assists/min"; }
        if ($id == 5)  { return "Souls/hour"; }
        if ($id == 6)  { return "NPC kills/min"; }
        if ($id == 7)  { return "Healed/min"; }
        if ($id == 8)  { return "Repair/min"; }
        if ($id == 9)  { return "BD/min"; }
        if ($id == 10) { return "Highest Winstreak"; }
        return "Other";
}

function get_corresponding_mysql_query_for_contest_functionid($id, $enough_playtime, $char = ">=") {
        if ($enough_playtime == 0) { $char = "<"; }
        if ($id == 1)  { return "SELECT p.playername, SUM(s.dmg)/(SUM(TIME_TO_SEC(s.duration))/60) as stat, COUNT(*) as matches, SUM(TIME_TO_SEC(s.duration))/60/60 as time FROM contestors c, stats s, matches m, players p WHERE c.contestid = '\$id' AND s.playerid = c.playerid AND p.playerid = c.playerid AND m.matchid = s.matchid AND m.date >= '\$period1' AND m.date <= '\$period2' GROUP BY s.playerid HAVING time $char '\$playtime' ORDER BY stat DESC"; }
        if ($id == 2)  { return "SELECT p.playername, SUM(s.kills)/(SUM(TIME_TO_SEC(s.duration))/60) as stat, COUNT(*) as matches, SUM(TIME_TO_SEC(s.duration))/60/60 as time FROM contestors c, stats s, matches m, players p WHERE c.contestid = '\$id' AND s.playerid = c.playerid AND p.playerid = c.playerid AND m.matchid = s.matchid AND m.date >= '\$period1' AND m.date <= '\$period2' GROUP BY s.playerid HAVING time $char '\$playtime' ORDER BY stat DESC "; }
        if ($id == 3)  { return "SELECT p.playername, SUM(s.deaths)/(SUM(TIME_TO_SEC(s.duration))/60/60) as stat, COUNT(*) as matches, SUM(TIME_TO_SEC(s.duration))/60/60 as time FROM contestors c, stats s, matches m, players p WHERE c.contestid = '\$id' AND s.playerid = c.playerid AND p.playerid = c.playerid AND m.matchid = s.matchid AND m.date >= '\$period1' AND m.date <= '\$period2' GROUP BY s.playerid HAVING time $char '\$playtime' ORDER BY stat DESC "; }
        if ($id == 4)  { return "SELECT p.playername, SUM(s.assists)/(SUM(TIME_TO_SEC(s.duration))/60) as stat, COUNT(*) as matches, SUM(TIME_TO_SEC(s.duration))/60/60 as time FROM contestors c, stats s, matches m, players p WHERE c.contestid = '\$id' AND s.playerid = c.playerid AND p.playerid = c.playerid AND m.matchid = s.matchid AND m.date >= '\$period1' AND m.date <= '\$period2' GROUP BY s.playerid HAVING time $char '\$playtime' ORDER BY stat DESC "; }
        if ($id == 5)  { return "SELECT p.playername, SUM(s.souls)/(SUM(TIME_TO_SEC(s.duration))/60/60) as stat, COUNT(*) as matches, SUM(TIME_TO_SEC(s.duration))/60/60 as time FROM contestors c, stats s, matches m, players p WHERE c.contestid = '\$id' AND s.playerid = c.playerid AND p.playerid = c.playerid AND m.matchid = s.matchid AND m.date >= '\$period1' AND m.date <= '\$period2' GROUP BY s.playerid HAVING time $char '\$playtime' ORDER BY stat DESC "; }
        if ($id == 6)  { return "SELECT p.playername, SUM(s.npc)/(SUM(TIME_TO_SEC(s.duration))/60) as stat, COUNT(*) as matches, SUM(TIME_TO_SEC(s.duration))/60/60 as time FROM contestors c, stats s, matches m, players p WHERE c.contestid = '\$id' AND s.playerid = c.playerid AND p.playerid = c.playerid AND m.matchid = s.matchid AND m.date >= '\$period1' AND m.date <= '\$period2' GROUP BY s.playerid HAVING time $char '\$playtime' ORDER BY stat DESC "; }
        if ($id == 7)  { return "SELECT p.playername, SUM(s.healed)/(SUM(TIME_TO_SEC(s.duration))/60) as stat, COUNT(*) as matches, SUM(TIME_TO_SEC(s.duration))/60/60 as time FROM contestors c, stats s, matches m, players p WHERE c.contestid = '\$id' AND s.playerid = c.playerid AND p.playerid = c.playerid AND m.matchid = s.matchid AND m.date >= '\$period1' AND m.date <= '\$period2' GROUP BY s.playerid HAVING time $char '\$playtime' ORDER BY stat DESC "; }
        if ($id == 8)  { return "SELECT p.playername, SUM(s.repair)/(SUM(TIME_TO_SEC(s.duration))/60) as stat, COUNT(*) as matches, SUM(TIME_TO_SEC(s.duration))/60/60 as time FROM contestors c, stats s, matches m, players p WHERE c.contestid = '\$id' AND s.playerid = c.playerid AND p.playerid = c.playerid AND m.matchid = s.matchid AND m.date >= '\$period1' AND m.date <= '\$period2' GROUP BY s.playerid HAVING time $char '\$playtime' ORDER BY stat DESC "; }
        if ($id == 9)  { return "SELECT p.playername, SUM(s.bd)/(SUM(TIME_TO_SEC(s.duration))/60) as stat, COUNT(*) as matches, SUM(TIME_TO_SEC(s.duration))/60/60 as time FROM contestors c, stats s, matches m, players p WHERE c.contestid = '\$id' AND s.playerid = c.playerid AND p.playerid = c.playerid AND m.matchid = s.matchid AND m.date >= '\$period1' AND m.date <= '\$period2' GROUP BY s.playerid HAVING time $char '\$playtime' ORDER BY stat DESC "; }
        if ($id == 10) { return "SELECT MAX(streak) as stat, playername, sub.playerid, \$playtime as time, 0 as matches FROM players, (SELECT playerid, COUNT(*) as streak FROM (SELECT playerid, @r := @r + (COALESCE(@won, won) <> won) AS series, @won := won as win FROM( SELECT @r := 0, @won := NULL) vars, (SELECT c.playerid, IF(winner = team, 1, 0) as won FROM matches m, commanderstats c, contestors WHERE m.matchid = c.matchid AND m.date >= '\$period1' AND m.date < '\$period2' AND contestors.playerid = c.playerid AND contestors.contestid = '\$id' ORDER BY playerid, m.matchid) as sub) as sub WHERE sub.win = 1 GROUP BY series, playerid) as sub WHERE players.playerid = sub.playerid GROUP BY sub.playerid HAVING time $char '\$playtime' ORDER BY stat DESC;"; }

}

function get_corresponding_mysql_column_for_contest_functionid($id) {
        if ($id == 1)  { return "dmg"; }
        if ($id == 2)  { return "kills"; }
        if ($id == 3)  { return "deaths"; }
        if ($id == 4)  { return "assists"; }
        if ($id == 5)  { return "souls"; }
        if ($id == 6)  { return "npc"; }
        if ($id == 7)  { return "healed"; }
        if ($id == 8)  { return "repair"; }
        if ($id == 9)  { return "bd"; }
        if ($id == 10) { return "streak"; }
}


function match_is_played_over_period_days_ago_matchid($matchid, $period) {
        if (!match_exists($matchid)) { return false; }
        $played = get_date_of_matchid($matchid);
        $period = get_period($period);
        if ($played <= $period) { return true; }
        return false;
}


function get_total_lf_for_playerid($playerid) {
        $exp = get_total_commander_exp_for_playerid($playerid);
        $win = get_total_commander_winloss_ratio_for_playerid($playerid);
        if ($win <= 0.25) { $win = 0.25; }
        if ($win >= 0.75) { $win = 0.75; }
        $lf = number_format((1/2)*($cexpmin) + (1/4)*($cexpmin)*(4/5) + (1/4)*($cexpmin) * ($win));
        return $lf;
}

function get_matchids_for_30days() {
        $period = get_period();
        return get_matchids_for_period($period);
}

function echo_actionplayer_data_addrow_lines_for_clan_for_period($clan, $period) {
        $addrow1        = 'var row = data.addRow([';
        $addrow2        = ']);';
        $result1        = get_playernames_for_players_from_clan_who_has_been_playing_within_period($clan, $period);
        $result2        = get_sf_components_for_clan_for_period($clan, $period);
        $init           = array();
        $haystack       = array();
        $t_exp          = array();
        $t_time         = array();
        $_time          = 0;
        $_exp           = 0;
        while ($row1 = mysql_fetch_assoc($result1)) {
                $init = array_merge($init, array($row1['playername'] => 'null'));
                $t_exp[$row1['playername']]     = 0;
                $t_time[$row1['playername']]    = 0;
                $playername = $row1['playername'];
                echo "data.addColumn('number', '$playername');\n";
        }
        echo "data.addColumn('number', 'Average SF');\n";
        while ($row2 = mysql_fetch_assoc($result2)) {
                $matchid                 = $row2['matchid'];
                $playername              = $row2['playername'];
                $exp                     = $row2['exp'];
                $time                    = $row2['time'];
                $t_exp[$playername]     += $exp;
                $t_time[$playername]    += $time;
                $_exp                   += $exp;
                $_time                  += $time;
                if (isset($haystack[$matchid][$playername])) {
                        $haystack[$matchid][$playername] = number_format($t_exp[$playername]/$t_time[$playername]);

                } else {
                        $haystack[$matchid] = $init;
                        $haystack[$matchid]['t'] = number_format($_exp/$_time);
                        $haystack[$matchid][$playername] = number_format($t_exp[$playername]/$t_time[$playername]);

                }
        }
        $i = 0;
        foreach ($haystack as $match) {
                $i++;
                echo "$addrow1'$i',".implode($match, ",")."$addrow2\n";
        }
}

function echo_actionplayer_data_addrow_lines_for_playerid_for_period($playerid, $period) {
        $result = get_sfs_for_playerid_verbose($playerid, $period);
        $cexp  = 0;
        $ctime = 0;
        $i = 1;
        while ($row = mysql_fetch_assoc($result)) {
                $exp  = $row['exp'];
                $time = $row['time'];
                $cexp  += $exp;
                $ctime += $time;
                $match  = $i;
                $avg   = round($cexp/($ctime/60),0);
                $sf    = round($exp/($time/60),0);
                $matchid = $row['matchid'];
                echo "                                  var row = data.addRow([\"$match\", $sf, $avg]);\n";
                echo "                                  data.setRowProperty(row, \"matchid\", \"$matchid\");\n";
                $i++;
        }
}

function echo_commander_data_addrow_lines_for_playerid_for_period($playerid, $period) {
        $result = get_lf_components_for_playerid_for_period_verbose($playerid, $period);
        $cexp  = 0;
        $ctime = 0;
        $cwin  = 0;
	$clos  = 0;
        $i = 1;
        while ($row = mysql_fetch_assoc($result)) {

                $exp  = $row['exp'];
                $time = $row['time'];
                $wins  = $row['winned'];
		$loss = $row['lossed'];

                $cexp  += $exp;
                $ctime += $time;
                $cwin += $wins;
		$clos += $loss;

		$expmin = $row['exp'] / ($row['time']/60);
		$cexpmin = $cexp / ($ctime/60);
		if ($cwin == "0") {
			if ($wins == "1") { $cwin = 0.75; } else { $cwin = 0.25; }
		} else {
			$cwin = $cwin/($cwin+$clos);
		}
		if ($cwin <= 0.25) { $win = 0.25; }
		if ($cwin >= 0.75) { $win = 0.75; }
		$clf = number_format((1/2)*($cexpmin) + (1/4)*($cexpmin)*(4/5) + (1/4)*($cexpmin) * ($win));
		$lf  = number_format((1/2)*($expmin) + (1/4)*($expmin)*(4/5) + (1/4)*($expmin) * ($win));
                $match  = $i;
                $matchid = $row['matchid'];
                echo "                                  var row = data.addRow([\"$match\", $lf, $clf]);\n";
                echo "                                  data.setRowProperty(row, \"matchid\", \"$matchid\");\n";
                $i++;
        }
}

function echo_actionplayer_google_graph_for_clan_for_period($clan, $givenperiod) {
        echo '<div id="visualization_actionplayer" style="width: 700px; height: 300px; border: solid 1px;"><img src=images/loading.gif></div>'; flush();
        if ($givenperiod == 7) { $period = get_period($givenperiod); }
        if ($givenperiod == 15) { $period = get_period($givenperiod); }
        if ($givenperiod == 30) { $period = get_period($givenperiod); }
        if ($givenperiod == 60) { $period = get_period($givenperiod); }
        if ($givenperiod == 0) { $period = 0; }
        $matches    = get_distictive_number_of_actionplayer_matches_clan_has_played_for_period($clan, $period);
        $dates      = get_actionplayer_date_range_of_matches_in_period_for_clan($clan, $period);
        if ($matches == 0) {
                $_SESSION['jobs']['clan_action_players_no_stats']     = "jquery_toggle_element(\"#action_player_no_stats\");";
                echo "<div id=action_player_no_stats style=\"display: none;\"><img src=images/no_stats_for_given_period.jpg style=\"border: solid 1px;\"></div>"; return 0;
        } else {
                // echo '<div id="visualization_actionplayer" style="width: 700px; height: 300px; border: solid 1px; display: none"></div>';
                $_SESSION['jobs']['clan_action_player_google_chart'] = "late_echo_actionplayer_google_graph_for_clan_for_period(\"$clan\", \"$period\", \"$matches\", \"$dates[0]\", \"$dates[1]\");";

        }
}

function echo_commander_google_graph_for_clan_for_period($clan, $period) {

}

function echo_actionplayer_google_graph_for_playerid_for_period($playerid, $givenperiod) {
        if ($givenperiod == 7) { $period = get_period($givenperiod); }
        if ($givenperiod == 15) { $period = get_period($givenperiod); }
        if ($givenperiod == 30) { $period = get_period($givenperiod); }
        if ($givenperiod == 60) { $period = get_period($givenperiod); }
        if ($givenperiod == 0) { $period = 0; }
        $playername = playerid_to_playername($playerid);
        $matches    = get_number_of_actionplayer_matches_for_playerid_for_period($playerid, $period);
        $dates      = get_actionplayer_date_range_of_matches_in_period_for_playerid($playerid, $period);
        if ($matches == 0) {
                $_SESSION['jobs']['action_player_no_stats']     = "jquery_toggle_element(\"#action_player_no_stats\");";
                echo "<div id=action_player_no_stats style=\"display: none;\"><img src=images/no_stats_for_given_period.jpg style=\"border: solid 1px;\"></div>"; return 0;
        }
        echo '<div id="visualization_actionplayer" style="width: 700px; height: 300px; border: solid 1px; display: none"></div>';
        $_SESSION['jobs']['action_player_google_chart'] = "late_echo_actionplayer_google_graph_for_playerid_for_period(\"$playerid\", \"$period\", \"$playername\", \"$matches\", \"$dates[0]\", \"$dates[1]\");";
}

function echo_commander_google_graph_for_playerid_for_period($playerid, $givenperiod) {
        if ($givenperiod == 7) { $period = get_period($givenperiod); }
        if ($givenperiod == 15) { $period = get_period($givenperiod); }
        if ($givenperiod == 30) { $period = get_period($givenperiod); }
        if ($givenperiod == 60) { $period = get_period($givenperiod); }
        if ($givenperiod == 0) { $period = 0; }
        $playername = playerid_to_playername($playerid);
        $matches    = get_number_of_commander_matches_for_playerid_for_period($playerid, $period);
        $dates      = get_commander_date_range_of_matches_in_period_for_playerid($playerid, $period);
        if ($matches == 0) { 
                $_SESSION['jobs']['commander_no_stats']     = "jquery_toggle_element(\"#commander_no_stats\");";
                echo "<div id=commander_no_stats style=\"display: none;\"><img src=images/no_stats_for_given_period.jpg style=\"border: solid 1px;\"></div>"; return 0;
        }
        echo '<div id="visualization_commander" style="width: 700px; height: 300px; border: solid 1px; display: none;"></div>';
        $_SESSION['jobs']['commander_google_chart'] = "late_echo_commander_google_graph_for_playerid_for_period(\"$playerid\", \"$period\", \"$playername\", \"$matches\", \"$dates[0]\", \"$dates[1]\");";

}


function echo_draw_sf_graph_for_playerid_for_period($playerid, $playername, $period) {
	echo "<img src=\"parser/echo_draw_30days_sf_graph_for_playerid.php?action=sf&playerid=$playerid&playername=$playername&period=$period\">";
}

function echo_draw_30days_sf_graph_for_playerid($playerid, $playername) {
	echo "<img src=\"parser/echo_draw_30days_sf_graph_for_playerid.php?action=sf&playerid=$playerid&playername=$playername&period=30\">";
}
function echo_draw_30days_lf_graph_for_playerid($playerid, $playername) {
	echo "<img src=\"parser/echo_draw_30days_lf_graph_for_playerid.php?action=sf&playerid=$playerid&playername=$playername\">";
}

function echo_clan_stats_period_options($clan, $period) {
        echo "Stats for: <select name=clan_stats_period onchange=\"change_clan_stats_period('$clan', this.value)\">";
                if ($period==7) {
                        echo "<option value=7 selected>7days</option>";
                } else {
                        echo "<option value=7>7days</option>";
                } if ($period==15) {
                        echo "<option value=15 selected>15days</option>";
                } else {
                        echo "<option value=15>15days</option>";
                }
                if ($period==30) {
                        echo "<option value=30 selected>30days</option>";
                } else {
                        echo "<option value=30>30days</option>";
                }
        echo "</select><br>";
}

function echo_last_update_for_profile_element($playerid) {
        if ($_SESSION['playerid'] != $playerid) { return 0; }
        $date = get_date_of_last_match_for_playerid($playerid);
        $time = format_last_login_time_to_neat_format_from_minutes(ceil((time() - $date)/60));
        echo "<font color=blue>Last match update: $time ago</font><br>
        <form method=GET action=stats_files/match_deploy.php>
        <input type=hidden name=q value=1>
        <input type=hidden name=url value=\"http://savage.boubbin.org/index.php?action=stats&playerid=$_SESSION[playerid]&playername=$_SESSION[playername]&period=30\">
        <input type=submit value=\"Update now\"><br>
        <form>";


}

function get_date_of_last_match_for_playerid($playerid) {
        $result = get_last_matches_for_playerid($playerid, 1);
        $row = mysql_fetch_assoc($result);
        $date = $row['date'];
        return $date;
}

function echo_individual_stats_period_options($playerid, $playername, $period) {
        echo "<div class=warning>Stats for: <select name=commander_graph_period onchange=\"change_individual_stats_period($playerid, '$playername', this.value)\">";
                if ($period==7) {
                        echo "<option value=7 selected>7days</option>";
                } else {
                        echo "<option value=7>7days</option>";
                }
                if ($period==15) {
                        echo "<option value=15 selected>15days</option>";
                } else {
                        echo "<option value=15>15days</option>";
                }
                if ($period==30) {
                        echo "<option value=30 selected>30days</option>";
                } else {
                        echo "<option value=30>30days</option>";
                }
                if ($period==60) {
                        echo "<option value=60 selected>60days</option>";
                } else {
                        echo "<option value=60>60days</option>";
                }
                 if ($period==0) {
                        echo "<option value=0 selected>Parser lifetime</option>";
                } else {
                        echo "<option value=0>Parser Lifetime</option>";
                }
        echo "</select><br>";
        if ($_SESSION['playerid'] == $playerid) { echo_last_update_for_profile_element($playerid); }
        echo "</div>";
}


function echo_table_row($array) {
	foreach ($array as $var => $key) {
		echo "<td></td><td>$key</td>";
	}
}

function echo_30days_playmates_for_playerid($playerid) {
	echo_table_header_with_topic("<b>Most played with<b>", "calender.png", "0");
	$rows = get_30days_playmates_for_playerid($playerid);
	$i = 0;
	while ($row = mysql_fetch_assoc($rows)) {
		$i++;
		$pname = $row['playername'];
		$sname = $row['times'];
		if (is_odd($i)) {
			echo "<tr class=odd><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		} else {
			echo "<tr ><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		}
	}
	echo "</tbody></table></div>";
}

function echo_played_maps_for_playerid_for_period($playerid, $period = 0) {
	echo_table_header_with_topic("<b>Most played maps<b>", "maps.png", "0");
	$rows = get_played_maps_for_playerid_for_period($playerid, 0);
	$i = 0;
	while ($row = mysql_fetch_assoc($rows)) {
		$i++;
		$pname = $row['map'];
		$sname = $row['times'];
		if (is_odd($i)) {
			echo "<tr class=odd><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		} else {
			echo "<tr ><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		}
	}
	echo "</tbody></table></div>";	
}

function echo_player_stats_page($playerid, $playername) {
        $playerid   = mysql_escape($playerid);
        $playername = mysql_escape($playername);
        $period = $_GET['period'];
        $label = $period." days";
        if ($period==0) { $label = "Parser lifetime"; }
        echo_player_search_element();
        echo_individual_stats_period_options($playerid, $playername, $period);
	echo "<div id=stat_header>Spawned units</div>";
        echo "<div id=player_match_info_mouseover></div>";
        echo "<div id=commander_match_info_mouseover></div>";;
        //echo_individual_playcount_for_units_for_playerid_for_period($playerid, $period);
        echo "<div id=stat_header>Overall statistics and graphs</div>";
	echo "<table border=0>";
	echo "<tr>";
	echo "<td>"; echo_actionplayer_stats_for_playerid_for_period($playerid, $playername, $period); echo "</td>";
	echo "<td>"; echo_actionplayer_google_graph_for_playerid_for_period($playerid, $period); echo "</td>";
        echo "</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>"; echo_commander_stats_for_playerid_for_period($playerid, $playername, $period); echo "</td>";
	echo "<td>"; echo_commander_google_graph_for_playerid_for_period($playerid, $period); echo "</td>";
	echo "</tr>";
	echo "</table>";
        echo "<div id=stat_header>Event based statistics</div>";
        //echo_event_based_stats_for_playerid_for_period($playerid, $period);
	echo "<br><br><h1><span>30days Other statistics</span></h1>";
        echo_misc_stats_about_player($playerid, $playername);
	echo "<table border=1>
                <tr><td>";
                        echo_played_maps_for_playerid_for_period($playerid, 2500);
        echo "</td><td>";
                        echo_30days_playmates_for_playerid($playerid);
        echo "</td></tr>";
	echo "</table>";
}

function number_of_php_files() {
	$files = exec('find . -name "*.php" -exec echo {} \; | wc -l');
	return $files;
}

function number_of_php_functions() {
	$functions = exec('find . -name "*.php" -exec cat {} \; | grep ^function | wc -l');
	return $functions;
}

function number_of_php_codelines_and_chars() {
	$lines = exec('find . -name "*.php" -exec cat {} \; | wc -l');
	$chars = exec('find . -name "*.php" -exec cat {} \; | wc -c');
	return "$lines ($chars characters)";
}

function number_of_css_codelines_and_chars() {
	$lines = exec('find . -name "*.css" -exec cat {} \; | wc -l');
	$chars = exec('find . -name "*.css" -exec cat {} \; | wc -c');
	return "$lines ($chars characters)";
}

function mysql_version() {
	open_mysql_connection();
	$mysql_version = mysql_get_server_info();
	mysql_close();
	return $mysql_version;
}

function apacheversion() {
	$ver = explode("/", $_SERVER['SERVER_SOFTWARE']);
	$apver = "$ver[1]";
	return $apver;;
}

function print_about_service_page() {
	echo "	
		<table>
		<h3>Enviroment</h3>

			<tr><td>PHP</td><td>".phpversion()."</td></tr>
			<tr><td>MYSQL</td><td>".mysql_version()."</td><tr>
			<tr><td>Apache</td><td>".apacheversion()."</td><tr>
			<tr><td>OS</td><td>".PHP_OS."</td><tr>
		</table>
		<table>
		<tr><td><h3>MYSQL</td><td></td></tr>
			";
			$results = get_database_stats();
			while ($row = mysql_fetch_assoc($results)) {
				echo "<tr><td>$row[table_name]</td><td>".number_format($row['table_rows'],0)." rows</td><td>$row[size] MB</td></tr>";
			}
			echo "</table>";
	echo "
		</table>
		<h3>PHP+HTML+CSS+JavaScript</h3>
		<table>

			<tr><td>PHP-files</td><td>".number_of_php_files()."</td></tr>
			<tr><td>PHP-functions</td><td>".number_of_php_functions()."</td></tr>
			<tr><td>PHP-codelines</td><td>".number_of_php_codelines_and_chars()."</td></tr>
			<tr><td>CSS-codelines</td><td>".number_of_css_codelines_and_chars()."</td></tr>

		</table>
	";

}


function echo_database_stats() {
	echo "<font color=black>";
	print_about_service_page();
}

function echo_update_options() {
	echo '<h1><span>Update stats for players</span></h1>
			<script language="javascript">
			function update(playerid) { 
				//alert(playerid);
				this.location.href = "index.php?action=updater&userid="+playerid+"&pass=yeah";
			}
			</script>';
	echo "<div id=foot_text>CAUTION!<br> This procedure is highly timetaking, press buttons only once!<br>Updating is only availaible once in 2minutes</div>";
	echo "	<div id=ladder>
			Next automatic updated will be issued in: 3hours 2mins<br>
			<table>
			<tbody><td>Update stats</td><td></td><td></td>
			</td>";
	$rows = get_vip_players("ORDER BY lastupdate ASC");
	$i = 0;
	$disableall = "disabled";
	while ($row = mysql_fetch_assoc($rows)) {
		$i++;
		$playername = $row['playername'];
		$playerid 	= $row['playerid'];		
		$updated    = $row['lastupdate'];
		$disabled   = "disabled";
		$js			= "onclick=\"update(this.name)\"";
		$passed     = time() - $updated;
		if (is_null($updated)) { $disabled = ""; $disableall = ""; $updated = "never"; }
		else if ($passed > 36000) { $disabled = ""; $disableall = ""; $updated = "long time ago"; }
		else if ($passed > 28800) { $disabled = ""; $disableall = ""; $updated = "more than 8hours ago"; }
		else if ($passed > 21600) { $disabled = ""; $disableall = ""; $updated = "more than 6hours ago"; }
		else if ($passed > 14400) { $disabled = ""; $disableall = ""; $updated = "more than 4hours ago"; }
		else if ($passed > 7200) { $disabled = ""; $disableall = ""; $updated = "more than 2hours ago"; }
		else if ($passed > 3600) { $disabled = ""; $disableall = ""; $updated = "+1hour ago"; }	
		else if ($passed > 1800) { $disabled = ""; $disableall = ""; $updated = "+30 minutes ago"; }
		else if ($passed > 600) { $disabled = ""; $disableall = ""; $updated = "+10 minutes ago"; }
		else if ($passed > 120) { $disabled = ""; $disableall = ""; $updated = "few minutes ago"; }
		else if ($passed < 121) { $updated = "$passed seconds ago"; }
		if (!is_odd($i)) {
			echo "<tr><td>$playername</td><td>$updated</td><td><input type=submit name=$playerid value=Update $disabled $js></td></tr>";
		} else {
			echo "<tr class=even><td>$playername</td><td>$updated</td><td><input type=submit name=$playerid value=Update $disabled $js></td></tr>";

		}
	}
	echo "</table></div>";
	echo "<input type=submit name=all value=\"Update all\" disabled onclick=\"update('all')\">";
}

function echo_table_header_with_topic($topic, $img = "", $width = "205") {
	echo "\n	<td width=$width>
			<div id=table>
			<table border=0 summary=\"$topic\"><caption><img src=\"images/stats/$img\"></caption><thead><tr><th scope=col>#</th><th scope=col>Playername</th><th scope=col=20>Stat</th></tr></thead>
			<tbody><th scope=row><td width=$width>$topic</td><td width=100>
		\n	</td>
	";

}

function get_html_table_header_with_topic($topic, $img = "", $width = "205") {
	return "\n	<td width=$width>
			<div id=table>
			<table border=0 summary=\"$topic\"><caption><img src=\"images/stats/$img\"></caption><thead><tr><th scope=col>#</th><th scope=col>Playername</th><th scope=col=20>Stat</th></tr></thead>
			<tbody><th scope=row><td width=$width>$topic</td><td width=100>
		\n	</td>
	";

}


function echo_ordered_summary_of_stats() {
	if (!isset($_GET['view'])) {
		$view = 1;
	} else if ($_GET['view']==2) {
		$view = 2;
	} else {
		$view = 1;
	}
	if (!isset($_GET['ladder'])) {
		$ladder = 1;
	} else if ($_GET['ladder']==2) {
		$ladder = 2;
	} else {
		$ladder = 1;
	}
	echo '
		<script language="javascript">
			function choose(view, ladder) { 
				// if (view==2) {�alert("Binding does not currently work in ladder view mode"); }
				this.location.href = "index.php?action=stats&view="+view+"&ladder="+ladder;
			}
			function ladder(ladder, view) { 
				// alert(ladder);
				//this.location.href = "index.php?action=stats&view="+view+"&ladder="+ladder;
			}
		</script>
	';
	echo "
	<h1><span>ACTION PLAYER LADDER</span></h1>
	Settings:
	<select onchange=\"choose(this.value, $ladder)\">
	";
	if ($view=='1') { 
		echo "
			<option value=1 selected>Ignore playtime when displaying stats</option>
			<option value=2>Bind stats to the time played (kills/min etc)</option>
			</select>
		";
	} else {
		echo "
			<option value=1>Ignore playtime when displaying stats</option>
			<option value=2 selected>Bind stats to the time played (kills/min etc)</option>
			</select>
		";
	}

	if ($ladder==2) {
		if (!isset($_GET['sort'])) { $sort = "sf"; } else { $sort = $_GET['sort']; }
		if ($view == 1) { echo_ladder($view, $sort); }
		else { echo_ladder_time_binded($view, $sort); }
	}
}

function echo_ladder_time_binded($view, $ord) {
	echo_ladder_header($view);
	$rows = get_time_binded_ladder_ordered_by($ord);
	$i = 0;
	while ($row = mysql_fetch_assoc($rows)) {
		if ($ord == "playername") { $_playername = "<td class=ordered>"; } else { $_playername = "<td>"; }
		if ($ord == "sf") { $sf = "<td class=ordered>"; } else { $sf = "<td>"; }
		if ($ord == "time") { $time = "<td class=ordered>"; } else { $time = "<td>"; }
		if ($ord == "exp") { $exp = "<td class=ordered>"; } else { $exp = "<td>"; }
		if ($ord == "kills") { $kills = "<td class=ordered>"; } else { $kills = "<td>"; }
		if ($ord == "souls") { $souls = "<td class=ordered>"; } else { $souls = "<td>"; }
		if ($ord == "deaths") { $deaths = "<td class=ordered>"; } else { $deaths = "<td>"; }
		if ($ord == "kd") { $kd = "<td class=ordered>"; } else { $kd = "<td>"; }
		if ($ord == "healed") { $healed = "<td class=ordered>"; } else { $healed = "<td>"; }
		if ($ord == "res") { $res = "<td class=ordered>"; } else { $res = "<td>"; }
		if ($ord == "gold") { $gold = "<td class=ordered>"; } else { $gold = "<td>"; }
		if ($ord == "repair") { $repair = "<td class=ordered>"; } else { $repair = "<td>"; }
		if ($ord == "razed") { $razed = "<td class=ordered>"; } else { $razed = "<td>"; }
		if ($ord == "dmg") { $dmg = "<td class=ordered>"; } else { $dmg = "<td>"; }
		if ($ord == "bd") { $bd = "<td class=ordered>"; } else { $bd = "<td>"; }
		foreach ($row as $key => $value) { $$key = $value; }
		$i++;
		// $pname = preg_replace("/\[.*\]/i", "", $row['playername']);
		// $playername _sf _time _exp _kills _assists _deaths _kd _healed _res _gold _repair _razed _dmg _bdsname   = number_format(round($row['avg'],2),2);
		//$_playername	.= $playername;
                $playerid        = $playerid;
		$_playername	.= "<a href=index.php?action=stats&playerid=$playerid&playername=$playername&period=30>".preg_replace("/\[.*\]/i", "", $playername)."</a>";
		$sf		.= number_format(round($_sf,2),2);
		$time		.= number_format(round($_time,2),2);
		$exp		.= number_format($_exp);
		$kills		.= number_format($_kills ,2);
		$souls	 	.= number_format($_souls, 2);
		$deaths		.= number_format($_deaths, 2);
		$kd 		.= number_format(round($_kd,2),2);
		$healed 	.= number_format($_healed, 2);
		$res 		.= number_format($_res, 2);
		$gold		.= number_format($_gold, 2);
		$repair		.= number_format($_repair, 2);
		$razed		.= number_format($_razed, 2);
		$dmg 		.= number_format($_dmg);
		$bd		.= number_format($_bd);
		if (is_odd($i)) {
			echo "<tr class=even><td>$i.</td>$_playername</a></td>$sf</td>$exp</td>$time</td>$kills</td>$souls</td>$deaths</td>$kd</td>$healed</td>$res</td>$gold</td>$repair</td>$razed</td>$dmg</td>$bd</td></tr>";
		} else {
			echo "<tr><td>$i.</td>$_playername</a></td>$sf</td>$exp</td>$time</td>$kills</td>$souls</td>$deaths</td>$kd</td>$healed</td>$res</td>$gold</td>$repair</td>$razed</td>$dmg</td>$bd</td></tr>";

		}
	}
	echo "</tbody></table></div>";
}

function echo_ladder($view, $ord) {
	echo_ladder_header($view);
	$rows = get_ladder_ordered_by($ord);
        $i = 0;
	while ($row = mysql_fetch_assoc($rows)) {
		if ($ord == "playername") { $_playername = "<td class=ordered>"; } else { $_playername = "<td>"; }
		if ($ord == "sf") { $sf = "<td class=ordered>"; } else { $sf = "<td>"; }
		if ($ord == "time") { $time = "<td class=ordered>"; } else { $time = "<td>"; }
		if ($ord == "exp") { $exp = "<td class=ordered>"; } else { $exp = "<td>"; }
		if ($ord == "kills") { $kills = "<td class=ordered>"; } else { $kills = "<td>"; }
		if ($ord == "souls") { $souls = "<td class=ordered>"; } else { $souls = "<td>"; }
		if ($ord == "deaths") { $deaths = "<td class=ordered>"; } else { $deaths = "<td>"; }
		if ($ord == "kd") { $kd = "<td class=ordered>"; } else { $kd = "<td>"; }
		if ($ord == "healed") { $healed = "<td class=ordered>"; } else { $healed = "<td>"; }
		if ($ord == "res") { $res = "<td class=ordered>"; } else { $res = "<td>"; }
		if ($ord == "gold") { $gold = "<td class=ordered>"; } else { $gold = "<td>"; }
		if ($ord == "repair") { $repair = "<td class=ordered>"; } else { $repair = "<td>"; }
		if ($ord == "razed") { $razed = "<td class=ordered>"; } else { $razed = "<td>"; }
		if ($ord == "dmg") { $dmg = "<td class=ordered>"; } else { $dmg = "<td>"; }
		if ($ord == "bd") { $bd = "<td class=ordered>"; } else { $bd = "<td>"; }
		foreach ($row as $key => $value) { $$key = $value; }
		$i++;
		// $_playername = preg_replace("/\[.*\]/i", "", $playername);
		// $playername _sf _time _exp _kills _assists _deaths _kd _healed _res _gold _repair _razed _dmg _bdsname   = number_format(round($row['avg'],2),2);
		// $_playername	.= $playername;
                $playerid        = $playerid;
                $link            = "<a href=index.php?action=stats&playerid=$playerid&playername=$playername&period=30>";
		$_playername	.= "<a href=index.php?action=stats&playerid=$playerid&playername=$playername&period=30>".preg_replace("/\[.*\]/i", "", $playername)."</a>";
		$sf		.= number_format(round($_sf,2),2);
		$time		.= number_format(round($_time,2),2);
		$exp		.= number_format($_exp);
		$kills		.= number_format($_kills);
		$souls	 	.= number_format($_souls);
		$deaths		.= number_format($_deaths);
		$kd 		.= number_format(round($_kd,2),2);
		$healed 	.= number_format($_healed);
		$res 		.= $_res;
		$gold		.= number_format($_gold);
		$repair		.= number_format($_repair);
		$razed		.= $_razed;
		$dmg 		.= number_format($_dmg);
		$bd		.= number_format($_bd);
		if (is_odd($i)) {
			echo "<tr class=even><td>$i.</td>$_playername</td>$sf</td>$exp</td>$time</td>$kills</td>$souls</td>$deaths</td>$kd</td>$healed</td>$res</td>$gold</td>$repair</td>$razed</td>$dmg</td>$bd</td></tr>";
		} else {
			echo "<tr><td>$i.</td>$_playername</td>$sf</td>$exp</td>$time</td>$kills</td>$souls</td>$deaths</td>$kd</td>$healed</td>$res</td>$gold</td>$repair</td>$razed</td>$dmg</td>$bd</td></tr>";

		}
		flush();
	}
	echo "</tbody></table></div>";
}

function echo_ladder_header($view) {
	if ($view == 1) {
		echo "<div id=ladder><table cellspacing=0>
			<thead>
			<tr>
				<td>#</td>
				<td><href=index.php?action=stats&view=$view&ladder=2&sort=playername>Player</a></td>
				<td><a href=index.php?action=stats&view=$view&ladder=2&sort=sf>SF</a></td>
				<td><a href=index.php?action=stats&view=$view&ladder=2&sort=exp>Exp</a></td>
				<td><a href=index.php?action=stats&view=$view&ladder=2&sort=time>Time</a></td>
				<td><a href=index.php?action=stats&view=$view&ladder=2&sort=kills>Kills</a></td>
				<td><a href=index.php?action=stats&view=$view&ladder=2&sort=souls>Souls</a></td>
				<td><a href=index.php?action=stats&view=$view&ladder=2&sort=deaths>Deaths</a></td>
				<td><a href=index.php?action=stats&view=$view&ladder=2&sort=kd>K:D</a></td>
				<td><a href=index.php?action=stats&view=$view&ladder=2&sort=healed>Healed</a></td>
				<td><a href=index.php?action=stats&view=$view&ladder=2&sort=res>Res</a></td>
				<td><a href=index.php?action=stats&view=$view&ladder=2&sort=gold>Gold</a></td>
				<td><a href=index.php?action=stats&view=$view&ladder=2&sort=repair>Repair</a></td>
				<td><a href=index.php?action=stats&view=$view&ladder=2&sort=razed>Razed</a></td>
				<td><a href=index.php?action=stats&view=$view&ladder=2&sort=dmg>Dmg</a></td>
				<td><a href=index.php?action=stats&view=$view&ladder=2&sort=bd>BD</a></td>
			</tr>
			</thead>
			<tbody>";
	} else {
		echo "<div id=ladder><table cellspacing=0>
			<thead>
			<tr>
				<td>#</td>
				<td><href=index.php?action=stats&view=$view&ladder=2&sort=playername>Player</a></td>
				<td><a href=index.php?action=stats&view=$view&ladder=2&sort=sf>SF</a></td>
				<td><a href=index.php?action=stats&view=$view&ladder=2&sort=exp>Exp</a></td>
				<td><a href=index.php?action=stats&view=$view&ladder=2&sort=time>Time</a></td>
				<td><a href=index.php?action=stats&view=$view&ladder=2&sort=kills>Kills/m</a></td>
				<td><a href=index.php?action=stats&view=$view&ladder=2&sort=souls>Souls/h</a></td>
				<td><a href=index.php?action=stats&view=$view&ladder=2&sort=deaths>Deaths/h</a></td>
				<td><a href=index.php?action=stats&view=$view&ladder=2&sort=kd>K:D</a></td>
				<td><a href=index.php?action=stats&view=$view&ladder=2&sort=healed>Healed/m</a></td>
				<td><a href=index.php?action=stats&view=$view&ladder=2&sort=res>Res/h</a></td>
				<td><a href=index.php?action=stats&view=$view&ladder=2&sort=gold>Gold/m</a></td>
				<td><a href=index.php?action=stats&view=$view&ladder=2&sort=repair>Repair/m</a></td>
				<td><a href=index.php?action=stats&view=$view&ladder=2&sort=razed>Razed/h</a></td>
				<td><a href=index.php?action=stats&view=$view&ladder=2&sort=dmg>Dmg/m</a></td>
				<td><a href=index.php?action=stats&view=$view&ladder=2&sort=bd>BD/m</a></td>
			</tr>
			</thead>
			<tbody>";
	}
}


/* highest stats ingame ends */

function get_html_highest_sf_in_game_table($period) {
	$line = get_html_table_header_with_topic("Highest SF", "rank.png");
	$rows = get_average_highest_sf_for_all_vips($period);
	$i = 0;
	while ($row = mysql_fetch_assoc($rows)) {
		$i++;
		$pname = preg_replace("/\[.*\]/i", "", $row['playername']);
		$sname   = number_format(round($row['avg'],2),2);
		if ($i < 4) { $pname = "<font color=blue>$pname</font>"; $sname = "<font color=blue>$sname</font>";}
		if (is_odd($i)) {
			$line .= "<tr class=odd><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		} else {
			$line .= "<tr ><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		}
	}
	$line .= "</tbody></table></div></td>";
        return $line;
}
function get_html_most_kills_in_game_table($period) {
	$line  = get_html_table_header_with_topic("Most kills", "kills.png");
	$rows = get_most_kills_in_game($period);
	$i = 0;
	while ($row = mysql_fetch_assoc($rows)) {
		$i++;
		$pname    = preg_replace("/\[.*\]/i", "", $row['playername']);
		$sname   = number_format($row['kills']);
		if ($i < 4) { $pname = "<font color=blue>$pname</font>"; $sname = "<font color=blue>$sname</font>";}
		if (is_odd($i)) {
			$line .= "<tr class=odd><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		} else {
			$line .=  "<tr ><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		}
	}
	$line .=  "</tbody></table></div>";
        return $line;
}
function get_html_most_healed_in_game_table($period) {
	$line = get_html_table_header_with_topic("Most healed", "healed.png");
	$rows = get_most_heals_in_game($period);
	$i = 0;
	while ($row = mysql_fetch_assoc($rows)) {
		$i++;
		$pname = preg_replace("/\[.*\]/i", "", $row['playername']);
		$sname   = number_format($row['healed']);
		if ($i < 4) { $pname = "<font color=blue>$pname</font>"; $sname = "<font color=blue>$sname</font>";}
		if (is_odd($i)) {
			$line .=  "<tr class=odd><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		} else {
			$line .=  "<tr ><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		}
	}
	$line .=  "</tbody></table></div>";
        return $line;
}
function get_html_best_kd_in_game_table($period) {
	$line = get_html_table_header_with_topic("Best KD-ratio", "kd.png");
	$stat = "kd";
	$rows = get_best_kd($period);
	$i = 0;
	while ($row = mysql_fetch_assoc($rows)) {
		$i++;
		$pname    = preg_replace("/\[.*\]/i", "", $row['playername']);
		$sname    = number_format(round($row[$stat],2),1).":1";
		if ($i < 4) { $pname = "<font color=blue>$pname</font>"; $sname = "<font color=blue>$sname</font>";}
		if (is_odd($i)) {
			$line .=  "<tr class=odd><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		} else {
			$line .=  "<tr ><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		}
	}
	$line .=  "</tbody></table></div>";
        return $line;
}

function get_html_most_bd_in_game_table($period) {
	$line = get_html_table_header_with_topic("Most BD", "bd.png");
	$stat = "bd";
	$rows = get_most_bd($period);
	$i = 0;
	while ($row = mysql_fetch_assoc($rows)) {
		$i++;
		$pname    = preg_replace("/\[.*\]/i", "", $row['playername']);
		$sname   = number_format($row[$stat]);
		if ($i < 4) { $pname = "<font color=blue>$pname</font>"; $sname = "<font color=blue>$sname</font>";}
		if (is_odd($i)) {
			$line .=  "<tr class=odd><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		} else {
			$line .=  "<tr ><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		}
	}
	$line .=  "</tbody></table></div>";
        return $line;
}

function get_html_most_repaired_in_game_table($period) {
	$line = get_html_table_header_with_topic("Most repaired", "repair.png");
	$stat = "repair";
	$rows = get_most_repaired($period);
	$i = 0;
	while ($row = mysql_fetch_assoc($rows)) {
		$i++;
		$pname    = preg_replace("/\[.*\]/i", "", $row['playername']);
		$sname   = number_format($row[$stat]);
		if ($i < 4) { $pname = "<font color=blue>$pname</font>"; $sname = "<font color=blue>$sname</font>";}
		if (is_odd($i)) {
			$line .=  "<tr class=odd><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		} else {
			$line .=  "<tr ><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		}
	}
	$line .=  "</tbody></table></div>";
        return $line;
}

function get_html_most_souls_in_game($period) {
	$line = get_html_table_header_with_topic("Most souls", "souls.png");
	$stat = "souls";
	$rows = get_most_souls_in_game($period);
	$i = 0;
	while ($row = mysql_fetch_assoc($rows)) {
		$i++;
		$pname    = preg_replace("/\[.*\]/i", "", $row['playername']);
		$sname   = number_format($row[$stat]);
		if ($i < 4) { $pname = "<font color=blue>$pname</font>"; $sname = "<font color=blue>$sname</font>";}
		if (is_odd($i)) {
			$line .=  "<tr class=odd><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		} else {
			$line .=  "<tr ><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		}
	}
	$line .=  "</tbody></table></div>";
        return $line;
}
function get_html_deaths_in_game_table($period) {
	$line = get_html_table_header_with_topic("Most deaths", "deaths.png" );
	$rows = get_most_deaths_in_game($period);
	$stat = "deaths";
	$i = 0;
	while ($row = mysql_fetch_assoc($rows)) {
		$i++;
		$pname = preg_replace("/\[.*\]/i", "", $row['playername']);
		$sname   = number_format($row[$stat]);
		if ($i < 4) { $pname = "<font color=blue>$pname</font>"; $sname = "<font color=blue>$sname</font>";}
		if (is_odd($i)) {
			$line .=  "<tr class=odd><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		} else {
			$line .=  "<tr ><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		}
	}
	$line .=  "</tbody></table></div>";
        return $line;
}

/* highest stats ingame ends */


function echo_top_souls_table($view) {
	echo_table_header_with_topic("Top souls", "souls.png");
	$stat = "souls";
	$rows = get_30days_top_souls($view);
	$i = 0;
	while ($row = mysql_fetch_assoc($rows)) {
		$i++;
		$pname    = preg_replace("/\[.*\]/i", "", $row['playername']);
		if ($view==2) {
			$sname   = number_format(round($row[$stat],2),2)."/h";
		} else {
			$sname   = number_format($row[$stat]);
		}
		if ($i < 4) { $pname = "<font color=blue>$pname</font>"; $sname = "<font color=blue>$sname</font>";}
		if (is_odd($i)) {
			echo "<tr class=odd><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		} else {
			echo "<tr ><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		}
	}
	echo "</tbody></table></div>";
}

function echo_top_bd_table($view) {
	echo_table_header_with_topic("Top BD", "bd.png");
	$stat = "bd";
	$rows = get_30days_top_bd($view);
	$i = 0;
	while ($row = mysql_fetch_assoc($rows)) {
		$i++;
		$pname    = preg_replace("/\[.*\]/i", "", $row['playername']);
		if ($view==2) {
			$sname   = number_format(round($row[$stat],2),2)."/h";
		} else {
			$sname   = number_format($row[$stat]);
		}
		if ($i < 4) { $pname = "<font color=blue>$pname</font>"; $sname = "<font color=blue>$sname</font>";}
		if (is_odd($i)) {
			echo "<tr class=odd><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		} else {
			echo "<tr ><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		}
	}
	echo "</tbody></table></div>";


}

function echo_top_repair_table($view) {
	echo_table_header_with_topic("Top repair", "repair.png");
	$stat = "repair";
	$rows = get_30days_top_repair($view);
	$i = 0;
	while ($row = mysql_fetch_assoc($rows)) {
		$i++;
		$pname    = preg_replace("/\[.*\]/i", "", $row['playername']);
		if ($view==2) {
			$sname   = number_format(round($row[$stat],2),2)."/h";
		} else {
			$sname   = number_format($row[$stat]);
		}
		if ($i < 4) { $pname = "<font color=blue>$pname</font>"; $sname = "<font color=blue>$sname</font>";}
		if (is_odd($i)) {
			echo "<tr class=odd><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		} else {
			echo "<tr ><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		}
	}
	echo "</tbody></table></div>";
}

function echo_top_hours_table($view) {
	echo_table_header_with_topic("Top playtime", "hours.png");
	$stat = "hours";
	$rows = get_30days_top_hours($view);
	$i = 0;
	while ($row = mysql_fetch_assoc($rows)) {
		$i++;
		$pname    = preg_replace("/\[.*\]/i", "", $row['playername']);
		$sname    = number_format(round($row[$stat],2),2);
		if ($i < 4) { $pname = "<font color=blue>$pname</font>"; $sname = "<font color=blue>$sname</font>";}
		if (is_odd($i)) {
			echo "<tr class=odd><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		} else {
			echo "<tr ><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		}
	}
	echo "</tbody></table></div>";
}

function echo_top_kd_table($view) {
	echo_table_header_with_topic("Top KD-ratio", "kd.png");
	$stat = "kd";
	$rows = get_30days_top_kd($view);
	$i = 0;
	while ($row = mysql_fetch_assoc($rows)) {
		$i++;
		$pname    = preg_replace("/\[.*\]/i", "", $row['playername']);
		$sname    = number_format(round($row[$stat],1),1).":1";
		if ($i < 4) { $pname = "<font color=blue>$pname</font>"; $sname = "<font color=blue>$sname</font>";}
		if (is_odd($i)) {
			echo "<tr class=odd><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		} else {
			echo "<tr ><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		}
	}
	echo "</tbody></table></div>";
}

function echo_top_killers_table($view) {
	echo_table_header_with_topic("Top killers", "kills.png");
	$rows = get_30days_top_kills($view);
	$i = 0;
	while ($row = mysql_fetch_assoc($rows)) {
		$i++;
		$pname    = preg_replace("/\[.*\]/i", "", $row['playername']);
		if ($view==2) {
			$sname   = number_format(round($row['kills'],2),2)."/min";
		} else {
			$sname   = number_format($row['kills']);
		}
		if ($i < 4) { $pname = "<font color=blue>$pname</font>"; $sname = "<font color=blue>$sname</font>";}
		if (is_odd($i)) {
			echo "<tr class=odd><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		} else {
			echo "<tr ><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		}
	}
	echo "</tbody></table></div>";
}

function echo_top_healers_table($view) {
	echo_table_header_with_topic("Top healers", "healed.png");
	$rows = get_30days_top_healers($view);
	$i = 0;
	while ($row = mysql_fetch_assoc($rows)) {
		$i++;
		$pname = preg_replace("/\[.*\]/i", "", $row['playername']);
		if ($view==2) {
			$sname   = number_format(round($row['healed'],2),2)."/min";
		} else {
			$sname   = number_format($row['healed']);
		}
		if ($i < 4) { $pname = "<font color=blue>$pname</font>"; $sname = "<font color=blue>$sname</font>";}
		if (is_odd($i)) {
			echo "<tr class=odd><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		} else {
			echo "<tr ><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		}
	}
	echo "</tbody></table></div>";
}

function echo_top_deaths_table($view) {
	echo_table_header_with_topic("Top deaths", "deaths.png" );
	$rows = get_30days_top_deaths($view);
	$stat = "deaths";
	$i = 0;
	while ($row = mysql_fetch_assoc($rows)) {
		$i++;
		$pname = preg_replace("/\[.*\]/i", "", $row['playername']);
		if ($view==2) {
			$sname   = number_format(round($row[$stat],2),2)."/h";
		} else {str_replace("[Epic]", "", $row['playername']);
			$sname   = number_format($row[$stat]);
		}
		if ($i < 4) { $pname = "<font color=blue>$pname</font>"; $sname = "<font color=blue>$sname</font>";}
		if (is_odd($i)) {
			echo "<tr class=odd><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		} else {
			echo "<tr ><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		}
	}
	echo "</tbody></table></div>";
}

function echo_average_sf_table($view) {
	echo_table_header_with_topic("Average SF", "rank.png");
	$rows = get_30days_average_sf_for_all_vips($view);
	$i = 0;
	while ($row = mysql_fetch_assoc($rows)) {
		$i++;
		$pname = preg_replace("/\[.*\]/i", "", $row['playername']);
		$sname   = number_format(round($row['avg'],2),2);
		if ($i < 4) { $pname = "<font color=blue>$pname</font>"; $sname = "<font color=blue>$sname</font>";}
		if (is_odd($i)) {
			echo "<tr class=odd><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		} else {
			echo "<tr ><th scope=row>$i.</th><td>$pname</td><td style=\"text-align:right;\">$sname</td></tr>";
		}
	}
	echo "</tbody></table></div></td>";
}

function echo_event_based_stats_for_playerid_for_period($playerid, $period) {
        $time    = get_playtime_for_playerid_for_period($playerid, get_period($period));
        if ($time < 1) { return; }
        $result3 = get_destroyed_gadgets_for_playerid_for_period($playerid, get_period($period));
        $result1 = get_destroyed_buildings_for_playerid_for_period($playerid, get_period($period));
        $result2 = get_destroyed_units_for_playerid_for_period($playerid, get_period($period));
        $result4 = get_killed_players_for_playerid_for_period($playerid, get_period($period));
        $result5 = get_players_who_killed_playerid_for_period($playerid, get_period($period));
        echo "<div id=individual_event_table><table><tr><td>";
        echo "<table>";
        echo "<div id=stat_sub_header>Destroyed buildings</div>";
        while ($row = mysql_fetch_assoc($result1)) {
                asort($row);
                $row = array_reverse($row, true);
                foreach ($row as $key => $value) {
                        $object = $key;
                        $count  = $value;
                        if ($count == "") { $count = 0; }
                        if (empty($count)) { continue; }
                        $permin = number_format($value/$time,2);
                        $img = get_corresponding_icon_for_event_object($object);
                        echo "<tr><td><img src=images/stats/$img></td><td><b>$object</b></b><br><b>$count</b> ($permin/hour)</td></tr>";
                }
        }
        echo "</table></td><td><table>";
        echo "<div id=stat_sub_header>Killed units</div>";
        while ($row = mysql_fetch_assoc($result2)) {
                asort($row);
                $row = array_reverse($row, true);
                foreach ($row as $key => $value) {
                        $object = $key;
                        $count  = $value;
                        if ($count == "") { continue; }
                        if (empty($count)) { continue; }
                        $permin = number_format($value/$time,2);
                        $img = get_corresponding_icon_for_event_object($object);
                        echo "<tr><td><img src=images/stats/$img></td><td><b>$object</b><br><b>$count </b>($permin/hour)</td></tr>";
                }
        }
        echo "</table></td><td><table>";
        echo "<div id=stat_sub_header>Destroyed gadgets</div>";
        while ($row = mysql_fetch_assoc($result3)) {
                asort($row);
                $row = array_reverse($row, true);
                foreach ($row as $key => $value) {
                        $object = $key;
                        $count  = $value;
                        if ($count == "") { $count = 0; }
                        if (empty($count)) { continue; }
                        $permin = number_format($value/$time,2);
                        $img = get_corresponding_icon_for_event_object($object);
                        echo "<tr><td><img src=images/stats/$img></td><td><b>$object</b><br><b>$count </b>($permin/hour)</td></tr>";
                }
        }
        echo "</table></td><td><table>";
        echo "<div id=stat_sub_header>Killed players</div>";
        while ($row = mysql_fetch_assoc($result4)) {
                asort($row);
                $row = array_reverse($row, true);
                $playername = $row['playername'];
                $times      = $row['times'];
                if ($times == "") { $times = 0; }
                if (empty($times)) { continue; }
                $permin = number_format($value/$time,2);
                $img = get_corresponding_icon_for_event_object("playername");
                echo "<tr><td><img src=images/stats/$img></td><td><b>$playername</b><br><b>$times </b></td></tr>";
        }
        echo "</table></td><td><table>";
        echo "<div id=stat_sub_header>Killed by</div>";
        while ($row = mysql_fetch_assoc($result5)) {
                asort($row);
                $row = array_reverse($row, true);
                $playername = $row['playername'];
                $times      = $row['times'];
                if ($times == "") { $times = 0; }
                if (empty($times)) { continue; }
                $permin = number_format($value/$time,2);
                $img = get_corresponding_icon_for_event_object("playername");
                echo "<tr><td><img src=images/stats/$img></td><td><b>$playername</b><br><b>$times </b></td></tr>";
        }
        echo "</table></td><tr>";
        echo "</table></div>";
}


function echo_commander_stats_for_playerid_for_period($playerid, $playername, $period) {
        $record  = get_commander_winloss_for_playerid_for_period($playerid, get_period($period));
        $stats = get_commander_stats_for_playerid_for_period($playerid, get_period($period));
        foreach ($stats as $key => $value) { $$key = $value; if ($$key == "") { $$key = 0; } }
        $playername	= playerid_to_playername($playerid);
        $duration       = number_format($duration,2);
        $recordstr      = "$record[0]-$record[1]";
        $winp           = form_winp($record);
        $exp		= number_format($exp);
        $healed   	= number_format($healed);
        $gold		= number_format($gold);
        $repair		= number_format($repaired);
        $dmg 		= number_format($dmg);
        $orders         = number_format($orders);
        $kills          = number_format($kills);
        $buffs          = number_format($buffs);
        $debuffs        = number_format($debuffs);
        $erected        = number_format($erected);
        $razed          = number_format($razed);
        $streak         = get_ongoing_commander_winstreak_for_playerid($playerid);
        $longest_streak = get_longest_commander_winstreak_for_playerid($playerid);
        $side           = get_teams_played_for_commander_playerid_for_period($playerid, $period);
        $width          = 30;
        echo "  <div id=individual_table>
                <table style=\"border:solid 0px; line-height : 15px;\">
                <tr>
                        <td><img src=../images/stats/record.png width=$width></td><td><font size=2>Record<br><font size=2><b>$recordstr</b></td>
                        <td><img src=../images/stats/rank.png width=$width></td><td><font size=2>Win<br><font size=2><b>$winp</td>
                </tr>
                <tr>
                        <td><img src=../images/stats/exp.png width=$width></td><td><font size=2>Experience<br><font size=2><b>$exp</td>
                        <td><img src=../images/stats/hours.png width=$width></td><td><font size=2>Playtime<br><font size=2><b>$duration</b></td>     
                </tr>
                <tr>
                        <td><img src=../images/stats/orders.png width=$width></td><td><font size=2>Orders<br><font size=2><b>$orders</td>
                        <td><img src=../images/stats/healed.png width=$width></td><td><font size=2>Healed<br><font size=2><b>$healed</td>
                </tr>
                <tr>
                        <td><img src=../images/stats/gold.png width=$width></td><td><font size=2>Gold<br><font size=2><b>$gold</td>
                        <td><img src=../images/stats/kills.png width=$width></td><td><font size=2>Kills<br><font size=2><b>$kills</td>
                </tr>
                <tr>
                        <td><img src=../images/stats/buffs.png width=$width></td><td><font size=2>Buffs<br><font size=2><b>$buffs</td>
                        <td><img src=../images/stats/debuffs.png width=$width></td><td><font size=2>Debuffs<br><font size=2><b>$debuffs</td>
                </tr>
                <tr>
                        <td><img src=../images/stats/repair.png width=$width></td><td><font size=2>Erected<br><font size=2><b>$erected</td>
                        <td><img src=../images/stats/repair.png width=$width></td><td><font size=2>Repaired<br><font size=2><b>$repaired</td>
                </tr>
                <tr>
                        <td><img src=../images/stats/dmg.png width=$width></td><td><font size=2>P. dmg<br><font size=2><b>$dmg</td>
                        <td><img src=../images/stats/razed.png width=$width></td><td><font size=2>Razed<br><font size=2><b>$razed</td>
                </tr>
                <tr>
                        <td><img src=../images/stats/exp.png width=$width></td><td><font size=2>LF<br><font size=2><b>N/A</td>
                        <td><img src=../images/stats/rank.png width=$width></td><td><font size=2>Win-Streak<br><font size=2><b>$streak ($longest_streak)</td>
                </tr>
                <tr>
                        <td><img src=../images/stats/kd.png width=$width></td><td><font size=2>H/B<br><font size=2><b>$side</td>
                        <td></td>
                </tr>
                </table>
                </div>
                </center>
        ";
}

function echo_actionplayer_stats_for_playerid_for_period($playerid, $playername, $period) {
        $record  = get_actionplayer_winloss_for_playerid_for_period($playerid, get_period($period));
        $stats = get_actionplayer_stats_for_playerid_for_period($playerid, get_period($period));
        foreach ($stats as $key => $value) { $$key = $value; if ($$key == "") { $$key = 0; } }
        $playername	= playerid_to_playername($playerid);
        $recordstr      = "$record[0]-$record[1]";
        $winp           = form_winp($record);
        $duration       = number_format($duration,2);
        $sf             = number_format($sf, 2);
        $kills          = number_format($kills);
        $deaths         = number_format($deaths);
        $souls          = number_format($souls);
        $razed          = number_format($razed);
        $assists        = number_format($assists);
        $npc            = number_format($npc);
        $kd             = number_format($kd, 2);
        $exp		= number_format($exp);
        $healed 	= number_format($healed);
        $gold		= number_format($gold);
        $repair		= number_format($repair);
        $dmg 		= number_format($dmg);
        $bd		= number_format($bd);
        $width          = 30;
        echo "  <div id=individual_table>
                <table style=\"border:solid 0px; line-height : 13px;\">
                <tr>
                        <td><img src=../images/stats/record.png width=$width></td><td><font size=2>Record<br><font size=2><b>$recordstr</b></td>
                        <td><img src=../images/stats/rank.png width=$width></td><td><font size=2>Win<br><font size=2><b>$winp</td>
                </tr>
                <tr>
                        <td><img src=../images/stats/hours.png width=$width></td><td><font size=2>Playtime<br><font size=2><b>$duration</b></td>
                        <td><img src=../images/stats/last.png width=$width></td><td><font size=2>Exp/min<br><font size=2><b>$sf</td>
                </tr>
                <tr>
                        <td><img src=../images/stats/exp.png width=$width></td><td><font size=2>Experience<br><font size=2><b>$exp</td>
                        <td><img src=../images/stats/dmg.png width=$width></td><td><font size=2>P. dmg<br><font size=2><b>$dmg</td>
                </tr>
                <tr>
                        <td><img src=../images/stats/kills.png width=$width></td><td><font size=2>Kills<br><font size=2><b>$kills</td>
                        <td><img src=../images/stats/deaths.png width=$width></td><td><font size=2>Deaths<br><font size=2><b>$deaths</td>
                </tr>
                <tr>
                        <td><img src=../images/stats/kd.png width=$width></td><td><font size=2>KD-ratio<br><font size=2><b>$kd</td>
                        <td><img src=../images/stats/souls.png width=$width></td><td><font size=2>Souls used<br><font size=2><b>$souls</td>
                </tr>
                <tr>
                        <td><img src=../images/stats/healed.png width=$width></td><td><font size=2>Healed<br><font size=2><b>$healed</td>
                        <td><img src=../images/stats/res.png width=$width></td><td><font size=2>Resses<br><font size=2><b>$res</td>
                </tr>
                <tr>
                        <td><img src=../images/stats/gold.png width=$width></td><td><font size=2>Gold<br><font size=2><b>$gold</td>
                        <td><img src=../images/stats/bd.png width=$width></td><td><font size=2>B. dmg<br><font size=2><b>$bd</td>
                </tr>
                <tr>
                        <td><img src=../images/stats/razed.png width=$width></td><td><font size=2>Razed<br><font size=2><b>$razed</td>
                        <td><img src=../images/stats/repair.png width=$width></td><td><font size=2>Repaired<br><font size=2><b>$repair</td>
                </tr>
                <tr>
                        <td><img src=../images/stats/assists.png width=$width></td><td><font size=2>Assists<br><font size=2><b>$assists</td>
                        <td><img src=../images/stats/npc.png width=$width></td><td><font size=2>NPC kills<br><font size=2><b>$npc</td>
                </tr>
                </table>
                </div>
                </center>
        ";
}


function echo_actionplayer_clan_stats_for_clan_for_period($clan, $period) {
        $period = get_period($period);
        $stats = get_actionplayer_stats_for_clan_for_period($clan, $period);
        foreach ($stats as $key => $value) { $$key = $value; if ($$key == "") { $$key = 0; } }
        $duration       = number_format($duration,2);
        $sf             = number_format($sf, 2);
        $kills          = number_format($kills);
        $deaths         = number_format($deaths);
        $souls          = number_format($souls);
        $razed          = number_format($razed);
        $assists        = number_format($assists);
        $npc            = number_format($npc);
        $kd             = number_format($kd, 2);
        $exp		= number_format($exp);
        $healed 	= number_format($healed);
        $gold		= number_format($gold);
        $repair		= number_format($repair);
        $dmg 		= number_format($dmg);
        $bd		= number_format($bd);
        $width          = 30;
        echo "  <div id=individual_table>
                <table style=\"border:solid 0px; line-height : 15px;\">
                <tr>
                        <td><img src=../images/stats/hours.png width=$width></td><td><font size=2>Playtime<br><font size=2><b>$duration</b></td>
                        <td><img src=../images/stats/exp.png width=$width></td><td><font size=2>Exp/min<br><font size=2><b>$sf</td>
                </tr>
                <tr>
                        <td><img src=../images/stats/exp.png width=$width></td><td><font size=2>Experience<br><font size=2><b>$exp</td>
                        <td><img src=../images/stats/dmg.png width=$width></td><td><font size=2>P. dmg<br><font size=2><b>$dmg</td>
                </tr>
                <tr>
                        <td><img src=../images/stats/kills.png width=$width></td><td><font size=2>Kills<br><font size=2><b>$kills</td>
                        <td><img src=../images/stats/deaths.png width=$width></td><td><font size=2>Deaths<br><font size=2><b>$deaths</td>
                </tr>
                <tr>
                        <td><img src=../images/stats/kd.png width=$width></td><td><font size=2>KD-ratio<br><font size=2><b>$kd</td>
                        <td><img src=../images/stats/souls.png width=$width></td><td><font size=2>Souls used<br><font size=2><b>$souls</td>
                </tr>
                <tr>
                        <td><img src=../images/stats/healed.png width=$width></td><td><font size=2>Healed<br><font size=2><b>$healed</td>
                        <td><img src=../images/stats/res.png width=$width></td><td><font size=2>Resses<br><font size=2><b>$res</td>
                </tr>
                <tr>
                        <td><img src=../images/stats/gold.png width=$width></td><td><font size=2>Gold<br><font size=2><b>$gold</td>
                        <td><img src=../images/stats/bd.png width=$width></td><td><font size=2>B. dmg<br><font size=2><b>$bd</td>
                </tr>
                <tr>
                        <td><img src=../images/stats/razed.png width=$width></td><td><font size=2>Razed<br><font size=2><b>$razed</td>
                        <td><img src=../images/stats/repair.png width=$width></td><td><font size=2>Repaired<br><font size=2><b>$repair</td>
                </tr>
                <tr>
                        <td><img src=../images/stats/assists.png width=$width></td><td><font size=2>Assists<br><font size=2><b>$assists</td>
                        <td><img src=../images/stats/npc.png width=$width></td><td><font size=2>NPC kills<br><font size=2><b>$npc</td>
                </tr>
                </table>
                </div>
                </center>
        ";
}
function echo_commander_clan_stats_for_clan_for_period($clan, $period) {

}




?>
