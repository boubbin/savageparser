<?php

require('parser/index.php');
require('get_functions.php');
require('print_page_functions.php');
require('misc_functions.php');
function get_missing_matches_for_30days() {
        $result = get_matchids_for_30days();
        $last = 0;
        $missing = array();
        while ($row = mysql_fetch_assoc($result)) {
                if ($last == 0) { $last = $row['matchid']; continue; }
                $current = $row['matchid'];
                $last--;
                while ($last > $current) {
                        $last--;
                        if ($last == $current) { break; }
                        array_push($missing, $last);
                }
        }
        return $missing;
}
echo "Getting.. \n";
$missing = get_missing_matches_for_30days();
get_matchstats_for_matchids($missing);
echo "We are done here\n";
?>