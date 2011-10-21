/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


// Add player to search
function addplayer() {
	var id = document.getElementById("id").value;
	$("#players").append("<p id='row" + id + "' style='display: none;'><label for='txt" + id + "'>Player " + id + "&nbsp;&nbsp;<input type='text' size='20' name='players[]' id='txt" + id + "' onkeyup='match_search_listusers(this.value, "+id+")'><input type=hidden name=playerids[] id='playerid" + id + "'>&nbsp;&nbsp<a href='#' onClick='removeplayer(\"#row" + id + "\"); return false;'><img src=images/remove_players.png width=16></a><br><div id='playersearch" + id + "'></div><p>");
        $("#row" + id).toggle('fast', function() {});
	id = (id - 1) + 2;
	document.getElementById("id").value = id;
}

function add_date() {
        $('#date').toggle('fast', function() {});
        $('#date_field').toggle('fast', function() {});
        $('#date').val('');
        $('#date_add_icon').toggle('fast', function() {});
        $('#date_remove_icon').toggle('fast', function() {});
}
function remove_date() {
        add_date();
}

function removeplayer(id) {
        $(id).toggle('fast', function() { $(id).remove(); });
}

function match_search_clearsearch(id) {
        field = "playersearch"+id;
        $('#'+field).toggle('fast', function() {});
}
function match_search_listusers(username, id) {
        if (username.length==0) {
                field = "playersearch"+id;
                document.getElementById(field).innerHTML="";
                document.getElementById(field).style.border="0px";
                return;
        }
        xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
                field = "playersearch"+id;
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        document.getElementById(field).innerHTML=xmlhttp.responseText;
                        document.getElementById(field).style.border="1px solid #A5ACB2";
                }
        }
        xmlhttp.open("GET","match_search_listusers.php?q="+username+"&id="+id,true);
        xmlhttp.send();
}


$('#add_player').click(function() {
        alert('Handler for .click() called.');
});

// Hide show advanced options things
$('#show_hide').click(function() {
        $('#advanced_options').toggle('fast', function() {
                if ($('#advanced_options').is(':visible')) {
                        $('#matchid_field').val('');
                        $('#matchid').toggle('fast', function() {});
                        
                } else {
                        $('#matchid_field').val('');
                        $('#matchid').toggle('fast', function() {});
                }
        });
});




