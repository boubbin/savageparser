<?php

require('get_functions.php');
require('parser/mysql_functions.php');


/*
 * LINE FUCKING CHART
 */

function echo_unique_playercount_daily_for_period_datarows($period) {
        $result = get_number_of_unique_and_nonunique_players_ingame_daily_for_period($period);
        $i = 1;
        while ($row = mysql_fetch_assoc($result)) {
                $day        = $row['day'];
                $uniq       = $row['unique_players'];
                $nonuniq    = $row['players'];
                $weekday    = $row['weekday'];
                echo "var row = data.addRow([\"$day $weekday\", $uniq, $nonuniq]);\n";
                $i++;

        }
}


function echo_google_graph_for_unique_playercount_daily_for_days($givenperiod = 30) {
        if (!is_numeric($givenperiod)) { return 0; }
        if ($givenperiod > 120) { $givenperiod = 120; }
        $period    = get_period($givenperiod)-(23*60*60);
        $unique    = number_format(get_number_of_unique_players_ingame_in_period($period));
        $nonunique = number_format(get_number_of_players_ingame_in_period($period));
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
                                data.addColumn(\'number\', \'Number of unique players ingame\');
                                data.addColumn(\'number\', \'Number of players ingame\');
                                '; flush();
                                echo_unique_playercount_daily_for_period_datarows($period);
                                echo '
                                // Create and draw the visualization.
                                var chart = new google.visualization.LineChart(document.getElementById(\'chart_div2\'));
                                chart.draw(data, {
                                                chartArea: { left:80,top:40,width:\'85%\',height:\'60%\' },
                                                title: \'Unique and non-unique players ingame daily for '.$givenperiod.' days (total for period: '.$unique."/".$nonunique.')\',
                                                curveType: "function",
                                                width: 650, height: 250,
                                                vAxis: {minValue: 20, maxValue: 20, title: \'Number of players ingame\'},
                                                hAxis: {

                                                        maxValue: 10, minvalue: 0, title: \'Date\'
                                                },
                                                legend: \'top\',
                                                pointSize: 6
                                        }
                                        );

                      }
                      google.setOnLoadCallback(drawVisualization_matches_daily);
                    </script>
                    <div id="chart_div2" style="width: 650px; height: 250px; border: solid 1px;"></div>
                </html>
        ';
}

flush();
if (isset($_GET['days'])) { $days = $_GET['days']; } else { $days = 30; }
echo_google_graph_for_unique_playercount_daily_for_days($days);
?>
