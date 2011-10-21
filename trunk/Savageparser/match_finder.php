<?php

function echo_overriding_css() {
        echo '<style type="text/css"><!--
                table {
                        border-style: solid;
                        border-width: 1px;
                }
                --></style>';
}

require('essentials.php');
echo_overriding_css();
echo_jquery_load();
echo_datepicker_load();
echo_match_search_form();
if (!empty($_POST)) { perform_match_search_for_attributes($_POST); }
?>
