/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


function player_search_clearsearch(id) {
        field = "playersearch";
        $('#'+field).toggle('fast', function() {});
}
function player_search_listusers(username, event) {
        if (username.length==0) {
                field = "playersearch_div";
                document.getElementById(field).innerHTML="";
                document.getElementById(field).style.border="0px";
                return;
        }
        xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
                field = "playersearch_div";
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        document.getElementById(field).innerHTML=xmlhttp.responseText;
                        document.getElementById(field).style.border="1px solid #A5ACB2";
                }
        }
        xmlhttp.open("GET","player_search_listusers.php?q="+username,true);
        xmlhttp.send();
}





