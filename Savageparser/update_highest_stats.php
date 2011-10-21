<?php

require('print_page_functions.php');
require('echo_mainpage.php');
require('get_functions.php');
require('misc_functions.php');
require('parser/mysql_functions.php');
require('parser/print_functions.php');
require('parser/http_functions.php');
require('parser/functions.php');

function update_highest_stats_ingame() {
        if (!isset($_GET['period'])) {
                $period = '1';
        } else { $period = '0'; }
        $line  = '';
        $line .= "<br><h1><span>Highest stat in game</span></h1><br>
                  This is static view that is beign updated twice a day<br>
                  <a href=update_highest_stats.php>Click here to update this view now</a><br>
                  <table border=1>
                  <tr>";
        $line .= get_html_highest_sf_in_game_table($period);
        $line .= get_html_most_kills_in_game_table($period);
        $line .= get_html_most_healed_in_game_table($period);
        $line .= get_html_best_kd_in_game_table($period);
        $line .= "	</tr></table>";
        $line .= "<table border=1>
                <tr>";
        $line .= get_html_most_repaired_in_game_table($period);
        $line .= get_html_most_bd_in_game_table($period);
        $line .= get_html_most_souls_in_game($period);
        $line .= get_html_deaths_in_game_table($period);
        $line .= "	</tr></table>";
        $file = fopen('highest_stats_ingame.html', 'w');
        fputs($file, "$line");
        fclose($file);
	exec("chmod 777 highest_stats_ingame.html");
}
update_highest_stats_ingame();
header('Location: index.php?action=misc');
?>
