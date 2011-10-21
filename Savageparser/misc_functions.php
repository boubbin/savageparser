<?php

function perform_match_search_for_attributes($_POST) {
        if (!empty($_POST['matchid'])) {
                echo_match_stats_for_matchid($_POST['matchid']);
        } else {
                $result = get_matching_matches_stats_for_attributes($_POST);
                $rows = mysql_num_rows($result);
                if ($rows == 0) { echo "<div class=error>No results for given criterias</div>"; unset($_POST); return; }
                if ($rows > 50) { echo "<div class=error>Too many results ($rows), try to filter out your search more</div>"; unset($_POST); return; }
                while ($row = mysql_fetch_assoc($result)) {
                        $matchid        = $row['matchid'];
                        $winner         = $row['winner'];
                        $map            = $row['map'] ;
                        $length         = $row['duration'];
                        $date           = date('d/m/y', $row['date']);
                        $sf             = $row['sf_team1']." - ".$row['sf_team2'];
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
                        echo "<tr><td><a href=index.php?action=match_info&matchid=$matchid>$matchid</td><td>$date</td><td>$map</td><td>$length</td><td>$winner</td><td>$sf</td></tr>";
                        echo "</td></tr></table>";
                }
        }
}

function format_last_login_time_to_neat_format_from_minutes($time) {
        if ($time < 1*60) { return $time."min"; }
        if ($time < 24*60) { return floor($time/60)."hours"; }
        return floor($time/(24*60))."days";
}

function perform_meta_jobs() {
        ob_start();
        ini_set('session.gc_maxlifetime', 26697600);
        session_start();
	echo_meta_and_head_tags();
	if (!isset($_SESSION['authed'])) { echo_remote_login_options(); die(); }
	if (isset($_GET['logout'])) { session_destroy(); header('Location: index.php'); }
	echo_header();
	echo_sidebar();
	ob_end_flush();
	flush();
        if ($_SESSION['playerid'] != '194827') { update_lastlog_for_playerid($_SESSION['playerid'], time()); }
        update_lastsf_for_playerid($_SESSION['playerid'], get_sf_for_playerid_for_period($_SESSION['playerid'], get_period(30)));
}


function get_salt() {
        return "su0laa_suo144,_v1tus71|su0l44";
}

function get_mapname_for_map($map) {
        if ($map == "ancientcities") { return "ancientcities.jpg"; }
        if ($map == "autumn") { return "autumn.jpg"; }
        if ($map == "bunker") { return "bunker.jpg"; }
        if ($map == "crossroads") { return "crossroads.jpg"; }
        if ($map == "deadlock") { return "deadlock.jpg"; }
        if ($map == "desolation") { return "desolation.jpg"; }
        if ($map == "eden") { return "eden.jpg"; }
        if ($map == "hellpeak") { return "hellpeak.jpg"; }
        if ($map == "hiddenvillage") { return "hiddenvillage.jpg"; }
        if ($map == "kunlunpass") { return "kunlunpass.jpg"; }
        if ($map == "losthills") { return "losthills.jpg"; }
        if ($map == "lostvalley") { return "lostvalley.jpg"; }
        if ($map == "mirakar") { return "mirakar.jpg"; }
        if ($map == "moonlight") { return "moonlight.jpg"; }
        if ($map == "morning") { return "morning.jpg"; }
        if ($map == "snowridge") { return "snowridge.jpg"; }
        if ($map == "storm") { return "storm.jpg"; }
        if ($map == "willow") { return "willow.jpg"; }
        return "other.jpg";
}

function form_winp($record) {
        $win    = $record[0];
        $loss   = $record[1];
        if ($win == 0 && $loss == 0) { return "NA"; }
        if ($loss == 0) { return "100%"; }
        if ($win  == 0) { return "0%"; }
        return number_format(($record[0]/($record[0]+$record[1]))*100,2) . "%";
}

function echo_chat_messages_after_period($period) {
        $new = get_number_of_chat_messages_for_period($period);
        if ($new != 0) {
                echo "<font color=red>($new)</font>";
        }
}

function get_chat_color_for_userid($userid) {
        $level = get_userlevel_for_userid($userid);
        if ($level == 2) { return "<font color=black>"; }
        if ($level == 3) { return "<b><font color=black>"; }
        return "<font color=white>";
}

function has_userid_posted_last_x_chat_messages_in_10min($userid, $x) {
        $result = get_last_x_chat_posters_userids_for_period($x, time() - 10*60);
        if (mysql_num_rows($result) == 0) { return false; }
        while ($row = mysql_fetch_row($result)) {
                if ($row[0] != $userid) { return false; }
        }
        return true;
}

function allow_chat_posting_for_userid($userid) {
        if ($_SESSION['level']>2) { return 0; }
        if ($_SESSION['level']==0) { return 1; }
        if (has_userid_posted_last_x_chat_messages_in_10min($userid, 3)) { return 2; }
        return 0;
}

function validate_chat_message_and_save_it($message) {
        $username = $_SESSION['username'];
        $userid = md5($username);
        $allow = allow_chat_posting_for_userid($userid);
        if ($allow == 0) {
                $message = escape_chat_message($message);
                save_chat_message_from_userid_at_time($message, $userid, time());
                return true;
        } else if ($allow == 1) {
                echo "<warning>Users with userlevel 0 are not allowed to post on the chat!</warning>";
        } else if ($allow == 2) {
                echo "<warning>You cant send more than 3 messages in a row for 10min!</warning><br>";
        }
        return false;
}

function escape_chat_message($str) {
        $str = strip_tags($str);
        $str = str_replace('\'', '', $str);
        return $str;
}

function confirm_remove_vip_if_exists($playerid, $playername) {
        if (is_numeric($playerid)) {
                echo '
                <script type="text/javascript">
                        function show_confirm_remove() {
                                var r = confirm(\'Are you sure you want to remove '.$playername.' from the VIP list (no automatic stats update anymore)?\');
                                if (r == true) {
                                        location.href = \'index.php?action=removevip&playerid='.$playerid.'&playername='.$playername.'&force=1\';
                                } else {
                                        location.href = \'index.php\';
                                }
                        }
                        show_confirm_remove();
                </script>';
        }
}

function iif($tst,$good,$bad) {
	return(($tst)?eval($good):eval($bad));
}

function iff($tst,$cmp,$bad) {
	return(($tst == $cmp)?$cmp:$bad);
}

function greet_and_guide_first_time_user($username) {
        $playerid  = $_SESSION['playerid'];
        $playername = $username;
        $string  = '
                <script type="text/javascript">
                        function show_confirm_firsttime()
                        {
                        var r=confirm(\'Hello ';
        $string .= $username;
        $string .= ' and welcome to Savage2 Stats Parser!\nLooks like it is your first time here, and your stats are not propably up to date!\n\nWould you like me to update the stats for you?\');
                        if (r==true) {
                          location.href = \'index.php?action=updater&userid='.$playerid.'&pass=yeah\';
                          }
                        else {
                          alert("Ok, you can also wait for me to update them automaticly twice a day!");
                          location.href = \'index.php\';
                          }
                        }
                        show_confirm_firsttime();
                </script>
        ';
        echo $string;
}

function echo_misc_stats_about_player($playerid, $playername) {
        
}

function js_on_confirm_href($text, $url, $false = 'index.php') {
         echo '
        <script type="text/javascript">
        function show_confirm_href() {
                var r = confirm(\''.$text.'\');
                if (r == true) {
                        location.href = \''.$url.'\';
                } else {
                        location.href = \''.$false.'\';
                }
        }
        show_confirm_href();
        </script>';
}

function jsalert($text) {
	echo "<script language=javascript>alert(\"$text\");</script>";
}

function js_href($url) {
        echo '<script language=javascript>location.href = \''.$url.'\';</script>';
}

function logout_user() {
	header('logout.php');
}

function add_new_vip_if_not_exists_for_playerid($playerid) {
	if (playerid_is_vip($playerid)) { return FALSE; }
	add_new_vip_record_for_playerid($playerid);
}

function add_new_vip_if_not_exists($playername) {
	$playerid = playername_to_playerid($playername);
	if (!is_numeric($playerid)) { return FALSE; }
	if (playerid_is_vip($playerid)) { return FALSE; }
	add_new_vip_record_for_playerid($playerid);
}

function add_new_website_user_if_not_exists_and_is_valid($username, $playerid, $level, $referal) {
	if (website_user_exists(md5($username))) { return FALSE; }
	if (md5(md5($username)) != $referal) { return FALSE; }
	if (!is_numeric($level) || $level > 2) { return FALSE; }
	if (!is_numeric($playerid) || $playerid < 70 || $playerid > 1000000) { return FALSE; }
	if (strlen($username) < 2) { return FALSE; }
	if (strlen($referal) < 32) { return FALSE; }
	add_new_website_user_record($username, $playerid, $level , $referal);
}


function create_new_account($username, $password1, $password2, $referal) {
	$username = mysql_escape($username);
	$password1 = mysql_escape($password1);
	$password2 = mysql_escape($password2);
	$referal = mysql_escape($referal);
	if (password_set_for_username($username)) { return FALSE; }
	if (!username_exists($username)) { $_SESSION['err']['noreferal'] = "1"; return FALSE; }
	if (!referal_exists($username, $referal)) { $_SESSION['err']['wrongreferal'] = "1"; return FALSE; }
	if (md5($password1) != md5($password2)) { $_SESSION['err']['passmismatch'] = "1"; return FALSE; }
	if (strlen($password1) < 5 || empty($password1)) { $_SESSION['err']['passtooshort'] = "1"; return FALSE; }
	if (activate_account($username, md5($password1))) { $_SESSION['err']['activated'] = "1"; return FALSE; }
	$_SESSION['ok']['activated'] = "1";
	return TRUE;
}

function validate_login_process_for_user($username, $password) {
        $uname    = strtolower($username);
	$username = strtolower($username);
	// $password = strtolower($password);
	if (strlen($password) < 3 || strlen($username) < 3) { $_SESSION['err']['password'] = "1"; return FALSE; }
	if (!username_exists($username)) { $_SESSION['err']['username'] = "1"; return FALSE; }
	if (!correct_password_for_username($username, md5($password))) { $_SESSION['err']['password'] = "1"; return FALSE; }
	$lastlog = get_lastlogin_for_userid(md5($username));
        update_lastlogin_for_username($username);
	$level = get_userlevel_for_userid(md5($username));
	$_SESSION['authed']       = 1;
	$_SESSION['playerid']     = playername_to_playerid($username);
	$_SESSION['level']        = $level;
	$_SESSION['username']     = $uname;
        $_SESSION['playername']   = $uname;
        if ($lastlog == '') {
                $_SESSION['lastlog'] = 0;
                $_SESSION['chatlastlog'] = 0;
                greet_and_guide_first_time_user($username);
        } else {
                $_SESSION['lastlog'] = $lastlog;
                $_SESSION['chatlastlog'] = $lastlog;
                header('Location: index.php');
        }
	return TRUE;
}

function run_successful_login_routines() {
        $playerid                 = get_users_playerid_for_playername($_SESSION['s2user']);
        $_SESSION['playerid']     = $playerid;
        $_SESSION['level']        = get_users_userlevel_for_playerid($playerid);
        $_SESSION['playername']   = get_users_playername_for_playerid($playerid);
        $_SESSION['ip']           = get_users_lastip_for_playerid($playerid);
        $_SESSION['lastsf']       = get_users_lastsf_for_playerid($playerid);
        $_SESSION['lastlog']      = get_users_lastlog_for_playerid($playerid);
        $_SESSION['authed']       = 1;
        header('Location: index.php');
}

function echo_remote_login_field($error = 0) {
        echo "<div id=tro><br><br><br>";
        if ($error) { echo "<font color=red>Wrong username or password</font>"; }
        echo "<fieldset><legend>Auth using Savage2 credentials</legend><table><form method=POST>
                <tr><td>Username:</td><td><input id=s2user name=s2user type=text></td></tr>
                <tr><td>Password:</td><td><input id=s2pass name=s2pass type=password></td></tr>
                </td><td></td><td><input type=submit value=Auth></td></tr>
              </form></table></fieldset></div>";
}

function echo_remote_login_options($error = 0) {
        if (isset($_POST['s2user']) && isset($_POST['s2pass'])) {
                if (!empty($_POST['s2user']) && !empty($_POST['s2pass'])) {
                        $_SESSION['s2user'] = $_POST['s2user'];
                        $_SESSION['s2pass'] = $_POST['s2pass'];
                        $status = check_login_against($_POST['s2user'], $_POST['s2pass']);
                        if ($status == 1) {
                                run_successful_login_routines();
                        } else if ($status == 0) { // not in mysql, yet
                                $arr = login_to_s2_mainpage($_POST['s2user'], $_POST['s2pass']);
                                //print_r($arr);
                                if ($arr[7] == "<script language=javascript>") {
                                        $_SESSION['html']   = $arr;
                                        //add_player_to_users_if_not_exist_new($_POST['s2user'], $_POST['s2pass']);
                                        add_player_to_users_if_not_exist();
                                        run_successful_login_routines();
                                } else {
                                        echo_remote_login_field(1);
                                }
                        } else if ($status == 2) {
                                echo_remote_login_field(1);
                        }
                } else {
                        echo_remote_login_field(0);
                }
        } else {
                echo_remote_login_field(0);
        }
}

function add_player_to_users_if_not_exist_new($playername, $password) {
        $playerid = playername_to_playerid($playername);
        if (user_exists($playerid)) { return; }
        if (!add_new_user($playerid, $playername, $password, 1, "NULL", "NULL", $_SERVER['REMOTE_ADDR'])) {
                die("Fatal error ocuured, please contact to site admin ($playerid, $playername)");
        }
        unset($_SESSION['s2pass']);
}

function add_player_to_users_if_not_exist() {
        $arr         = $_SESSION['html'];
        $playername = explode(' ', strip_tags(trim($arr[631])));
        $playername = $playername[3];
        $playerid   = trim($arr[738]);
        $regexp     = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>"; preg_match_all("/$regexp/siU", trim($playerid), $matches);
        $playerid   = str_replace("player_stats.php?id=", "",$matches[2][0]);
        if (user_exists($playerid)) { return; }
        if (!add_new_user($playerid, $playername, $_POST['s2pass'], 1, "NULL", "NULL", $_SERVER['REMOTE_ADDR'])) {
                die("Fatal error ocuured, please contact to site admin ($playerid, $playername)");
        }
        unset($_SESSION['s2pass']);
}

function check_login_against($playername, $password) {
        open_mysql_connection();
        $playername = mysql_real_escape_string($playername);
        $password   = mysql_real_escape_string($password);
        return is_valid_password_for_playername($password, $playername);
}

?>