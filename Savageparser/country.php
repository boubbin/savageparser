<html><head><title>Savage2 : Player regions :</title></head>
        <body>

<?php

require('essentials.php');

function echo_connections_from_country_table() {
        $result = get_ingame_connections_from_all_countries();
        $rows = mysql_num_rows($result);
        echo "<table><tr><td><font size=2>#</td><td><font size=2>Country</td><td><font size=2>Connections</td></tr>";
        $i = 0;
        while ($row = mysql_fetch_assoc($result)) {
                $i++;
                $country     = ucfirst(strtolower($row['country']));
                $connections = $row['connections'];
                echo "<tr><td><font size=2>$i</td><td><font size=2>$country</td><td><font size=2>$connections</td></tr>";
        }
        echo "</table>";
}

function echo_data_addrow_lines_for_country_map() {
        $result = get_ingame_connections_from_all_countries();
        $rows = mysql_num_rows($result);
        echo "          data.addRows($rows);\n";
        echo "          data.addColumn('string', 'Prefix');\n
                        data.addColumn('string', 'Country');\n
                        data.addColumn('number', 'Connections');\n";
        $i = 0;
        while ($row = mysql_fetch_assoc($result)) {
                $prefix1     = ucfirst(strtolower($row['prefix1']));
                $country     = ucfirst(strtolower($row['country']));
                $connections = $row['connections'];
                echo "          data.setValue($i, 0, '$prefix1');\n";
                echo "          data.setValue($i, 1, '$country');\n";
                echo "          data.setValue($i, 2, $connections);\n";
                $i++;
        }
}
function draw_country_map() {
        echo "
                <h1>Savage2 - Region of players ingame for past 2 weeks</h2>
                (53% of all connections were logged)<br>
                <font size=2 face=serif>
          <script type='text/javascript' src='https://www.google.com/jsapi'></script>
          <script type='text/javascript'>
           google.load('visualization', '1', {'packages': ['geochart']});\n
           google.setOnLoadCallback(drawRegionsMap);\n

            function drawRegionsMap() {\n
              var data = new google.visualization.DataTable();\n
        ";
        echo_data_addrow_lines_for_country_map();
        echo "
                var options = {};\n
                var container = document.getElementById('map_canvas');\n
                var geochart = new google.visualization.GeoChart(container);\n
                geochart.draw(data, options);\n
                };
        </script>
        </head>
        <body>
        <div id='map_canvas' style=\"width: 1200px; height: 700px; border: solid 1px;\"></div>
        </body>
        ";
        echo_connections_from_country_table();
}
draw_country_map();