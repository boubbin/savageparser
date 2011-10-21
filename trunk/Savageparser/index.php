<?php
//echo time();
        $time = microtime(true);
	require('print_page_functions.php');
	require('echo_mainpage.php');
	require('get_functions.php');
	require('misc_functions.php');
	include('parser/index.php');
        include('parser/get_lifetime.php');
        perform_meta_jobs();
	if (isset($_GET['action'])) { $action = $_GET['action']; }
	else { $action = NULL; }
	if (is_null($action)) {
		echo_mainpage();
	} else if ($action=="stats") {
		if (!isset($_GET['playerid'])) {
			echo_ordered_summary_of_stats();
		} else {
			$playerid   = $_GET['playerid'];
			$playername = $_GET['playername'];
			echo_player_stats_page($playerid, $playername);
                        if ($_SESSION['playerid'] != $playerid && $_SESSION['playerid'] != '194827') { increase_visit_count_for_profile($playerid); }
		}
	} else if ($action == "service") {
		echo_database_stats();
	} else if ($action == "account") {
		echo_account_page();
	} else if ($action == "misc") {
                echo_static_misc_stats();
        } else if ($action == "manage") {
		if ($_SESSION['level'] < 2) { js_href("index.php"); die(); }
		echo_management_page();
	} else if ($action == "sigpic") {
                echo_sigpic_page();
        } else if ($action == "clan_stats") {
                if (!isset($_GET['clan'])) { js_href("index.php"); die(); }
                // if ($_SESSION['level'] < 2) { js_href("index.php"); die(); } // comment this line when feature done, ok?
                $period = 30;
                if (isset($_GET['period'])) { $period = $_GET['period']; }
                echo_clan_stats_for_clan_for_period($_GET['clan'], $period);
        } else if ($action == "contest") {
                if ($_SESSION['level'] <= 1) { js_href("index.php"); die(); }
                echo_contest_management_page();
        } else if ($action == "edit_profile") {
                echo_profile_management_page();
        } else if ($action == "match_info") {
                echo_match_info_page();
        } else if ($action == "test") {
                echo_test();
        }
        echo_pending_jobs_to_be_done();
        echo_footer($time);
	if (!isset($_SESSION['alert'])) {
		$_SESSION['alert'] = 1;
		// jsalert("Sigpic betas are here");
	}
?>
