<?php
        require('echo_functions.php');
        require('models.php');
        require('../parser/mysql_functions.php');
        require('../get_functions.php');
        require('../misc_functions.php');
        echo_header();
        echo_body();
        $action = '';
        if (isset($_GET['action'])) { $action = $_GET['action']; }
        if ($action == "show_contest_summary") {
                if (isset($_GET['id'])) {
                        if (is_numeric($_GET['id'])) {
                                if (contest_for_id_exists($_GET['id'])) {
                                        $id = $_GET['id'];
                                } else { js_href("index.php"); return; }
                        } else { js_href("index.php"); return; }
                } else { js_href("index.php"); return; }
                echo_contest_summary_for_id($id);
        } else {
                echo_mainpage();
        }
        echo_footer();
?>


