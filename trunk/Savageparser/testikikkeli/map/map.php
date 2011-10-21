<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->

<?php

function echo_js() {
        
}


?>
<script src="../../jquery/jquery.js"></script>

<font size="2">
        <table>

                <tr>
                        <td>
                                <img id=testimg src="../../images/maps/desolation.jpg">
                        </td>
                        <td>
                                <a href="#" id="event1">1min</a><br>
                                <a href="#" id="event2">2min</a><br>
                                <a href="#" id="event3">3min</a><br>
                                <a href="#" id="event4">4min</a><br>
                                <a href="#" id="event5">5min</a><br>
                                <a href="#" id="event6">6min</a><br>
                                <a href="#" id="event7">7min</a><br>
                        </td>
                </tr>
        </table>
<div id="event"></div>
<div style='display: none;' id="id">1</div>
<div id="legend">Game has started</div>


<script type="text/javascript">
        function outpost_destroyed(team) {
                $("#legend").append("Team" + team + " outpost was destroyed!<br>");
        }
        function outpost_for_team(team, quiet) {
                if (quiet == 0) { $("#legend").append("Team" + team + " makes outpost<br>"); }
        }
        function portal_for_team(team, quiet) {
                if (quiet == 0) { $("#legend").append("Team" + team + " drops spawn portal<br>"); }
                
                
        }
        function makedot_for_coord(object, x, y, quiet) {
                id = $("#id").value + 1;
                $("#id").html(id + 1);
                if (object == "outpost1") { var color = 'blue'; var size = '20px'; outpost_for_team(1, quiet);  }
                if (object == "outpost2") { var color = 'red';  var size = '20px'; outpost_for_team(2, quiet); }
                if (object == "portal1")  { var color = 'blue'; var size = '7px';  portal_for_team(1, quiet); }
                if (object == "portal2")  { var color = 'red '; var size = '7px';  portal_for_team(2, quiet); }
                if (object == "unit1")    { var color = 'blue'; var size = '3px';  }
                if (object == "unit2")    { var color = 'red';  var size = '3px';  }
                $("#event").append(
                    $("<div></div>")
                        .css('position', 'absolute')
                        .css('top', y + 'px')
                        .css('left', x + 'px')
                        .css('width', size)
                        .css('height', size)
                        .css('moz-border-radius', size)
                        .css('-webkit-border-radius', size)
                        .css('-khtml-border-radius', size)
                        .css('border-radius', size)
                        .css('background-color', color)
                );                
        }
        $('#event1').mouseover(function() {
                $("#legend").empty();
                $('#event').empty();
                makedot_for_coord("outpost1", 109, 25, 0);
                makedot_for_coord("outpost2", 111, 175, 0);
                makedot_for_coord("portal2", 124, 139, 0);
                makedot_for_coord("portal1", 49, 135, 0);
                makedot_for_coord("unit1", 72, 170);
                makedot_for_coord("unit1", 108, 62);
                makedot_for_coord("unit1", 102, 53);
                makedot_for_coord("unit1", 77, 180);
                makedot_for_coord("unit1", 75, 170);
                makedot_for_coord("unit2", 122, 105);
                makedot_for_coord("unit2", 114, 94);
                makedot_for_coord("unit2", 124, 97);
        });
        $('#event2').mouseover(function() {
                $("#legend").empty();
                $('#event').empty();
                makedot_for_coord("outpost1", 109, 25, 1);
                makedot_for_coord("portal2", 124, 139, 1);
                makedot_for_coord("portal1", 49, 135, 1);
                makedot_for_coord("unit1", 119, 173);
                makedot_for_coord("unit1", 106, 53);
                makedot_for_coord("unit1", 114, 178);
                makedot_for_coord("unit1", 110, 183);
                makedot_for_coord("unit1", 112, 130);
                makedot_for_coord("unit2", 104, 68);
                makedot_for_coord("unit2", 119, 178);
                makedot_for_coord("unit2", 163, 175);
                makedot_for_coord("unit2", 156, 178);
                outpost_destroyed(2);
        });
        $('#event3').mouseover(function() {
                $("#legend").empty();
                $('#event').empty();

        });

        $("#testimg").click(function (ev) {
        mouseX = ev.pageX;
        mouseY = ev.pageY
        //alert(mouseX + ' ' + mouseY);
        var color = 'black';
        var size = '10px';
        $("#legend").html(mouseX + "," + mouseY);
        $("body").append(
            $('<div id=event></div>')
                .css('position', 'absolute')
                .css('top', mouseY + 'px')
                .css('left', mouseX + 'px')
                .css('width', size)
                .css('height', size)
                .css('moz-border-radius', size)
                .css('-webkit-border-radius', size)
                .css('-khtml-border-radius', size)
                .css('border-radius', size)
                .css('background-color', color)
        );
    })

</script>



