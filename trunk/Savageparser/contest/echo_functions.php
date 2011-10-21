<?php

/*
 * Functions with real dynamic content
 */

function echo_summary_view_for_contest($id) {
        $image = get_corresponding_header_icon_url_for_contest_functionid(get_functionid_for_contestid($id));
        echo "<img class=\"summary\" src=\"$image\">";
        if (contest_is_upcoming($id)) {
               echo return_upcoming_contest_info_for_id($id);
        } else if (contest_is_recent($id)) {
                echo return_recent_contest_info_for_id($id);
        } else {
               echo return_info_for_contest($id);
               echo return_summary_for_contestid($id);
        }
}


function echo_ongoing_view() {
        echo '                        <div id="ongoing"><img src="../images/contest/ongoing.png">
                                <div id="contest_table">
                                        <table>
                                                '.return_ongoing_contests_for_ongoing_view().'
                                        </table>
                                </div>
                        </div>';
}
function echo_upcoming_view() {
        echo '                        <div id="upcoming"><img src="../images/contest/upcoming.png">
                                <div id="contest_table">
                                        <table>
                                               '.return_upcoming_contests_for_upcoming_view().'
                                        </table>
                                </div>
                        </div>';
}
function echo_recent_view() {
        echo '                        <div id="recent"><img src="../images/contest/recent.png">
                                <div id="contest_table">
                                        <table>
                                                '.return_recent_contests_for_recent_view().'
                                        </table>
                                </div>
                        </div>';
}


/*
 * More Static ones here..
 */

function echo_contest_summary_for_id($id) {
        echo_summary_view_for_contest($id);
}

function echo_mainpage() {
        echo_ongoing_view();
        echo_upcoming_view();
        echo_recent_view();
}

function echo_header() {
        echo '<html>
        <head>
                <title>S2SP :: Contests ::</title>
                <link href="style.css" rel="stylesheet" type="text/css" />
        </head>';
}

function echo_body() {
        echo '
        <body>
                <div id="wrapper">
                        <div id="header">
                                <a href=index.php><img src="../images/contest/contests.png"></a><br>
                        </div>
                        <br><br>';
}

function echo_footer() {
        echo '                </div>
                              <div id="bottom">
                                        
                              </div>
        </body>
</html>';
}



?>
