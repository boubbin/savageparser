<font color="white" size="2">
<?php

require('essentials.php');

function noname() {
      
}
//echo_meta_and_head_tags();
$playerid = playername_to_playerid("Netukka");

echo "<table><tr><td>";

$result = get_destroyed_gadgets_for_playerid_for_period($playerid, get_period(30));
while ($row = mysql_fetch_assoc($result)) {
        asort($row);
        $row = array_reverse($row, true);
        foreach ($row as $key => $value) {
                $object = $key;
                $count  = $value;
                if ($count == "") { $count = 0; }
                $img = get_corresponding_icon_for_event_object($object);
                echo "<img src=images/stats/$img width=20>$count ($object)<br>";
        }
}
echo "</td><td>";
$result = get_destroyed_buildings_for_playerid_for_period($playerid, get_period(30));
while ($row = mysql_fetch_assoc($result)) {
        asort($row);
        $row = array_reverse($row, true);
        foreach ($row as $key => $value) {
                $object = $key;
                $count  = $value;
                $img = get_corresponding_icon_for_event_object($object);
                echo "<img src=images/stats/$img width=20>$count ($object)<br>";
        }
}
echo "</td><td>";
$result = get_destroyed_units_for_playerid_for_period($playerid, get_period(30));
while ($row = mysql_fetch_assoc($result)) {
        asort($row);
        $row = array_reverse($row, true);
        foreach ($row as $key => $value) {
                $object = $key;
                $count  = $value;
                $img = get_corresponding_icon_for_event_object($object);
                echo "<img src=images/stats/$img width=20>$count ($object)<br>";
        }
}
echo "</td><tr>";
echo "</table>";
?>
