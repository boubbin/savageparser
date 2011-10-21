<form method="POST">
        <input type="text" name="kakka"><br>
        <input type="checkbox" name="penis"><br>
        <input type="text" name="nimi"><br>
        <input type="checkbox" name="checkboxi"><br>
        <input type="text" name="paskaa"><br>
        <input type="checkbox name="kikkare"><br>
        <input type="submit">
</form>

<?php
        if (isset($_POST)) {
                echo serialize($_POST);
        }
 ?>


ALTS FOR IP:
SELECT INET_NTOA(sub.ip) as IP, players.playername, sub.playerid FROM players, (SELECT DISTINCT(playerid) as playerid, ip FROM stats WHERE ip = givenip UNION SELECT DISTINCT(playerid) as playerid, ip FROM commanderstats WHERE ip = givenip) as sub WHERE players.playerid = sub.playerid;

ALTS FOR playerid:
SELECT INET_NTOA(sub.ip) as IP, players.playername, sub.playerid FROM players, (SELECT playerid, DISTINCT(ip) FROM stats WHERE playerid = '' UNION SELECT playerid, DISTINCT(ip) FROM commanderstats WHERE playerid = '') as sub WHERE players.playerid = sub.playerid;







