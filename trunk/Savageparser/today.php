<?php

require('get_functions.php');
require('parser/mysql_functions.php');

/*
 * Bar Fucking Chart
 */

function echo_matches_played_weekly_data_addrow_lines($period) {
        $i = 0;
        $result = get_number_of_matches_played_for_period_grouped_by_weekday($period);
        while ($row = mysql_fetch_assoc($result)) {
                $weekday = $row['weekday'];
                $matches = $row['matches'];
                echo "
                        data.addRows(1);
                        data.setValue($i, 0, '$weekday');
                        data.setValue($i, 1, $matches);
                ";
                $i++;
        }
}

/*
 * LINE FUCKING CHART
 */

function echo_played_daily_matches_for_period_add_datarows($period) {
        $result = get_number_of_daily_matches_for_period($period);
        $rows   = mysql_num_rows($result);
        $i = 1;
        while ($row = mysql_fetch_assoc($result)) {
                $day        = $row['day'];
                $matches    = $row['matches'];
                echo "var row = data.addRow([\"$day\", $matches]);\n";
                $i++;

        }
}

/*
 * PIE FUCKING CHART
 */

function echo_most_matches_played_data_addrow_lines_for_vips_for_period($period) {
        $result = get_number_of_actionplayer_matches_for_all_vips_for_period($period);
        $rows   = mysql_num_rows($result);
        echo "data.addRows($rows);\n";
        $i = 0;
        while ($row = mysql_fetch_assoc($result)) {
                $playername = $row['playername'];
                $matches    = $row['matches'];
                echo "data.setValue($i, 0, '$playername');\n";
                echo "data.setValue($i, 1, $matches);\n";
                $i++;

        }
}

function echo_google_graph_for_matches_played_daily_for_period($givenperiod = 30) {
        $period = get_period($givenperiod)-(23*60*60);
        echo '

                    <script type="text/javascript" src="/js/js/ajax-dynamic-content.js"></script>
                    <script type="text/javascript" src="/js/js/ajax.js"></script>
                    <script type="text/javascript" src="/js/js/ajax-tooltip.js"></script>
                    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
                    <script type="text/javascript">
                      google.load(\'visualization\', \'1\', {packages: [\'corechart\']});
                    </script>
                    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
                    <script type="text/javascript">
                      function drawVisualization_matches_daily() {
                                // Create and populate the data table.
                                var data = new google.visualization.DataTable();
                                data.addColumn(\'string\', \'Day\');
                                data.addColumn(\'number\', \'Matches played\');
                                '; flush();
                                echo_played_daily_matches_for_period_add_datarows($period);
                                echo '
                                // Create and draw the visualization.
                                var chart = new google.visualization.LineChart(document.getElementById(\'chart_div2\'));
                                chart.draw(data, {
                                                chartArea: { left:80,top:40,width:\'80%\',height:\'60%\' },
                                                title: \'Number of daily played matches for last '.$givenperiod.' days\',
                                                curveType: "function",
                                                width: 600, height: 250,
                                                vAxis: {minValue: 20, maxValue: 20, title: \'Number of played matches\'},
                                                hAxis: {

                                                        slantedTextAngle: 40, maxValue: 10, minvalue: 0, title: \'Date\'
                                                },
                                                legend: \'top\',
                                                pointSize: 6
                                        }
                                        );

                      }
                      google.setOnLoadCallback(drawVisualization_matches_daily);
                    </script>
                    <div id="chart_div2" style="width: 600px; height: 250px; border: solid 1px;"></div>
                </html>
        ';
}

function echo_google_graph_for_matches_in_period_for_players($givenperiod = 1) {
        $period = get_period($givenperiod);
        echo '
                    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
                    <script type="text/javascript">
                    google.load(\'visualization\', \'1\', {packages: [\'corechart\']});
                    </script>
                    <script type="text/javascript">
                      function drawVisualization_today() {
                                // Create and populate the data table.
                                var data = new google.visualization.DataTable();
                                data.addColumn(\'string\', \'Player\');
                                data.addColumn(\'number\', \'Matches in '.$givenperiod.' days\');
                                '; flush();
                                echo_most_matches_played_data_addrow_lines_for_vips_for_period($period);
                                echo '
                                // Create and draw the visualization.
                                var chart = new google.visualization.PieChart(document.getElementById(\'chart_div1\'));
                                chart.draw(data, {width: 600, height: 600, title: \'Matches in '.$givenperiod.'days\'});
                      }
                      google.setOnLoadCallback(drawVisualization_today);
                    </script>
                    <div id="chart_div1" border=1></div>
                </html>
        ';
}

function echo_google_graph_for_matches_played_for_weekday_for_period($givenperiod = 30) {
        $period = get_period($givenperiod);
        echo '
                <script type="text/javascript" src="https://www.google.com/jsapi"></script>
                <script type="text/javascript">
                google.load("visualization", "1", {packages:["corechart"]});
                google.setOnLoadCallback(drawChart_weekday);
                function drawChart_weekday() {
                var data = new google.visualization.DataTable();
                data.addColumn(\'string\', \'Weekday\');
                data.addColumn(\'number\', \'Matches played\');
                var formatter = new google.visualization.BarFormat({showValue: 1});
                formatter.format(data, 0);
                formatter.format(data, 1);
                ';
                echo_matches_played_weekly_data_addrow_lines($period);
                echo '
                var chart = new google.visualization.BarChart(document.getElementById(\'chart_div3\'));
                chart.draw(data, {
                        allowHtml: true,
                        width: 600, height: 340, title: \'Total number of matches played on weekdays for last '.$givenperiod.' days\',
                        vAxis: {
                                minValue: 0,
                                title: \'Weekday\'
                        },
                        hAxis: {
                                minValue: 0,
                                title: \'Matches played\'
                        },
                        legend: \'top\',
                        }
                        );
                }
                </script>
                <div id="chart_div3" style="width: 600px; height: 340px; border: solid 1px;"></div>
    ';      
}



flush();
if (isset($_GET['days'])) { $days = $_GET['days']; } else { $days = 30; }
//echo_google_graph_for_matches_in_period_for_players($days);
echo_google_graph_for_matches_played_daily_for_period($days);
echo "<br>";
echo_google_graph_for_matches_played_for_weekday_for_period($days);
?>
