<?php

require('../essentials.php');

function event_data_exists_for_matchid($matchid) {
        $query = "SELECT 1 FROM eventlog WHERE matchid = '$matchid' LIMIT 1";
        open_mysql_connection();
        $result = mysql_query($query);
        $result = mysql_fetch_row($result);
        mysql_close();
        if ($result[0]==1) { return true; }
        return false;
}

function add_new_event_log_file($matchid, $performer_playerid, $performer_unit, $action, $target_playerid, $target_unit, $time, $coord, $event_num ) {
        $query = "INSERT INTO eventlog (matchid, performer_playerid, performer_unit, action, target_playerid, target_unit, time, coord, event_num) VALUES ('$matchid', '$performer_playerid', '$performer_unit', '$action', '$target_playerid', '$target_unit', '$time', '$coord', '$event_num')";
        open_mysql_connection();
	$result = mysql_query($query);
        mysql_close();
        if ($result) { return true; }
        return false;
}

function is_valid_event_data($matchid, $action, $performer_playerid, $target_playerid, $time, $event_num) {
        if (!is_numeric($matchid)) { echo "1 ($matchid) "; return false; }
        if (!is_numeric($event_num)) { echo "2 ($event_num) "; return false; }
        if (!is_numeric($time)) { echo "3 ($time) "; return false; }
        if (!is_numeric($performer_playerid)) { echo "4 ($performer_playerid) "; return false; }
        if (!is_numeric($target_playerid)) { echo "5 ($target_playerid) "; return false; }
        if (!in_array($action, array('built', 'join', 'killed', 'placed', 'spawn', 'leave'))) { echo "UNKNOWN ACTION: $action\n<br>"; return false; }
        return true;
}



function dump_raw_eventlog_to_database($file) {
        $remove  = array('event');
        $matchid = str_ireplace($remove, "", $file);
        if (event_data_exists_for_matchid($matchid)) { return 2; }
        $raw     = file_get_contents("/home/boubbino/public_html/savage/stats_files/files/".$file);
        $raw     = str_replace("}{", "}\n{", $raw);
        $arr     = explode("\n", $raw);
        $tmp     = array();
        $i       = 0;
        foreach ($arr as $line) {
                $i++;
                $line = str_ireplace('None', "0", $line);
                $remove = array('\\', '{', '}', '\'', 'on_type: ', 'by_type: ',  'action: ', 'on: ', 'onid: ', 'coord: ', 'event: ', 'time: ', 'type: ', 'byid: ', 'by: ', 'match: ', 'map: ');
                $tmp = explode(",", str_ireplace($remove, "", $line));
                if (!is_numeric(trim($tmp[0]))) { continue; }
                $action                 = trim($tmp[4]);
                $target_unit            = trim($tmp[1]);
                $performer_unit         = trim($tmp[2]);
                $performer_playerid     = trim($tmp[6]);
                $target_playerid        = trim($tmp[0]);
                $time                   = trim($tmp[5]);
                $coord                  = trim($tmp[8]);
                $event_num              = trim($tmp[9]);
                if (!is_valid_event_data($matchid, $action, $performer_playerid, $target_playerid, $time, $event_num)) { print_r($tmp); return 1; }
                if (add_new_event_log_file($matchid, $performer_playerid, $performer_unit, $action, $target_playerid, $target_unit, $time, $coord, $event_num) == false) { return 3; }
        }
        return 0;
}

function deploy_all_event_files() {
        echo "Deploying event-files:";
        if (isset($_GET['q'])) { $q = 1; }
        else { $q = 0; }
        flush();
        $handle = opendir('/home/boubbino/public_html/savage/stats_files/files');
        while (false !== ($file = readdir($handle))) {
                if (!preg_match('/event/', $file)) { continue; }
                echo "\n";
                if (!$q) echo " file: $file: ";
                $status = dump_raw_eventlog_to_database($file);
                if ($status == 0) {
                        echo "OK";
                        copy("/home/boubbino/public_html/savage/stats_files/files/$file", "/home/boubbino/public_html/savage/stats_files/bu_files/$file");
                        unlink("/home/boubbino/public_html/savage/stats_files/files/$file");
                } elseif ($status == 1) {
                        echo "Not valid data";
                } elseif ($status == 2) {
                        echo "Data already exists";
                        unlink("/home/boubbino/public_html/savage/stats_files/files/$file");
                } elseif ($status == 3) {
                        echo "Query didn't succeed: \n";
                }
        }
}

deploy_all_event_files();

?>
