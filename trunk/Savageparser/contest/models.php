<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function return_recent_contest_info_for_id($id) {
        $str  = '<div id="summary_table_info"><table>';
        $result1 = get_contest_info_for_contestid($id);
        while ($row = mysql_fetch_array($result1)) {
                $id             = $row['id'];
                $name           = $row['name'];
                $functionid     = $row['functionid'];
                $description    = $row['description'];
                $start          = date('d/m/Y',$row['start']);
                $end            = date('d/m/Y',$row['end']);
                $period         = $row['start'];
                $period         = $row['end']-$period;
                $periodd        = number_format($period/24/60/60,1);
                $startd         = number_format(($row['start']-time())/24/60/60,1);
                $label          = get_string_label_for_contest_functionid($functionid);
                $contestors     = get_list_of_contestors_in_contest($id);
        }
        $str .= "<tr><td>Started:</td><td>$start ($startd days ago)</td></tr>";
        $str .= "<tr><td>Ended:</td><td>$end (duration $periodd days)</td></tr>";
        $str .= "<tr><td>Description:</td><td>$description</td></tr>";
        $str .= "</table></div>";
        $str .= '<div id="summary_table"><table>';
        $result1 = get_contest_info_for_contestid($id);
        while ($row = mysql_fetch_array($result1)) {
                $id             = $row['id'];
                $name           = $row['name'];
                $functionid     = $row['functionid'];
                $description    = $row['description'];
                $start          = date('d/m/Y',$row['start']);
                $end            = date('d/m/Y',$row['end']);
                $period1        = $row['start'];
                $period2        = $row['end'];
                $playtime       = $row['min_playtime'];
                $label          = get_string_label_for_contest_functionid($functionid);
        }
        $str .= "<tr><td class=rank><b>#</b></td><td><b>Playername</b></td><td><b>$label</b></td><td><b>Matches played</b></td><td><b>Playtime(h)</b></td><tr>";
        $result2 = get_contestors_results_in_contest_for_period_with_enough_playtime_dynamic_queries($id, $period1, $period2, $playtime);
        $result3 = get_contestors_results_in_contest_for_period_with_not_enough_playtime_dynamic_queries($id, $period1, $period2, $playtime);
        $i = 0;
        while ($row = mysql_fetch_array($result2)) {
                $i++;
                $playername     = $row['playername'];
                $stat           = number_format($row['stat'],1);
                $matches        = $row['matches'];
                $time           = number_format($row['time'],1);
                $str           .= "<tr><td class=rank><b>$i</b></td><td>$playername</td><td class=stat>$stat</td><td>$matches</td><td>$time</td><tr>";

        }
        while ($row = mysql_fetch_array($result3)) {
                $i++;
                $playername     = $row['playername'];
                $stat           = number_format($row['stat'],1);
                $matches        = $row['matches'];
                $time           = number_format($row['time'],1);
                $str           .= "<tr class=notranked><td><b></b></td><td>$playername</td><td>$stat</td><td>$matches</td><td><font color=red>$time</td><tr>";

        }
        $str .= "</table></div>";
        return $str;
}

function return_upcoming_contest_info_for_id($id) {
        $str  = '<div id="summary_table_info"><table>';
        $result1 = get_contest_info_for_contestid($id);
        while ($row = mysql_fetch_array($result1)) {
                $id             = $row['id'];
                $name           = $row['name'];
                $functionid     = $row['functionid'];
                $description    = $row['description'];
                $start          = date('d/m/Y',$row['start']);
                $end            = date('d/m/Y',$row['end']);
                $period         = $row['start'];
                $period         = $row['end']-$period;
                $periodd        = number_format($period/24/60/60,1);
                $startd         = number_format(($row['start']-time())/24/60/60,1);
                $label          = get_string_label_for_contest_functionid($functionid);
                $contestors     = get_list_of_contestors_in_contest($id);
        }
        $str .= "<tr><td>Will start:</td><td>$start (in $startd days)</td></tr>";
        $str .= "<tr><td>Will end:</td><td>$end (duration $periodd days)</td></tr>";
        $str .= "<tr><td>Description:</td><td>$description</td></tr>";
        $str .= "<tr><td>Participants:</td><td style=\"line-height: 17px;\">$contestors</td></tr>";
        $str .= "</table></div>";
        return $str;
}

function return_info_for_contest($id) {
        $str  = '<div id="summary_table_info"><table>';
        $result1 = get_contest_info_for_contestid($id);
        while ($row = mysql_fetch_array($result1)) {
                $id             = $row['id'];
                $name           = $row['name'];
                $functionid     = $row['functionid'];
                $description    = $row['description'];
                $start          = date('d/m/Y',$row['start']);
                $end            = date('d/m/Y',$row['end']);
                $min_time       = $row['min_playtime'];
                $startd         = number_format((time()-$row['start'])/24/60/60,1);
                $endd           = number_format(($row['end']-time())/24/60/60,1);
                $period1        = $row['start'];
                $period2        = $row['end'];
                $label          = get_string_label_for_contest_functionid($functionid);
        }
        $str .= "<tr><td>Started:</td><td>$start ($startd days ago)</td></tr>";
        $str .= "<tr><td>Will End:</td><td>$end (in $endd days)</td></tr>";
        $str .= "<tr><td>Min playtime:</td><td>$min_time hours</td></tr>";
        $str .= "<tr><td>Description:</td><td>$description</td></tr>";
        $str .= "</table></div>";
        return $str;
}

function return_summary_for_contestid($id) {
        $str  = '<div id="summary_table"><table>';
        $result1 = get_contest_info_for_contestid($id);
        while ($row = mysql_fetch_array($result1)) {
                $id             = $row['id'];
                $name           = $row['name'];
                $functionid     = $row['functionid'];
                $description    = $row['description'];
                $start          = date('d/m/Y',$row['start']);
                $end            = date('d/m/Y',$row['end']);
                $period1        = $row['start'];
                $period2        = $row['end'];
                $playtime       = $row['min_playtime'];
                $label          = get_string_label_for_contest_functionid($functionid);
        }
        $str .= "<tr><td class=rank><b>#</b></td><td><b>Playername</b></td><td><b>$label</b></td><td><b>Matches played</b></td><td><b>Playtime(h)</b></td><tr>";
        $result2 = get_contestors_results_in_contest_for_period_with_enough_playtime_dynamic_queries($id, $period1, $period2, $playtime);
        $result3 = get_contestors_results_in_contest_for_period_with_not_enough_playtime_dynamic_queries($id, $period1, $period2, $playtime);
        $i = 0;
        while ($row = mysql_fetch_array($result2)) {
                $i++;
                $playername     = $row['playername'];
                $stat           = number_format($row['stat'],1);
                $matches        = $row['matches'];
                $time           = number_format($row['time'],1);
                if ($matches == 0) {
                                $time = number_format(get_playtime_for_commander_in_range($row['playerid'], $period1, $period2),1);
                                $matches = number_format(get_num_of_matches_for_commander_in_range($row['playerid'], $period1, $period2));
                }
                $str           .= "<tr><td class=rank><b>$i</b></td><td>$playername</td><td class=stat>$stat</td><td>$matches</td><td>$time</td><tr>";

        }
        while ($row = mysql_fetch_array($result3)) {
                $i++;
                $playername     = $row['playername'];
                $stat           = number_format($row['stat'],1);
                $matches        = $row['matches'];
                $time           = number_format($row['time'],1);
                $str           .= "<tr class=notranked><td><b></b></td><td>$playername</td><td>$stat</td><td>$matches</td><td><font color=red>$time</td><tr>";

        }
        $str .= "</table></div>";
        return $str;
}


function return_ongoing_contests_for_ongoing_view() {
        $str = '';
        $result = get_ongoing_contests_without_upcoming();
        while ($row = mysql_fetch_array($result)) {
                $id             = $row['id'];
                $name           = $row['name'];
                $functionid     = $row['functionid'];
                $description    = $row['description'];
                $start          = date('d/m/Y',$row['start']);
                $end            = date('d/m/Y',$row['end']);
                $endd           = number_format(($row['end']-time())/24/60/60,1);
                $link           = "<a href=index.php?action=show_contest_summary&id=$id>";
                $staticon       = get_corresponding_stat_icon_url_for_contest_functionid($functionid);
                $headericon     = get_corresponding_header_icon_url_for_contest_functionid($functionid);
                $str           .= "<tr><td title=\"$description\">$link<img class=\"icon\" src=\"$staticon\"></a></td>
                                       <td title=\"$description\">
                                                $link<img class=\"header\" src=\"$headericon\" title=\"$description\"></a><br>
                                                $name<br>
                                                Ends: $end (in $endd days)<br>
                                        </td>
                                   </tr>";
        }
        return $str;
}
function return_upcoming_contests_for_upcoming_view() {
        $str = '';
        $result = get_upcoming_contests();
        if (!$result) { return ''; }
        while ($row = mysql_fetch_array($result)) {
                $id             = $row['id'];
                $name           = $row['name'];
                $functionid     = $row['functionid'];
                $description    = $row['description'];
                $start          = date('d/m/Y',$row['start']);
                $end            = date('d/m/Y',$row['end']);
                $startd         = number_format(($row['start']-time())/24/60/60,1);
                $link           = "<a href=index.php?action=show_contest_summary&id=$id>";
                $staticon       = get_corresponding_stat_icon_url_for_contest_functionid($functionid);
                $headericon     = get_corresponding_header_icon_url_for_contest_functionid($functionid);
                $str           .= "<tr><td title=\"$description\">$link<img class=\"icon\" src=\"$staticon\"></a></td>
                                       <td title=\"$description\">
                                                $link<img class=\"header\" src=\"$headericon\" title=\"$description\"></a><br>
                                                $name<br>
                                                Starts: $start (in $startd days)<br>
                                        </td>
                                   </tr>";        }
        return $str;
}
function return_recent_contests_for_recent_view() {
        $str = '';
        $result = get_recent_contests();
        if (!$result) { return ''; }
        while ($row = mysql_fetch_array($result)) {
                $id             = $row['id'];
                $name           = $row['name'];
                $functionid     = $row['functionid'];
                $description    = $row['description'];
                $end            = date('d/m/Y',$row['end']);
                $link           = "<a href=index.php?action=show_contest_summary&id=$id>";
                $staticon       = get_corresponding_stat_icon_url_for_contest_functionid($functionid);
                $headericon     = get_corresponding_header_icon_url_for_contest_functionid($functionid);
                $str           .= "<tr><td title=\"$description\">$link<img class=\"icon\" src=\"$staticon\"></a></td>
                                       <td title=\"$description\">
                                                $link<img class=\"header\" src=\"$headericon\" title=\"$description\"></a><br>
                                                $name<br>
                                                Ended: $end<br>
                                        </td>
                                   </tr>";        }
        return $str;
}
?>
