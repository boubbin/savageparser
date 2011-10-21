<?php


function echo_mainpage() {
	echo '
        <div id=mainpage>
        <h1><span>29.9.2011 - Invalid stats fixed</font></span></h1>
        Sometime ago one of the game servers decided to send me a 500 matches all of sudden.<br>
        Since game-servers do not timestamp the files I need to timestamp them when i receive them. Basicly this means that all those 500 matches were played in 5seconds :)<br>
        For some reason most of the matches holded matchid that was placed in the future(?), like 2 weeks<br>
        So when those matches that really occured after 2 weeks (26. 27. 28. of this month) were already in the database and thats why they were omitted!<br>
        I removed all those fucked up 500 matches and replaced them with the REAL ones.<br>
        This means that matches from 2weeks ago might be missing or bugged somehow, but the most recent ones are 100% valid<br>
        There is still little bit fuss going on but it wil be sorted out when time passes<br>
        Im truly sorry about this.. you can only imagine how long it took to realize WHAT THE FUCK had happened<br><br>
        Event stats are temporary not in use until i find time to fix them, they took way too long to load atm!<br>
        <h1><span>21.8.2011 - Event based stats (experimental)</font></span></h1>
        Service has been receiving event based stats for couple days now.<br>
        This means that you can see your stats even more closer, actions you do in matches, which unit do you spawn the most, which unit do you kill the most<br>
        how many malphas have you killed with siege etc etc; events are full of possibilities!<br>
        So far you can see the number of gadgets/buildings destroyed and also which units do you kill the most<br>
        Since we only have events from past couple days, most of the figures arent that accurate, you\'ll have to wait<br>
        for the events to catch up the 30days cycle to be accurate, so dont get scared if it says that you only got 10 portals in 50 matches :)<br>
        If you can\'t see anything on the event based stats on your page, it means that you haven\'t played in 2 days (check <a href=index.php?action=stats&playerid=543762&playername=ohorani&period=30>ohorani</a>s profile for nice view of the events)<br>
        Events will be also part of contests in many ways.<br><br>
        <a href=index.php?action=stats&playerid='.$_SESSION['playerid'].'&playername='.$_SESSION['playername'].'&period=30>Check events at your stats page!</a>
        <br><br>
        <h1><span>11.8.2011 - S2SP for all</font></span></h1>
        Website is finally open for everyone and can be used with Savage2 credentials.<br>
        This website aims to offer as much satisfaction as possible for total stats-junkies<br>
        and those who are addicted to know their real 30days sf. This service is open for everyone<br>
        but we expect that only small number of people will keen on enough to visit daily!<br>
        <i>Ideal user of this service is sf 200+ oldschool skilled farmer</i><br>
        And to remind that this service <b>DOES NOT</b> run any kind of competition with <a href=http://www.playsavage2.com>www.playsavage2.com</a><br>
        PS2.com and S2SP are different kind of products that share similarity but are developed to serve different function.<br>
        <br>
        Currect state of this website is Under Construction. Updates are applied almost daily.<br>
        <br>Components of this website that are worth checking out:<br>
        <li><a href=http://savage.boubbin.org/contest/>Contests with prizes</a></li>
        <li><a href=http://savage.boubbin.org/parser/alt_finder.php>An Alternative Account finder</a></li>
        <li><a href=http://savage.boubbin.org/today.php>How many matches are played daily?</a></li>
        <li><a href=http://savage.boubbin.org/playercounts.php>Players ingame daily</a></li>
        <br><br>
        Stay tuned for moar!
        </div>
	<div id="foot_text"></div>
	';
}
