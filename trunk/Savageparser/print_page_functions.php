<?php
// This is static linker of a test function
// the the target only, keep this as the first function of this file
function echo_test() {
        echo_player_search_element();
}

function echo_individual_playcount_for_units_for_playerid_for_period($playerid, $period) {
        $result = get_spawned_unit_for_playerid_for_period($playerid, get_period($period));
        echo "<table><tr>";
        while ($row = mysql_fetch_assoc($result)) {
                $unit  = $row['target_unit'];
                $count = $row['times'];
                $img   = get_corresponding_icon_for_event_object($unit);
                echo "<td><img src=images/stats/$img title=\"Spawned as $unit\"><br><b>$count</b></td>";
        }
        echo "</tr></table>";
}


function echo_pending_jobs_to_be_done() {
        if (isset($_SESSION['jobs'])) {
                if (!empty($_SESSION['jobs'])) {
                        foreach ($_SESSION['jobs'] as $job => $key) {
                                eval($key);
                                unset($_SESSION['jobs'][$job]);
                        }
                }
        }
}

function jquery_toggle_element($id) {
        echo "<script>$(\"$id\").toggle('slow', function() {});</script>";
}


function late_echo_actionplayer_google_graph_for_clan_for_period($clan, $period, $matches, $dates0, $dates1) {
   echo '
                    <link rel="stylesheet" href="/js/css/ajax-tooltip.css" media="screen" type="text/css">
                    <link rel="stylesheet" href="/js/css/ajax-tooltip-demo.css" media="screen" type="text/css">
                    <script type="text/javascript" src="/js/js/ajax-dynamic-content.js"></script>
                    <script type="text/javascript" src="/js/js/ajax.js"></script>
                    <script type="text/javascript" src="/js/js/ajax-tooltip.js"></script>
                    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
                    <script type="text/javascript">
                      google.load(\'visualization\', \'1\', {packages: [\'corechart\']});
                    </script>
                    <script type="text/javascript">
                      function drawVisualization_actionplayer() {
                                // Create and populate the data table.
                                var data = new google.visualization.DataTable();
                                data.addColumn(\'string\', \'Match\');
                                ';
                                echo_actionplayer_data_addrow_lines_for_clan_for_period($clan, $period);
                                echo '
                                // Create and draw the visualization.
                                var line = new google.visualization.LineChart(document.getElementById(\'visualization_actionplayer\'));
                                        line.draw(data, {
                                                chartArea: { left:65,top:40,width:\'67%\',height:\'65%\' },
                                                curveType: "function",
                                                width: 700, height: 300,
                                                vAxis: {maxValue: 10 ,title: \'Skill factor (XP/min)\'},
                                                hAxis: {maxValue: 10 ,title: \'Matches played ('.$matches.')\'},
                                                title: \'Average SF for clan '.$clan.' ('.date("d/m/Y", $dates0).' - '.date("d/m/Y", $dates1).')\',
                                                legend: \'right\',
                                                legendTextStyle: { fontSize: 12 },
                                                pointSize: 3
                                        }
                                        );

                      }
                      google.setOnLoadCallback(drawVisualization_actionplayer);
                    </script>
                    <script>
                            // $(\'#visualization_actionplayer\').toggle(\'slow\', function() {});
                            $(\'#visualization_actionplayer\').empty();
                            $("#player_match_info_mouseover").click(function() {
                                $("#player_match_info_mouseover").toggle(\'slow\', function() {});
                            });
                    </script>

        ';
}

function late_echo_actionplayer_google_graph_for_playerid_for_period($playerid, $period, $playername, $matches, $dates0, $dates1) {
        echo '
                    <link rel="stylesheet" href="/js/css/ajax-tooltip.css" media="screen" type="text/css">
                    <link rel="stylesheet" href="/js/css/ajax-tooltip-demo.css" media="screen" type="text/css">
                    <script type="text/javascript" src="/js/js/ajax-dynamic-content.js"></script>
                    <script type="text/javascript" src="/js/js/ajax.js"></script>
                    <script type="text/javascript" src="/js/js/ajax-tooltip.js"></script>
                    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
                    <script type="text/javascript">
                      google.load(\'visualization\', \'1\', {packages: [\'corechart\']});
                    </script>
                    <script type="text/javascript">
                      function drawVisualization_actionplayer() {
                                // Create and populate the data table.
                                var data = new google.visualization.DataTable();
                                data.addColumn(\'string\', \'Match\');
                                data.addColumn(\'number\', \'SF\');
                                data.addColumn(\'number\', \'Average SF\');
                                ';
                                echo_actionplayer_data_addrow_lines_for_playerid_for_period($playerid, $period);
                                echo '
                                // Create and draw the visualization.
                                var line = new google.visualization.LineChart(document.getElementById(\'visualization_actionplayer\'));
                                        line.draw(data, {
                                                chartArea: { left:65,top:40,width:\'87%\',height:\'65%\' },
                                                curveType: "none",
                                                width: 700, height: 300,
                                                vAxis: {maxValue: 10 ,title: \'Skill factor (XP/min)\'},
                                                hAxis: {maxValue: 10 ,title: \'Matches played ('.$matches.')\'},
                                                title: \'Average SF for player '.$playername.' ('.date("d/m/Y", $dates0).' - '.date("d/m/Y", $dates1).')\',
                                                legend: \'top\',
                                                pointSize: 3
                                        }
                                        );
                                google.visualization.events.addListener(line, \'onmouseover\', onmouse_action);
                                function onmouse_action(row) {
                                        var r = row[\'row\'];
                                        var matchid = data.getRowProperty(r, "matchid");
                                        var url = "parser/match_ajax_info.php?matchid="+matchid+"&playerid='.$playerid.'&position=player";
                                        $.get(url, function(data) {
                                          $("#player_match_info_mouseover").html(data);
                                        });
                                        if (!$("#player_match_info_mouseover").is(":visible")) { $(\'#player_match_info_mouseover\').toggle(\'fast\', function() {}); }
                                        return false;

                                };
                      }
                      google.setOnLoadCallback(drawVisualization_actionplayer);
                    </script>
                    <script>$(\'#visualization_actionplayer\').toggle(\'slow\', function() {});
                            $("#player_match_info_mouseover").click(function() {
                                $("#player_match_info_mouseover").toggle(\'slow\', function() {});
                            });
                    </script>
        ';
}

function late_echo_commander_google_graph_for_playerid_for_period($playerid, $period, $playername, $matches, $dates0, $dates1) {
        echo '
                    <script type="text/javascript">
                      google.load(\'visualization\', \'1\', {packages: [\'corechart\']});
                    </script>
                    <script type="text/javascript">
                      function drawVisualization_commander() {
                                // Create and populate the data table.
                                var data = new google.visualization.DataTable();
                                data.addColumn(\'string\', \'Match\');
                                data.addColumn(\'number\', \'LF\');
                                data.addColumn(\'number\', \'Average LF\');
                                ';
                                echo_commander_data_addrow_lines_for_playerid_for_period($playerid, $period);
                                echo '
                                // Create and draw the visualization.
                                var line = new google.visualization.LineChart(document.getElementById(\'visualization_commander\'));
                                        line.draw(data, {
                                                chartArea: { left:80,top:40,width:\'80%\',height:\'60%\' },
                                                curveType: "none",
                                                width: 700, height: 300,
                                                vAxis: {maxValue: 10 ,title: \'Leadership Factor (LF)\'},
                                                hAxis: {maxValue: 10 ,title: \'Matches played ('.$matches.')\'},
                                                title: \'Average LF for player '.$playername.' ('.date("d/m/Y", $dates0).' - '.date("d/m/Y", $dates1).')\',
                                                legend: \'top\',
                                                pointSize: 3
                                        }
                                        );
                                google.visualization.events.addListener(line, \'onmouseover\', onmouse_commander);         
                                function onmouse_commander(row) {
                                        var r = row[\'row\'];
                                        var matchid = data.getRowProperty(r, "matchid");
                                        var url = "parser/match_ajax_info.php?matchid="+matchid+"&playerid='.$playerid.'&position=commander";
                                        $.get(url, function(data) {
                                          $("#commander_match_info_mouseover").html(data);
                                        });
                                        if (!$("#commander_match_info_mouseover").is(":visible")) { $(\'#commander_match_info_mouseover\').toggle(\'fast\', function() {}); }
                                        return false;

                                };
                      }
                      google.setOnLoadCallback(drawVisualization_commander);
                    </script>
                    <script>$(\'#visualization_commander\').toggle(\'slow\', function() {});
                    $("#commander_match_info_mouseover").click(function() {
                        $("#commander_match_info_mouseover").toggle(\'slow\', function() {});
                    });</script>
        ';
}

function echo_datepicker_load() {
        echo '<script language="Javascript" src="js/date_picker.js"></script>';
}

function echo_jquery_load() {
        echo '<script src="jquery/jquery.js"></script>';
}

function echo_load_player_seach_js_file() {
        echo "<script src=js/player_search.js></script>";
}

function echo_player_search_element() {
        echo "<div class=info>Search for player:
                <input type=text name=playersearch onkeyup='player_search_listusers(this.value, event)'>
                <input type=hidden name=playerid>
                <div id=playersearch_div></div></div>";
        echo_load_player_seach_js_file();
}

function echo_match_search_form() {
        echo '
                <div class=info><form method=POST name=match_search>
                <div id=matchid>Matchid: <input type=text name=matchid id=matchid_field></div>
                <div id=advanced_options style="display: none;">
                        <table>
                        <tr><td>Map:</td><td><select name=map> '; echo_match_search_options_for_maps(); echo ' </select></td></tr>
                        <tr><td>Date:</td><td>
                                        <div id=date style="display: none;"><input type=text style="display: none;" id=date_field name=date autocomplete="off" onclick="displayDatePicker(\'date\');"></div>
                                        <div id=date_add_icon><a href=# onclick="add_date(); return false;">
                                        <img src=images/add_player.png width=16 onclick=add_date()> Add date</a></div>
                                        <div id=date_remove_icon style="display: none;"><a href=# onclick="remove_date(); return false;">
                                        <img src=images/remove_players.png width=16 onclick=remove_date()>Remove date</a></div>
                                        </td></tr>
                        <tr><td>Server:</td><td><select name=server> '; echo_match_search_options_for_servers(); echo ' </select></td></tr>
                        <tr><td>Length:</td><td><select name=length_operator>
                                                        <option>Any</option>
                                                        <option value=lt>Less than</option>
                                                        <option value=gt>Greater than</option>
                                                </select>
                                                <input name=length type=text>minutes</td></tr>
                        <tr><td>Winner:</td><td><select name=winner>
                                                        <option>Any</option>
                                                        <option value=1>Humans (Team1)</option>
                                                        <option value=2>Beasts (Team2)</option>
                                                </select></td></tr>
                        <tr><td>Team1 SF:</td><td><select name=sf_operator_team1>
                                                        <option>Any</option>
                                                        <option value=lt>Less than</option>
                                                        <option value=gt>Greater than</option>
                                                </select>
                                                <input name=sf_team1 type=text></td></tr>
                        <tr><td>Team2 SF:</td><td><select name=sf_operator_team2>
                                                        <option>Any</option>
                                                        <option value=lt>Less than</option>
                                                        <option value=gt>Greater than</option>
                                                </select>
                                                <input name=sf_team2 type=text></td></tr>
                        <tr><td>Players:</td><td>
                                        <div id=players>
                                                <input type="hidden" id="id" value="1">
                                        </div>
                                        <a href=# onclick="addplayer(); return false;"><img src=images/add_player.png width=16> Add field</a></td></tr>
                        <tr><td></td><td></td></tr>
                        </table>
                </div>
              <div id=show_hide><a href=#>+Show advanced search</a></div>
              <input type=submit value=Search>
              </form></div>
              ';
        echo_load_match_seach_js_file();
}

function echo_load_match_seach_js_file() {
        echo "<script src=js/match_search.js></script>";
}

function echo_match_search_options_for_maps() {
        $result = get_different_mapnames_and_playcounts();
        var_dump($result);
        echo "<option>Any</option>";
        while ($row = mysql_fetch_assoc($result)) {
                echo "<option value=$row[map]>&nbsp;$row[map] ($row[playtimes] matches)</option>";
        }
}

function echo_match_search_options_for_servers() {
        $result = get_different_server_names();
        var_dump($result);
        echo "<option>Any</option>";
        while ($row = mysql_fetch_assoc($result)) {
                echo "<option value=$row[id]>&nbsp;$row[server]</option>";
        }
}

function echo_match_search_options() {
        echo_match_search_form();
        if (!empty($_POST)) { perform_match_search_for_attributes($_POST); }
}

function echo_match_search_element() {
        if (isset($_GET['matchid'])) { $matchid = $_GET['matchid']; }
        else { $matchid = 0; }
        if (empty($matchid) || !is_valid_matchid($matchid)) {
                echo_overall_match_view();
        } else {
                echo_match_stats_for_matchid($matchid);
        }

}
function echo_match_stats_for_matchid_new($matchid) {
        // echo_match_search_options();
        generate_overall_match_info_for_matchid_new($matchid);
        generate_overall_match_stats_for_matchid_for_teams_new($matchid);
        generate_individual_match_stats_for_matchid_for_teams_new($matchid);
}

function echo_match_stats_for_matchid($matchid) {
        // echo_match_search_options();
        generate_overall_match_info_for_matchid($matchid);
        generate_overall_match_stats_for_matchid_for_teams($matchid);
        generate_individual_match_stats_for_matchid_for_teams($matchid);
}

function generate_overall_match_info_for_matchid($matchid) {
        echo_overall_match_info_for_matchid($matchid);
}
function generate_overall_match_info_for_matchid_new($matchid) {
        echo_overall_match_info_for_matchid($matchid);
}

function echo_overall_match_info_for_matchid($matchid) {
        $map = get_mapname_for_map(get_mapname_for_matchid($matchid));
        echo "<table>
                <tr>
                <td>
                <img src=images/maps/$map>
                </td>
                <td>";
        echo '<table id="rounded-corner" summary="Overall match stats on '.$matchid.'">
        <thead> <tr>
        <th scope="col" class="rounded-matchid">Matchid</th>
        <th scope="col" class="rounded-date">Date</th>
        <th scope="col" class="rounded-date">Map</th>
        <th scope="col" class="rounded-date">Length</th>
        <th scope="col" class="rounded-date">Winner</th>
        <th scope="col" class="rounded-position">SF</th>
        </tr>
        <td colspan="6"><em>Overall team stats for match: '.$matchid.'</em></td>
        </thead> <tfoot> <tr>
        <td colspan="5" class="rounded-foot-left"></td>
        <td class="rounded-foot-right">&nbsp;</td>
        </tr>
        </tfoot>
        <tbody>';
        $result = get_overall_match_info_for_matchid($matchid);
        while ($row = mysql_fetch_assoc($result)) {
                $matchid        = $row['matchid'];
                $winner         = $row['winner'];
                $map            = $row['map'] ;
                $length         = $row['duration'];
                $date           = date('d/m/y', $row['date']);
                $sf             = $row['sf_team1']." - ".$row['sf_team2'];
                echo "<tr><td>$matchid</td><td>$date</td><td>$map</td><td>$length</td><td>$winner</td><td>$sf</td></tr>";
        }
        echo "</td></tr></table></table>";
}

function generate_overall_match_stats_for_matchid_for_teams_new($matchid) {
        echo '<table id="rounded-corner" summary="Overall match stats on '.$matchid.'">
        <thead> <tr>
        <th scope="col" class="rounded-matchid"></th>
        <th scope="col" class="rounded-date">SF</th>
        <th scope="col" class="rounded-date">P. dmg</th>
        <th scope="col" class="rounded-date">Kills</th>
        <th scope="col" class="rounded-date">Assist</th>
        <th scope="col" class="rounded-date">Souls</th>
        <th scope="col" class="rounded-date">Healed</th>
        <th scope="col" class="rounded-date">Res</th>
        <th scope="col" class="rounded-date">Gold</th>
        <th scope="col" class="rounded-date">Repair</th>
        <th scope="col" class="rounded-date">Npc</th>
        <th scope="col" class="rounded-date">B. dmg</th>
        <th scope="col" class="rounded-date">Razed</th>
        <th scope="col" class="rounded-date">Deaths</th>
        <th scope="col" class="rounded-position">KD</th>
        </tr>
        <td colspan="15"><em>Overall team stats for match: '.$matchid.'</em></td>
        </thead> <tfoot> <tr>
        <td colspan="14" class="rounded-foot-left"></td>
        <td class="rounded-foot-right">&nbsp;</td>
        </tr>
        </tfoot>
        <tbody>
        ';
        generate_overall_match_stats_for_matchid_for_team($matchid, 1);
        generate_overall_match_stats_for_matchid_for_team($matchid, 2);
        echo ' </tbody>';
        echo "</table>";
}

function generate_overall_match_stats_for_matchid_for_teams($matchid) {
        echo '<table id="rounded-corner" summary="Overall match stats on '.$matchid.'">
        <thead> <tr>
        <th scope="col" class="rounded-matchid"></th>
        <th scope="col" class="rounded-date">SF</th>
        <th scope="col" class="rounded-date">P. dmg</th>
        <th scope="col" class="rounded-date">Kills</th>
        <th scope="col" class="rounded-date">Assist</th>
        <th scope="col" class="rounded-date">Souls</th>
        <th scope="col" class="rounded-date">Healed</th>
        <th scope="col" class="rounded-date">Res</th>
        <th scope="col" class="rounded-date">Gold</th>
        <th scope="col" class="rounded-date">Repair</th>
        <th scope="col" class="rounded-date">Npc</th>
        <th scope="col" class="rounded-date">B. dmg</th>
        <th scope="col" class="rounded-date">Razed</th>
        <th scope="col" class="rounded-date">Deaths</th>
        <th scope="col" class="rounded-position">KD</th>
        </tr>
        <td colspan="15"><em>Overall team stats for match: '.$matchid.'</em></td>
        </thead> <tfoot> <tr>
        <td colspan="14" class="rounded-foot-left"></td>
        <td class="rounded-foot-right">&nbsp;</td>
        </tr>
        </tfoot>
        <tbody>
        ';
        generate_overall_match_stats_for_matchid_for_team($matchid, 1);
        generate_overall_match_stats_for_matchid_for_team($matchid, 2);
        echo ' </tbody>';
        echo "</table>";
}

function generate_overall_match_stats_for_matchid_for_team($matchid, $team = 1) {
        $result = get_overall_match_stats_for_matchid_for_team($matchid, $team);
        while ($row = mysql_fetch_assoc($result)) {
                $teamstr        = $row['team'];
                $sf             = number_format($row['sf_team'.$team]);
                $player_dmg     = number_format($row['player_dmg_team'.$team]);
                $kills          = number_format($row['kills_team'.$team]);
                $assists        = number_format($row['assists_team'.$team]);
                $souls          = number_format($row['souls_team'.$team]);
                $healed         = number_format($row['healed_team'.$team]);
                $res            = number_format($row['res_team'.$team]);
                $gold           = number_format($row['gold_team'.$team]);
                $repaired       = number_format($row['repaired_team'.$team]);
                $npc            = number_format($row['npc_team'.$team]);
                $bd             = number_format($row['bd_team'.$team]);
                $razed          = number_format($row['razed_team'.$team]);
                $deaths         = number_format($row['deaths_team'.$team]);
                $kd             = number_format($row['kd_team'.$team],2);
                echo "<tr>
                        <td>$teamstr</td>
                        <td>$sf</td>
                        <td>$player_dmg</td>
                        <td>$kills</td>
                        <td>$assists</td>
                        <td>$souls</td>
                        <td>$healed</td>
                        <td>$res</td>
                        <td>$gold</td>
                        <td>$repaired</td>
                        <td>$npc</td>
                        <td>$bd</td>
                        <td>$razed</td>
                        <td>$deaths</td>
                        <td>$kd</td>
                </tr>";
        }
}

function generate_individual_match_stats_for_matchid_for_teams_new($matchid) {
        echo '<table id="rounded-corner" summary="Overall match stats on '.$matchid.'">
        <thead> <tr>
        <th scope="col" class="rounded-matchid">Player</th>
        <th scope="col" class="rounded-date">SF</th>
        <th scope="col" class="rounded-date">Kills</th>
        <th scope="col" class="rounded-date">P. dmg</th>
        <th scope="col" class="rounded-date">Deaths</th>
        <th scope="col" class="rounded-date">Assist</th>
        <th scope="col" class="rounded-date">Souls</th>
        <th scope="col" class="rounded-date">NPC</th>
        <th scope="col" class="rounded-date">Healed</th>
        <th scope="col" class="rounded-date">Res</th>
        <th scope="col" class="rounded-date">Gold</th>
        <th scope="col" class="rounded-date">Repair</th>
        <th scope="col" class="rounded-date">B. dmg</th>
        <th scope="col" class="rounded-date">Razed</th>
        <th scope="col" class="rounded-date">KD</th>
        <th scope="col" class="rounded-position">Duration</th>
        </tr>
        <td colspan="16"><em>Team 1: Humans</em></td>
        </thead> <tfoot> <tr>
        <td colspan="15" class="rounded-foot-left"></td>
        <td class="rounded-foot-right">&nbsp;</td>
        </tr>
        </tfoot>
        <tbody>
        ';
        echo_individual_match_stats_for_matchid_for_team_new($matchid, 1);
        echo '<td colspan="16"><em>Team 2: Beasts</em></td>';
        echo_individual_match_stats_for_matchid_for_team_new($matchid, 2);
        echo ' </tbody>';
        echo "</table>";
}

function generate_individual_match_stats_for_matchid_for_teams($matchid) {
        echo '<table id="rounded-corner" summary="Overall match stats on '.$matchid.'">
        <thead> <tr>
        <th scope="col" class="rounded-matchid">Player</th>
        <th scope="col" class="rounded-date">SF</th>
        <th scope="col" class="rounded-date">Kills</th>
        <th scope="col" class="rounded-date">P. dmg</th>
        <th scope="col" class="rounded-date">Deaths</th>
        <th scope="col" class="rounded-date">Assist</th>
        <th scope="col" class="rounded-date">Souls</th>
        <th scope="col" class="rounded-date">NPC</th>
        <th scope="col" class="rounded-date">Healed</th>
        <th scope="col" class="rounded-date">Res</th>
        <th scope="col" class="rounded-date">Gold</th>
        <th scope="col" class="rounded-date">Repair</th>
        <th scope="col" class="rounded-date">B. dmg</th>
        <th scope="col" class="rounded-date">Razed</th>
        <th scope="col" class="rounded-date">KD</th>
        <th scope="col" class="rounded-position">Duration</th>
        </tr>
        <td colspan="16"><em>Team 1: Humans</em></td>
        </thead> <tfoot> <tr>
        <td colspan="15" class="rounded-foot-left"></td>
        <td class="rounded-foot-right">&nbsp;</td>
        </tr>
        </tfoot>
        <tbody>
        ';
        echo_individual_match_stats_for_matchid_for_team($matchid, 1);
        echo '<td colspan="16"><em>Team 2: Beasts</em></td>';
        echo_individual_match_stats_for_matchid_for_team($matchid, 2);
        echo ' </tbody>';
        echo "</table>";
}

function echo_individual_match_stats_for_matchid_for_team_new($matchid, $team) {
        $result = get_individual_match_stats_for_match_for_team($matchid, $team);
        while ($row = mysql_fetch_assoc($result)) {
                $matchid        = $row['matchid'];
                $playerid       = $row['playerid'];
                $playername     = "<a href=index.php?action=stats&playerid=$playerid&playername=&period=30>".playerid_to_playername($playerid)."</a>";
                $exp            = number_format($row['exp']);
                $dmg            = number_format($row['dmg']);
                $kills          = number_format($row['kills']);
                $assists        = number_format($row['assists']);
                $souls          = number_format($row['souls']);
                $npc            = number_format($row['npc']);
                $healed         = number_format($row['healed']);
                $res            = number_format($row['res']);
                $gold           = number_format($row['gold']);
                $repair         = number_format($row['repair']);
                $bd             = number_format($row['bd']);
                $razed          = number_format($row['razed']);
                $deaths         = number_format($row['deaths']);
                $kd             = number_format($row['kd'],2);
                $duration       = $row['duration'];
                $sf             = number_format($row['gamesf']);
                echo "<tr><td>$playername</td><td>$sf</td><td>$kills</td><td>$dmg</td><td>$deaths</td><td>$assists</td><td>$souls</td><td>$npc</td><td>$healed</td><td>$res</td><td>$gold</td><td>$repair</td><td>$bd</td><td>$razed</td><td>$kd</td><td>$duration</td></tr>";
        }
}

function echo_individual_match_stats_for_matchid_for_team($matchid, $team) {
        $result = get_individual_match_stats_for_match_for_team($matchid, $team);
        while ($row = mysql_fetch_assoc($result)) {
                $matchid        = $row['matchid'];
                $playerid       = $row['playerid'];
                $playername     = "<a href=index.php?action=stats&playerid=$playerid&playername=&period=30>".playerid_to_playername($playerid)."</a>";
                $exp            = number_format($row['exp']);
                $dmg            = number_format($row['dmg']);
                $kills          = number_format($row['kills']);
                $assists        = number_format($row['assists']);
                $souls          = number_format($row['souls']);
                $npc            = number_format($row['npc']);
                $healed         = number_format($row['healed']);
                $res            = number_format($row['res']);
                $gold           = number_format($row['gold']);
                $repair         = number_format($row['repair']);
                $bd             = number_format($row['bd']);
                $razed          = number_format($row['razed']);
                $deaths         = number_format($row['deaths']);
                $kd             = number_format($row['kd'],2);
                $duration       = $row['duration'];
                $sf             = number_format($row['gamesf']);
                echo "<tr><td>$playername</td><td>$sf</td><td>$kills</td><td>$dmg</td><td>$deaths</td><td>$assists</td><td>$souls</td><td>$npc</td><td>$healed</td><td>$res</td><td>$gold</td><td>$repair</td><td>$bd</td><td>$razed</td><td>$kd</td><td>$duration</td></tr>";
        }
}

function echo_overall_match_view() {
                echo "
                        <table>
                                <tr>
                                <td>"; generate_my_recent_match_info(); echo "</td>
                                <td>"; generate_recent_match_info();    echo "</td>
                                </tr>
                        </table>
                ";             
}

function generate_my_recent_match_info() {
        $recents = 20;
        echo '<table id="rounded-corner" summary="Recent match info for '.$recents.' matches">
        <thead> <tr>
        <th scope="col" class="rounded-matchid">Matchid</th>
        <th scope="col" class="rounded-date">Date</th>
        <th scope="col" class="rounded-map">Map</th>
        <th scope="col" class="rounded-length">Length</th>
        <th scope="col" class="rounded-winner">Position</th>
        <th scope="col" class="rounded-position">SF</th>
        </tr>
        <td colspan="6"><em>Your '.$recents.' recent matches</em></td>
        </thead> <tfoot> <tr>
        <td colspan="5" class="rounded-foot-left"></td>
        <td class="rounded-foot-right">&nbsp;</td>
        </tr>
        </tfoot>
        <tbody>
        ';
        $result = get_last_matches_for_playerid($_SESSION['playerid'], 20);
        while ($row = mysql_fetch_assoc($result)) {
                $matchid        = $row['matchid'];
                $matchid        = "<a href=index.php?action=match_info&matchid=$matchid>$matchid</a>";
                $str            = $row['str'];
                $map            = $row['map'] ;
                $length         = $row['duration'];
                $date           = date('d/m/y', $row['date']);
                $sf             = number_format($row['sf']);
                echo "<tr><td>$matchid</td><td>$date</td><td>$map</td><td>$length</td><td>$str</td><td>$sf</td></tr>";
        }
        echo ' </tbody>';
        echo "</table>";
        
}
function generate_recent_match_info() {
        $recents = 20;
        echo '<table id="rounded-corner" summary="Recent match info for '.$recents.' matches">
        <thead> <tr>
        <th scope="col" class="rounded-matchid">Matchid</th>
        <th scope="col" class="rounded-date">Date</th>
        <th scope="col" class="rounded-map">Map</th>
        <th scope="col" class="rounded-length">Length</th>
        <th scope="col" class="rounded-winner">Winner</th>
        <th scope="col" class="rounded-position">SF</th>
        </tr>
        <td colspan="6"><em>Recent '.$recents.' matches played overall</em></td>
        </thead> <tfoot> <tr>
        <td colspan="5" class="rounded-foot-left"></td>
        <td class="rounded-foot-right">&nbsp;</td>
        </tr>
        </tfoot>
        <tbody>
        ';
        $result = get_last_matches(20);
        while ($row = mysql_fetch_assoc($result)) {
                $matchid        = $row['matchid'];
                $matchid        = "<a href=index.php?action=match_info&matchid=$matchid>$matchid</a>";
                $str            = $row['winner'];
                $map            = $row['map'] ;
                $length         = $row['duration'];
                $date           = date('d/m/y', $row['date']);
                $sf             = $row['sf_team1']." - ".$row['sf_team2'];
                echo "<tr><td>$matchid</td><td>$date</td><td>$map</td><td>$length</td><td>$str</td><td>$sf</td></tr>";
        }
        echo ' </tbody>';
        echo "</table>";
}


function echo_match_info_page() {
        echo_match_search_options();
        if (empty($_POST)) {
                echo_match_search_element();
        }
}


function echo_profile_management_page() {
 	echo '
	<fieldset><legend><font color=black>Add other players are favourites</legend>
                Add your alts or other players as favourite so they show up under your account, easily accesible
		<table style="border-style: solid; border-width:1px;">
			<form method=POST name=add_contestor>
			<tr><td>Playername:</td>        <td><input type=text name=contestor_name id=contestor onkeyup=contest_listusers(this.value) autocomplete="off"></td></tr>
			<tr><td></td>                   <td><div id=userlist></div></td></tr>
			<tr><td></td><td><input type=submit value="Add as favourite"></td></tr>
                        <input type=hidden id=playerid name=contestor_playerid>
			</form>
		</table>
	</fieldset>
	';

}

function echo_contest_management_page() {
        echo "<font size=3>All contest are based on time binded results of given statistics (for example: heal/min)<br>
              This page is only for managing contests, the actual stats are presented on a public page which is accessible for everyone<br>
              <a href=http://savage.boubbin.org/contest/>http://savage.boubbin.org/contest/</a></font><br><br>";
        if (isset($_POST['contest_name']) && isset($_POST['contest_functionid'])) {
		$result = add_new_contest_if_input_data_is_valid($_POST['contest_name'], $_POST['contest_description'], $_POST['contest_functionid'], $_POST['contest_start'],$_POST['contest_duration'], $_POST['contest_playtime']);
                if (!$result) { echo "<warning>Could not add contest, input data was invalid..</warning>"; }
                else { js_href("index.php?action=contest"); }
        } else if (isset($_POST['contestor_playerid']) && isset($_POST['contest_id'])) {
		$result = add_new_contestor_for_contest_if_input_data_is_valid($_POST['contestor_playerid'], $_POST['contest_id']);
                if (!$result) { echo "<warning>Could not add contestor for contest, input data was invalid.. maybe that user is already added?</warning>"; }
                else { js_href("index.php?action=contest"); }
                add_new_vip_if_not_exists_for_playerid($_POST['contestor_playerid']);
                update_update_time_for_playerid($_POST['contestor_playerid'], get_period(30));
        }
        echo_add_new_contest_form();
        echo_add_new_contestor_for_contest_form();
        echo_ongoing_contests_table();
}

function add_new_contest_if_input_data_is_valid($contest_name, $contest_description, $contest_function, $contest_start, $contest_end, $contest_playtime) {
        list($month, $day, $year) = explode("/", $contest_start);
        $contest_unixstart = mktime(0, 0, 0, $month, $day, $year);
        $contest_unixend = $contest_unixstart + ($contest_end*24*60*60);
        $contest_name = strip_tags($contest_name);
        $contest_description = strip_tags($contest_description);
        if (contest_for_name_exists($contest_name)) { return false; }
        if (!is_numeric($contest_function)) { return false; }
        if (!is_numeric($contest_unixstart)) { return false; }
        if (!is_numeric($contest_unixend)) {  return false; }
        if (!is_numeric($contest_playtime)) { return false; }
        $result = add_new_contest($contest_name, $contest_description, $contest_function, $contest_unixstart ,$contest_unixend , $contest_playtime);
        if (!$result) { return false; }
        return true;
}

function add_new_contestor_for_contest_if_input_data_is_valid($playerid, $contestid) {
        if (!contest_for_id_exists($contestid)) { return false; }
        if (contestor_exists_for_contest($playerid, $contestid)) { return false; }
        $result = add_contestor_for_contest($playerid, $contestid);
        if (!$result) { return false; }
        return true;
}

function echo_add_new_contest_form() {
	echo '
	<fieldset><legend><font color=black>Add new Contest</legend>
                All contest should have playtime of 5 hours, except highest/winstreak.
		<table style="border-style: solid; border-width:1px;">
			<form method=POST>
			<tr><td>Contest name:</td>              <td><input type=text name=contest_name autocomplete="off"></td></tr>
                        <tr><td>Short descrition:</td>          <td><input type=text style="width : 480px;" name=contest_description autocomplete="off" value="In this contest the contestors must aim to get the highest XXX as possible for given period"></td></tr>
                        <tr><td>Contest function:</td>          <td>'.return_contest_functions().'</td></tr>
                        <tr><td>Start date:</td>                     <td><input type=text name=contest_start autocomplete="off" onclick="displayDatePicker(\'contest_start\');" value='.date('m/d/Y').'></td></tr>
                        <tr><td>Duration:</td>                  <td>'.return_contest_durations().'</td></tr>
                        <tr><td>Min playtime(h):</td>           <td><input type=text name=contest_playtime autocomplete="off" value=5></td></tr>
			<tr><td></td><td><input type=submit value="Add Contest!"></td></tr>
			</form>
		</table>
	</fieldset>
	';
}

function echo_add_new_contestor_for_contest_form() {
	echo '
	<fieldset><legend><font color=black>Add player to existing Contest</legend>
		<table style="border-style: solid; border-width:1px;">
			<form method=POST name=add_contestor>
			<tr><td>Username:</td>          <td><input type=text name=contestor_name id=contestor onkeyup=contest_listusers(this.value) autocomplete="off"></td></tr>
			<tr><td></td>                   <td><div id=userlist></div></td></tr>
                        <tr><td>Contest:</td>           <td>'.return_contest_selection_list().'</td></tr>
			<tr><td></td><td><input type=submit value="Add user to Contest!"></td></tr>
                        <input type=hidden id=playerid name=contestor_playerid>
			</form>
		</table>
	</fieldset>
	';
}

function return_contest_selection_list() {
        $str = '<select name=contest_id>';
        $result = get_ongoing_contests();
        while ($row = mysql_fetch_assoc($result)) {
                $id             = $row['id'];
                $name           = $row['name'];
                $start          = date('m/d/Y', $row['start']);
                $end            = date('m/d/Y', $row['end']);
                if ($row['start'] < time()) { $str .= "<option value=$id>$name (started: $start | will end: $end)</option>"; }
                else { $str .= "<option value=$id>$name (starts: $start | ends: $end)</option>"; }
        }
        return $str;
}


function return_contest_durations() {
        return "
                <select name=contest_duration>
                        <option value=7>7 days</option>
                        <option value=15>15 days</option>
                        <option value=30 selected>30 days</option>
                </select>
        ";
}

function return_contest_functions() {
        return "
                <select name=contest_functionid>
                        <option value=0 disabled selectd></option>
                        <option value=1 ".iff(contest_for_functionid_exists(1) === true,"disabled",'').">".get_string_label_for_contest_functionid(1)."</option>
                        <option value=2 ".iff(contest_for_functionid_exists(2) === true,"disabled",'').">".get_string_label_for_contest_functionid(2)."</option>
                        <option value=3 ".iff(contest_for_functionid_exists(3) === true,"disabled",'').">".get_string_label_for_contest_functionid(3)."</option>
                        <option value=4 ".iff(contest_for_functionid_exists(4) === true,"disabled",'').">".get_string_label_for_contest_functionid(4)."</option>
                        <option value=5 ".iff(contest_for_functionid_exists(5) === true,"disabled",'').">".get_string_label_for_contest_functionid(5)."</option>
                        <option value=6 ".iff(contest_for_functionid_exists(6) === true,"disabled",'').">".get_string_label_for_contest_functionid(6)."</option>
                        <option value=7 ".iff(contest_for_functionid_exists(7) === true,"disabled",'').">".get_string_label_for_contest_functionid(7)."</option>
                        <option value=8 ".iff(contest_for_functionid_exists(8) === true,"disabled",'').">".get_string_label_for_contest_functionid(8)."</option>
                        <option value=9 ".iff(contest_for_functionid_exists(9) === true,"disabled",'').">".get_string_label_for_contest_functionid(9)."</option>
                        <option value=10 ".iff(contest_for_functionid_exists(10) === true,"disabled",'').">".get_string_label_for_contest_functionid(10)."</option>
                </select>
        ";
}



function echo_ongoing_contests_table() {
	echo '
	<fieldset><legend><font color=black>Ongoing contests</legend>
		<table border=1 style="border-style: inset; border-width:1px;">
                <tr>
                        <td><b>Contest name:</b></td>
                        <td><b>Contest description:</b></td>
                        <td><b>Participants:</b></td>
                        <td><b>Start date:</b></td>
                        <td><b>End date:</b></td>
                </tr>
                '.return_ongoing_contests_info().'
		</table>
	</fieldset>
	';
}

function return_ongoing_contests_info() {
        $str = '';
        $result = get_ongoing_contests();
        while ($row = mysql_fetch_assoc($result)) {
                $str           .= '<tr>';
                $id             = $row['id'];
                $name           = $row['name'];
                $desc           = $row['description'];
                $start          = date('m/d/Y', $row['start']);
                $end            = date('m/d/Y', $row['end']);
                $contestors     = get_list_of_contestors_in_contest($id);
                $str           .= "
                                <td>$name</td>
                                <td>$desc</td>
                                <td>$contestors</td>
                                <td>$start</td>
                                <td>$end</td>
                ";
                $str .= "</tr>";
        }
        return $str;
}

function echo_clan_stats_for_clan_for_period($clan, $givenperiod = 30) {
        if ($givenperiod == 7) { $period = get_period($givenperiod); }
        if ($givenperiod == 15) { $period = get_period($givenperiod); }
        if ($givenperiod >= 30) { $givenperiod = 30; $period = get_period(30); }
        if ($givenperiod == 0) { $givenperiod = 30; $period = get_period(30); }
	echo "<h1><span>$givenperiod days stats for clan $clan</span></h1>";
        echo_clan_stats_period_options($clan, $givenperiod); flush();
	echo "<table border=1>";
	echo "<tr>";
	echo "<td>"; echo_actionplayer_clan_stats_for_clan_for_period($clan, $givenperiod); echo "</td>"; flush();
	echo "<td>"; echo_actionplayer_google_graph_for_clan_for_period($clan, $givenperiod); echo "</td>"; flush();
        echo "</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>"; echo_commander_clan_stats_for_clan_for_period($clan, $givenperiod); echo "</td>";
	echo "<td>"; echo_commander_google_graph_for_clan_for_period($clan, $givenperiod); echo "</td>";
	echo "</tr>";
	echo "</table>";
}


function get_corresponding_text_label_for_sigpic_option($option) {
        if ($option == 1) { return "SF"; }    // sf
        if ($option == 2) { return "KD"; }    // kd
        if ($option == 3) { return "Record"; }    // record
        if ($option == 4) { return "Playtime(h)"; }    // playtime
        if ($option == 5) { return "Human"; }    // human
        if ($option == 6) { return "Beast"; }    // beast
        if ($option == 7) { return "Kills/min"; }    // kpm
        if ($option == 8) { return "Dmg/min"; }    // dpm
        if ($option == 9) { return "BD/min"; }    // bdpm
        if ($option == 10) { return "Souls/hour"; }  // sph
        if ($option == 11) { return "Healed/min"; }  // hpm
        if ($option == 12) { return "Gold/min"; }  // gpm
        if ($option == 13) { return "Most kills in one game"; }  // highkills
        if ($option == 14) { return "Most bdmg in one game"; }  // highbd
        if ($option == 15) { return "Most souls used in one game"; } // highsouls
        if ($option == 16) { return "Most healed in one game"; }  // highhealed
        if ($option == 17) { return "Best KD in one game"; }  // highkd
        if ($option == 18) { return "Highest SF in one game"; }  // highkd
        return " ";
}

function gcsrfo($option, $playerid) {
        return get_corresponding_sigpic_result_for_option($option, $playerid);
}

function get_corresponding_sigpic_result_for_option($option, $playerid) {
        if ($option == "1")  { $result = get_30days_average_sf_for_playerid($playerid); return number_format($result['averagesf'],2); }
        if ($option == "2")  { return number_format(get_30days_kd_for_playerid($playerid),1).":1"; }
        if ($option == "3")  { $record = get_30days_winloss_for_playerid($playerid); return $record[0]."-".$record[1]; }
        if ($option == "4")  { return number_format(get_30_days_playtime_for_playerid($playerid),2); }
        if ($option == "5")  { $teams = get_30days_teams_played_for_playerid($playerid); $row = mysql_fetch_row($teams); return ($row[0]+0)." times"; }
        if ($option == "6")  { $teams = get_30days_teams_played_for_playerid($playerid); $row = mysql_fetch_row($teams); $row = mysql_fetch_row($teams); return ($row[0]+0)." times"; }
        if ($option == "7")  { return number_format(get_30days_kills_per_minute_for_playerid($playerid),2); }
        if ($option == "8")  { return number_format(get_30days_damage_per_minute_for_playerid($playerid),0); }
        if ($option == "9")  { return number_format(get_30days_bdamage_per_minute_for_playerid($playerid),2); }
        if ($option == "10") { return number_format(get_30days_souls_per_hour_for_playerid($playerid),0); }
        if ($option == "11") { return number_format(get_30days_heal_per_minute_for_playerid($playerid),0); }
        if ($option == "12") { return number_format(get_30days_gold_per_minute_for_playerid($playerid),2); }
        if ($option == "13") { return get_30days_most_kills_in_one_game_for_playerid($playerid); }
        if ($option == "14") { return number_format(get_30days_most_bdmg_in_one_game_for_playerid($playerid),0); }
        if ($option == "15") { return get_30days_most_souls_in_one_game_for_playerid($playerid); }
        if ($option == "16") { return number_format(get_30days_most_healed_in_one_game_for_playerid($playerid),0); }
        if ($option == "17") { return get_30days_most_kd_in_one_game_for_playerid($playerid); }
        if ($option == "18") { return number_format(get_30days_highest_sf_in_one_game_for_playerid($playerid),2); }
        return " ";

}

function get_corresponding_sigpic_option_getvar_for_option($option) {
        if ($option == 1) { return "sf=1"; }    // sf
        if ($option == 2) { return "kd=2"; }    // kd
        if ($option == 3) { return "record=3"; }    // record
        if ($option == 4) { return "playtime=4"; }    // playtime
        if ($option == 5) { return "human=5"; }    // human
        if ($option == 6) { return "beast=6"; }    // beast
        if ($option == 7) { return "kpm=7"; }    // kpm
        if ($option == 8) { return "dpm=8"; }    // dpm
        if ($option == 9) { return "bdpm=9"; }    // bdpm
        if ($option == 10) { return "sph=10"; }  // sph
        if ($option == 11) { return "hpm=11"; }  // hpm
        if ($option == 12) { return "gpm=12"; }  // gpm
        if ($option == 13) { return "highkills=13"; }  // highkills
        if ($option == 14) { return "highbd=14"; }  // highbd
        if ($option == 15) { return "highsouls=15"; } // highsouls
        if ($option == 16) { return "highhealed=16"; }  // highhealed
        if ($option == 17) { return "highkd=17"; }  // highkd
        if ($option == 18) { return "highsf=18"; }  // highkd
        return "=0";
}


function echo_sigpic_page() {
        if (count($_POST)>2) {
                $baseurl = "http://savage.boubbin.org/sigpic_generator.php?playerid=".$_SESSION['playerid']."&bg=".$_POST['player-background'];
                if ($_POST['1'] >= 0 && $_POST['1'] < 19) { $baseurl .= "&i1".get_corresponding_sigpic_option_getvar_for_option($_POST['1']); }
                if ($_POST['2'] >= 0 && $_POST['2'] < 19) { $baseurl .= "&i2".get_corresponding_sigpic_option_getvar_for_option($_POST['2']); }
                if ($_POST['3'] >= 0 && $_POST['3'] < 19) { $baseurl .= "&i3".get_corresponding_sigpic_option_getvar_for_option($_POST['3']); }
                if ($_POST['4'] >= 0 && $_POST['4'] < 19) { $baseurl .= "&i4".get_corresponding_sigpic_option_getvar_for_option($_POST['4']); }
                if ($_POST['4'] >= 0 && $_POST['4'] < 19) { $baseurl .= "&i5".get_corresponding_sigpic_option_getvar_for_option($_POST['5']); }
                echo "<a href=$baseurl><img src=$baseurl></a><br>";
                echo "If you would like to use this picture on forums sigature use this link as a picture:<br> <code>$baseurl</code>";
        } else {
                echo_sigpic_generating_options();
        }
}

function echo_sigpic_item_selection_list_for_action_player($item, $selected) {
        $playerid = $_SESSION['playerid'];
        if ($item) {
                return "
                              <select name=".$item.">
                                      <option value=0></option>
                                      <option value=1 ".iff($selected == 1,"selected",'').">Skill Factor (".gcsrfo(1, $playerid).")</option>
                                      <option value=2>Kill/Death ratio(".gcsrfo(2, $playerid).")</option>
                                      <option value=3 ".iff($selected == 2,"selected",'').">Win-loss (".gcsrfo(3, $playerid).")</option>
                                      <option value=4 ".iff($selected == 3,"selected",'').">Playtime (".gcsrfo(4, $playerid).")</option>
                                      <option value=5 ".iff($selected == 4,"selected",'').">Times human (".gcsrfo(5, $playerid).")</option>
                                      <option value=6 ".iff($selected == 5,"selected",'').">Times beasts (".gcsrfo(6, $playerid).")</option>
                                      <option disabled>--Time binded--</option>
                                      <option value=7>Kills per minute</option>
                                      <option value=8>Damage per minute</option>
                                      <option value=9>Building dmg per minute</option>
                                      <option value=10>Souls per hour</option>
                                      <option value=11>Heal per minute</option>
                                      <option value=12>Gold per minute</option>
                                      <option disabled>--Highest in one game--</option>
                                      <option value=13>Most kills in one game</option>
                                      <option value=14>Most bd in one game</option>
                                      <option value=15>Most souls in one game</option>
                                      <option value=16>Most healed in one game</option>
                                      <option value=17>Best KD-ratio in one game</option>
                                      <option value=18>Biggest Sf in one game</option>
                              </select>
                              <br>
                ";
        } else {
                return "
                                       <select name=playername>
                                              <option value=".$_SESSION['playername']." selected>".$_SESSION['playername']."</option>

                                      </select>
                                      <br>
                ";
        }
}
function echo_sigpic_item_selection_list_for_commander($item, $selected) {
        $playerid = $_SESSION['playerid'];
        if ($item) {
                return "
                              <select name=".$item.">
                                      <option value=0></option>
                                      <option value=1 ".iff($selected == 1,"selected",'').">Skill Factor (".gcsrfo(1, $playerid).")</option>
                                      <option value=2>Kill/Death ratio(".gcsrfo(2, $playerid).")</option>
                                      <option value=3 ".iff($selected == 2,"selected",'').">Win-loss (".gcsrfo(3, $playerid).")</option>
                                      <option value=4 ".iff($selected == 3,"selected",'').">Playtime (".gcsrfo(4, $playerid).")</option>
                                      <option value=5 ".iff($selected == 4,"selected",'').">Times human (".gcsrfo(5, $playerid).")</option>
                                      <option value=6 ".iff($selected == 5,"selected",'').">Times beasts (".gcsrfo(6, $playerid).")</option>
                                      <option disabled>--Time binded--</option>
                                      <option value=7>Kills per minute</option>
                                      <option value=8>Damage per minute</option>
                                      <option value=9>Building dmg per minute</option>
                                      <option value=10>Souls per hour</option>
                                      <option value=11>Heal per minute</option>
                                      <option value=12>Gold per minute</option>
                                      <option disabled>--Highest in one game--</option>
                                      <option value=13>Most kills in one game</option>
                                      <option value=14>Most bd in one game</option>
                                      <option value=15>Most souls in one game</option>
                                      <option value=16>Most healed in one game</option>
                                      <option value=17>Best KD-ratio in one game</option>
                                      <option value=18>Biggest Sf in one game</option>
                              </select>
                              <br>
                ";
        } else {
                return "
                                       <select name=playername>
                                              <option value=".$_SESSION['playername']." selected>".$_SESSION['playername']."</option>

                                      </select>
                                      <br>
                ";
        }
}


function echo_sigpic_generating_options() {
        // size of sigpic should be 300*100
        echo "<div id=sigpic>";
        echo "
                <fieldset>
                <legend>Action player signature pictures</legend>
                <form method=POST name=actionplayerform>
                <input type=hidden name=actionplayerform value=1>
                <table>
                <tr>
                        <td><input type=radio checked name=player-background value=1></td><td><img src=images/sigpic/bg1.jpg></td>
                        <td><input type=radio name=player-background value=2></td><td><img src=images/sigpic/bg2.jpg></td>
                </tr>
                </table><br>
                <table>
                <tr>
                        <td>
                                  ".echo_sigpic_item_selection_list_for_action_player(0,0)."
                                  ".echo_sigpic_item_selection_list_for_action_player(1,1)."
                                  ".echo_sigpic_item_selection_list_for_action_player(2,2)."
                                  ".echo_sigpic_item_selection_list_for_action_player(3,3)."
                                  ".echo_sigpic_item_selection_list_for_action_player(4,4)."
                                  ".echo_sigpic_item_selection_list_for_action_player(5,5)."
                        </td>
                </tr>
                </table><br>
                <div id=submit><input type=submit value=Generate></div>
                </form>
                </fieldset>

                <br>

                <fieldset>
                <legend>Commander signature pictures</legend>
                <form method=POST name=commanderform>
                <input type=hidden name=commanderform value=1>
                <table>
                <tr>
                        <td><input type=radio checked name=player-background value=1></td><td><img src=images/sigpic/bg1.jpg></td>
                        <td><input type=radio name=player-background value=2></td><td><img src=images/sigpic/bg2.jpg></td>
                </tr>
                </table><br>
                <table>
                <tr>
                        <td>
                                  ".echo_sigpic_item_selection_list_for_commander(0,0)."
                                  ".echo_sigpic_item_selection_list_for_commander(1,1)."
                                  ".echo_sigpic_item_selection_list_for_commander(2,2)."
                                  ".echo_sigpic_item_selection_list_for_commander(3,3)."
                                  ".echo_sigpic_item_selection_list_for_commander(4,4)."
                                  ".echo_sigpic_item_selection_list_for_commander(5,5)."
                        </td>
                </tr>
                </table><br>
                <div id=submit><input type=submit value=Generate></div>
                </form>
                </fieldset>
        ";
        echo "</div>";

}



function echo_overall_team_winning_percent() {
        $humanwin = get_number_of_matches_won_by_team(1, 1);
        $beastwin = get_number_of_matches_won_by_team(2, 1);
        $humansf  = number_format(get_average_sf_for_team_for_period(1, get_period(30)));
        $beastsf  = number_format(get_average_sf_for_team_for_period(2, get_period(30)));
        $total    = $humanwin + $beastwin;
        echo "<div id=teamwin>
                <fieldset>
                <legend>Total matches in 30days <b>$total</b></legend>
                <table>";
        echo "  <tr><td>Won by Humans</td><td> $humanwin (" . number_format(($humanwin/$total)*100,0) ."%) Average SF: $humansf</td></tr>
                <tr><td>Won by Beasts</td><td> $beastwin (" . number_format(($beastwin/$total)*100,0) ."%) Average SF: $beastsf</td></tr>";
        echo "</table></fieldset></div>";
}

function echo_static_misc_stats() {
        include('highest_stats_ingame.html');
}

function echo_management_page() {
	if (isset($_POST['vip_username'])) {
		add_new_vip_if_not_exists($_POST['vip_username']);
                js_href("index.php?action=manage");
	} else if (isset($_POST['new_username'])) {
		add_new_website_user_if_not_exists_and_is_valid($_POST['new_username'], $_POST['new_playerid'], $_POST['new_level'] , $_POST['new_referal']);
	} else if (isset($_POST['edit_username'])) {

	}
	echo_add_vip_table();
	echo_add_new_user_account_table();
	echo_attribute_edit_table();
}

function echo_get_lifetime_page() {
        $page = 1;
	if (!isset($_GET['playerid'])) { echo "Please do not hack u bitch.."; return; }
        if (isset($_GET['page'])) { $page = $_GET['page']; }
        $playerid   = $_GET['playerid'];
        $playername = playerid_to_playername($playerid);
        curl_get_lifetime_stats_for_playerid_starting_from_page($playerid, $playername, $page);
}

function echo_account_page() {
        echo '
                moimoi moi
	';
        echo '
        </div>
        <div id="text_bottom">
                <div id="text_bottom_left"></div>
                <div id="text_bottom_right"></div>
        </div>
        ';
        echo '<br><br><br><br>
         </div>
	<div id="text">
                <div id="text_top">
                        <div id="text_top_left"></div>
                        <div id="text_top_right"></div>
                </div>
                <div id="text_body">
        ';
        echo "mopo l�htee k�sist�";
}


function echo_add_vip_table() {
	echo '
	<fieldset><legend><font color=black>Add Existing playername to the VIP list</legend>
		Adding new player to the vip list makes the service to automaticly update their stats twice a day<br>
		<i>Playername still needs account to access the site, this will just add the player to vip list!</i>
		<table style="border-style: solid; border-width:1px;">
			<form method=POST name=addvip>
			<tr><td>Username:</td><td><input type=text name=vip_username id=username onkeyup=listusers(this.value) autocomplete="off"></td></tr>
			<tr><td></td><td><div id=userlist></div></td></tr>
			<tr><td></td><td><input type=submit value="Add user"></td></tr>
			</form>
		</table>
	</fieldset>
	';
}

function echo_add_new_user_account_table() {
	echo '
	<fieldset><legend><font color=black>Add new User account</legend>
		Add completely new user to the service, referal hash is calculated automaticly, you need to send it to the user so they can active their account<br>
		Userlevel 0 = No website access at all<br>
		Userlevel 1 = Normal user<br>
		Userlevel 2 = Admin, can manage the site<br>
		Userlevel 3 = Superadmin, practically server admin only<br>
		<table style="border-style: solid; border-width:1px;">
			<form method=POST name=adduser>
			<tr><td>Username:    </td><td><input name=new_username type=text onkeydown="listusers2(this.value)" autocomplete=off></td></tr>
			<tr><td></td><td><div id=userlist2></div></td></tr>
			<tr><td>Playerid:</td><td><input name=new_playerid id=new_playerid type=text readonly></td></tr>
			<tr><td>Referal-hash:</td><td><input name=new_referal id=new_referal type=text readonly style="width:200px;"></td></tr>
			<tr><td>Level:      </td><td><select name=new_level><option disabled>User level<option>0<option selected>1<option>2<option disabled>3</select></td></tr>
			<tr><td></td><td><input type=submit value="Add user"></td></tr>
			</form>
		</table>
	</fieldset>
	';
}

function echo_attribute_edit_table() {
	echo '
	<fieldset>
		<legend><font color=black>Edit preferences of users</legend>
		Change the attributes of an user account<br>
		Resetting password will set the password to their "username"<br>
		Changing usernames should not be done unless the user has joined a clan and clantag should be added
		<form method=POST>
                <div id=attribute_table>
		<table style="border-style: solid; border-width:1px;">
		<tr><td>Username</td><td>Reset password</td><td>Userlevel</td><td>Referal</td></tr>
	';
	$result = get_all_user_all_info();
	while ($row = mysql_fetch_array($result)) {
		list($username, $userid, $playerid, $password, $lastlogin, $referal, $level) = $row;
		if ($level==0) {
			$select = "<select><option disabled>User level<option selected>0<option>1<option>2<option disabled>3</select>";
		} else if ($level==1) {
			$select = "<select><option disabled>User level<option>0<option selected>1<option>2<option disabled>3</select>";
		} else if ($level==2) {
			$select = "<select><option disabled>User level<option disabled>0<option disabled>1<option selected>2<option disabled>3</select>";
		} else {
			$select = "<select><option disabled>User level<option disabled>0<option disabled>1<option disabled>2<option selected>3</select>";
		}
		echo "<tr><td><input type=text name=playername value=$username></td><td>link</td><td>$select</td><td>$referal</td></tr>";
	}
	echo '<tr><td><input type=submit value=Save></td><td></td><td></td><tr>';
	echo '</form></table></div>';
	
	

}



function echo_login_page() {
	if (isset($_POST['newusername']) && isset($_POST['password1'])) {
		$result = create_new_account($_POST['newusername'], $_POST['password1'], $_POST['password2'], $_POST['referal']);
	} else if (isset($_POST['username']) && isset($_POST['password'])) {
		$result = validate_login_process_for_user($_POST['username'], $_POST['password']);
	}
        // jsalert("Hello thar! To get an account contact boubbin (boubbin@gmail.com) or coolness, s2games irc-server -> #epic! Remember password and USERNAME are both case sensitive");
	echo "<center><font color=white>";
	echo '<br><br><br><br><br><br><br><br><br><br><br><br><center>';
	echo "<div id=tro><form method=POST name=login>";
	echo "<fieldset style=\"text-align:left\"><legend>Login to use this service</legend>
		<table>
		<tr>
			<td>Username:</td><td><input type=text name=username></td><td>";
	if (isset($_SESSION['err']['username'])) { echo "<font color=red>Bad username</td>"; unset($_SESSION['err']['username']); }
	echo "
		</tr>
		<tr>
			<td>Password:</td><td><input type=password name=password></td><td>";
	if (isset($_SESSION['err']['password'])) { echo "<font color=red>Bad password"; unset($_SESSION['err']['password']); }
	echo "
		</tr>	
		<tr>
			<td></td><td><input type=submit value=Login></td>
		</tr>
		</table>
		</form>
		</fieldset><br>
		<form method=POST>
		<fieldset style=\"text-align:left\"><legend>Activate your account</legend>
		<table>
		<tr>
			<td>Username:</td><td><input type=text name=newusername></td><td><td>";
	if (isset($_SESSION['err']['noreferal'])) { echo "<font color=red>No referal"; unset($_SESSION['err']['noreferal']); }
	echo "
		</tr>
		<tr>
			<td>Password:</td><td><input type=password name=password1></td><td>";
	if (isset($_SESSION['err']['passtooshort'])) { echo "<font color=red>Too short"; unset($_SESSION['err']['passtooshort']); }
	echo "
		</tr>
		<tr>
			<td>Pass again:</td><td><input type=password name=password2></td><td>";
	if (isset($_SESSION['err']['passmismatch'])) { echo "<font color=red>Mismatch"; unset($_SESSION['err']['passmismatch']); }
	echo "
		</tr>
		<tr>
			<td>Referal hash:</td><td><input type=text name=referal></td><td>";
	if (isset($_SESSION['err']['wrongreferal'])) { echo "<font color=red>Wrong referal"; unset($_SESSION['err']['wrongreferal']); }
	echo "
		</tr>			
		<tr>
			<td></td><td><input type=submit value=\"Create account\"></td>
		</tr>
		</table>
		</form>
		";
		if (isset($_SESSION['err']['activated'])) { echo "<font color=red>Error in account activation (Username case sensitive?)</font>"; unset($_SESSION['err']['activated']); }
		if (isset($_SESSION['ok']['activated'])) { echo "<font color=red>Account activated!<br>Please login</font>"; unset($_SESSION['ok']['activated']); }
	echo "</div>";
        echo '<script type="text/javascript">
                        document.login.username.focus();
                </script>
        ';
}

function echo_loading_player($state, $id) {
	if ($state) {
		echo "
			<img src=\"images/ajax-load.gif\" width=20 id=\"$id\">
		";
	} else {
		echo "
			<script language=\"Javascript\">
				toggleLayer('$id');
				document.write('<font color=#0052FF>*</font>');
			</script>
		";
	}
}
function echo_loading_match($state, $id) {
	if ($state) {
		echo "
			<img src=\"images/ajax-load2.gif\" width=20 id=\"$id\">
		";
	} else {
		echo "
			<script language=\"Javascript\">
				toggleLayer('$id');
				document.write('<font color=#00FF33>*</font>');
			</script>
		";
	}
}


function echo_meta_and_head_tags() {
	echo '
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-15" />
	<title>:: S2SP : Savage2 Stats Parser ::</title>
	<link href="style.css" rel="stylesheet" type="text/css" />
        <script src="jquery/jquery.js"></script>
        <script language="Javascript" src="js/date_picker.js"></script>
	<script language="Javascript">
		function toggleLayer(whichLayer) {
			var elem, vis;
			if(document.getElementById) // this is the way the standards work
				elem = document.getElementById(whichLayer);
			else if(document.all) // this is the way old msie versions work
				elem = document.all[whichLayer];
			else if(document.layers) // this is the way nn4 works
				elem = document.layers[whichLayer];
				vis = elem.style;
			// if the style.display value is blank we try to figure it out here
			if(vis.display==\'\'&&elem.offsetWidth!=undefined&&elem.offsetHeight!=undefined)
				vis.display = (elem.offsetWidth!=0&&elem.offsetHeight!=0)?\'block\':\'none\';
				vis.display = (vis.display==\'\'||vis.display==\'block\')?\'none\':\'block\';
		}
		function contest_listusers(username) {
			if (username.length==0) {
				document.getElementById("userlist").innerHTML="";
				document.getElementById("userlist").style.border="0px";
				return;
			}
			xmlhttp=new XMLHttpRequest();
			xmlhttp.onreadystatechange=function() {
				if (xmlhttp.readyState==4 && xmlhttp.status==200) {
					document.getElementById("userlist").innerHTML=xmlhttp.responseText;
					document.getElementById("userlist").style.border="1px solid #A5ACB2";
				}
			}
			xmlhttp.open("GET","contest_listusers.php?q="+username,true);
			xmlhttp.send();
		}
		function listusers(username) {
			if (username.length==0) {
				document.getElementById("userlist").innerHTML="";
				document.getElementById("userlist").style.border="0px";
				return;
			}
			xmlhttp=new XMLHttpRequest();
			xmlhttp.onreadystatechange=function() {	
				if (xmlhttp.readyState==4 && xmlhttp.status==200) {
					document.getElementById("userlist").innerHTML=xmlhttp.responseText;
					document.getElementById("userlist").style.border="1px solid #A5ACB2";
				}
			}
			xmlhttp.open("GET","listusers.php?q="+username,true);
			xmlhttp.send();
		}
		function clearsearch() {
			document.getElementById("userlist").innerHTML=\'\';
		}
		function listusers2(username) {
			document.forms[\'adduser\'].new_referal.value = MD5(MD5(username));
			if (username.length==0) {
				document.getElementById("userlist2").innerHTML="";
				document.getElementById("userlist2").style.border="0px";
				return;
			}
			xmlhttp=new XMLHttpRequest();
			xmlhttp.onreadystatechange=function() {	
				if (xmlhttp.readyState==4 && xmlhttp.status==200) {
					document.getElementById("userlist2").innerHTML=xmlhttp.responseText;
					document.getElementById("userlist2").style.border="1px solid #A5ACB2";
				}
			}
			xmlhttp.open("GET","listusers2.php?q="+username,true);
			xmlhttp.send();
		}
		function clearsearch2() {
			document.getElementById("userlist2").innerHTML=\'\';
		}
                function change_individual_stats_period(playerid, playername, period) {
                        this.location.href = "index.php?action=stats&playerid="+playerid+"&playername="+playername+"&period="+period;
                }
                function change_clan_stats_period(clan, period) {
                        this.location.href = "index.php?action=clan_stats&clan="+clan+"&period="+period;
                }
	</script>
	</head>
	<body>
	';
}

function echo_header() {
	echo '
	<!-- header -->
	<div id="logo">'.echo_overall_team_winning_percent().'<a href="#"><img src=images/logo.png border=0></a></div>
	<div id="header">
	<div id="left_header"></div>
	<div id="right_header"></div>
	</div>
	<div id="menu">
		<ul>
		<li><a href="index.php?">Whats up?</a></li>
                <li><a href="index.php?action=stats&playerid='.$_SESSION['playerid'].'&playername='.$_SESSION['playername'].'&period=30"><font color=green>My stats</a></li>
                <li><a href="index.php?action=match_info">Matches</a></li>
		<li><a href="index.php?action=stats&view=2&ladder=2&sort=sf">Ladder</a></li>
                <li><a href="index.php?action=sigpic">Sigpics</font></a></li>
		<li><a href="index.php?action=service">Service</a></li>
		';
	if (isset($_SESSION['authed']) && $_SESSION['level'] > 1) { echo '<li><a href="index.php?action=manage"><font color=red>Manage</font></a></li>'; }
	if (isset($_SESSION['authed']) && $_SESSION['level'] > 1) { echo '<li><a href="index.php?action=contest"><font color=red>Contests</font></a></li>'; }
	if (isset($_SESSION['authed'])) { echo '<li><a href="index.php?logout"><font color=white>Logout</font></a></li>'; }
	echo '
		</ul>
	</div>
	<!--end header -->
	<div id="content">
	<div id="content_top">
	<div id="content_top_left"></div>
	<div id="content_top_right"></div>
	</div>
	<div id="content_body">

	';
}


function echo_sidebar() {
	echo '
	<div id="sidebar">
	<div id="sidebar_top"></div>
	<div id="sidebar_body"><ul>
	';
        echo "<h1><href=index.php?action=edit_profile><img src=images/edit.png></a>My account</h1>";
        generate_my_account_list();
	echo '<br><h1><img src=images/clock1.png style="width: 18px;">Activity</h1>';
        generate_recent_activity_list();
	echo '<br><h1><img src=images/group.png style="width: 18px;">Newest users:</h1>';
        generate_new_users_list();
	echo '<br><h1>Popular profiles:</h1>';
        generate_popular_profiles_list();
        echo '
	</ul>
        </div>
	<div id="sidebar_bottom"></div>
	</div>
	<div id="text">
                <div id="text_top">
                        <div id="text_top_left"></div>
                        <div id="text_top_right"></div>
                </div>
                <div id="text_body">
	';


}

function generate_my_account_list() {
        echo "<li><a href=index.php?action=stats&playerid=$_SESSION[playerid]&playername=$_SESSION[playername]&period=30>$_SESSION[playername]</a></li>";
        $result = get_alt_accounts_for_ip($_SERVER['REMOTE_ADDR']);
        while ($row = mysql_fetch_assoc($result)) {
                $playername = $row['playername'];
                $playerid   = $row['playerid'];
                echo "<li><a href=index.php?action=stats&playerid=$playerid&playername=$playername&period=30>$playername</a></li>";
        }
}

function generate_popular_profiles_list() {
        $result = get_10_most_popular_profiles();
        while ($row = mysql_fetch_assoc($result)) {
                $playername = $row['playername'];
                $playername = preg_replace("/\[.*\]/i", "", $playername);
                $playerid   = $row['playerid'];
                $visits     = $row['visits'];
                echo "<li><a href=index.php?action=stats&playerid=$playerid&playername=$playername&period=30>$playername</a> ($visits visits)</a></li>";
        }        
}

function generate_new_users_list() {
        $result = get_3_newest_users();
        while ($row = mysql_fetch_assoc($result)) {
                $playername = $row['playername'];
                $playerid   = $row['playerid'];
                echo "<li><a href=index.php?action=stats&playerid=$playerid&playername=$playername&period=30>$playername</a></li>";
        }
}

function generate_recent_activity_list() {
        $result = get_10_recently_active_users();
        while ($row = mysql_fetch_assoc($result)) {
                $playername = $row['playername'];
                $playerid   = $row['playerid'];
                $lastlog    = $row['lastlog'];
                $time       = format_last_login_time_to_neat_format_from_minutes(ceil((time() - $lastlog)/60));
                echo "<li><a href=index.php?action=stats&playerid=$playerid&playername=$playername&period=30>$playername</a> ($time)</li>";
        }
}

function echo_last_x_chat_messages($x) {
        $result = get_last_x_chat_messages($x);
        if (mysql_num_rows($result)==0) { echo "<tr><td>Chat has no messages..</td></tr>"; return; }
        date_default_timezone_set('UTC');
        while ($row = mysql_fetch_assoc($result)) {
                $userid         = $row['userid'];
                $color          = get_chat_color_for_userid($userid);
                $username       = htmlspecialchars("<".userid_to_playername($userid).">");
                $time           = date("j/n H:i:s" ,$row['date']);
                $message        = $row['content'];
                echo "          <tr><td class=time>($time)</td><td class=username>$color$username</td><td class=message>$message</td></tr>";
        }
        date_default_timezone_set('Europe/Helsinki');
}

function echo_chat() {
        if (isset($_POST['message'])) {
                $result = validate_chat_message_and_save_it($_POST['message']);
                if ($result) { js_href("index.php?action=chat"); }
        }
        $_SESSION['chatlastlog'] = time();
        echo "<div id=chat>";
        echo "  <fieldset>
                        <legend>Chat</legend>
                        <div id=chat_table>
                        <table border=0>
                        ";
        echo_last_x_chat_messages(30);
        echo "
                        </table>
                        </div>
                </fieldset>
                <table border=0>
                <form method=POST>
                <tr><td><textarea name=message></textarea></td></tr>
                <tr><td><input type=submit value=\"Send message\"></td></tr>
                </form>
                </table>
                </fieldset>
        ";
        echo "</div>";
}



function echo_footer($time) {
	echo '
        </div>
        <div id="text_bottom">
                <div id="text_bottom_left"></div>
                <div id="text_bottom_right"></div>
        </div>
        </div>
        </div>
        <div id="content_bottom">
        <div id="content_bottom_left"></div>
        <div id="content_bottom_right"></div>
        </div>
        </div>
	<!-- footer -->
	<div id="footer">
	<div id="left_footer">&copy; Copyright 2011 <b>(boubbin)</b> Epic Warriors </div>
	<div id="right_footer">Rendering this page took '.(round(microtime(true) - $time,2)).' seconds</div>
	</div>
	<!-- end footer -->
	</body>
	</html>

';

}
