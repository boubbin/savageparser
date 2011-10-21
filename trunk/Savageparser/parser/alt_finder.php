<?php

	require('index.php');


function echo_form() {
		echo "<html><head><title>ALT finder</title></head>";
		echo "<h1>Search for alts v1.0</h1>";
		echo "Pretty basic tool for checking whether 2 players have been playing in the same matches or not, and if so, for how many times<br>
			<font color=grey>(Later on this search will provide more advanced techniques on finding alts...)</font><br><br>";
		echo "<font size=2><form method=POST>Playername1 (or part of it): <input type=text name=playername1><br>";
		echo "Playername2 (or part of it): <input type=text name=playername2><br><input type=submit value=\"Search for playernames\"></form> <br><br>";
		echo "<font color=red size=3>Please notice that you can actually search only for 2 exact names at once!<br>
			If your search terms come up with more than 2 results, you are forced to choose 2 of them.<br>
			Those 2 will be used in the actual search!<br>
			You should realize that this form only helps you to find the best playernames for your search<br>
			For example player boubbin has 3 different accounts:
			<li>boubbin</li><li>boubbin_com</li><li>demo_boubbin</li>
			and you can only use one of them when searching against another playername<br>
			but it is ok to type part of the playername in the those textfields above!<br>
			Think this as \"Phase one: finding good searchterms\"";

}

	
	if (isset($_POST['player1']) && isset($_POST['player2'])) {
		$result = get_duplicate_matches_from_playerids($_POST['player1'], $_POST['player2']);
		$matches = mysql_num_rows($result);
		echo "<h1>Results</h1>";
		$player1 = playerid_to_playername($_POST['player1']);
		$player2 = playerid_to_playername($_POST['player2']);
		$matches1 = get_number_of_matches_from_playerid($_POST['player1']);
		$matches2 = get_number_of_matches_from_playerid($_POST['player2']);
		echo "<b>Went thru ($matches1) matches from $player1 and ($matches2) matches from $player2</b><br>";
		echo "Players <b>$player1</b> and <b>$player2</b> have been playing together on these matches ($matches pcs):";
		while ($row = mysql_fetch_assoc($result)) {
			echo "<li><a href=http://www.savage2replays.com/match_replay.php?mid=$row[matchid]>$row[matchid]</a></li>";
		}
		echo "<br><br><a href=http://savage.juhlamoka.fi/parser/alt_finder>Back to ALT-Finder</a>";
		die();
	} else if (!isset($_POST['playername1']) || !isset($_POST['playername2'])) {
		echo_form();
	} else if (isset($_POST['playername1']) && isset($_POST['playername2'])) {
		// we got 2 playernames
		// get results for them
		
		$len1 = strlen($_POST['playername1']);
		$len2 = strlen($_POST['playername2']);
		if ($len1 < 3 || $len2 < 3) { echo "<font color=red>Nickname(s) too short to search...</font>"; echo_form(); die(); }
		$nicks1 = get_matching_playername_for($_POST['playername1']);
		$nicks2 = get_matching_playername_for($_POST['playername2']);
		$n1 = mysql_num_rows($nicks1);
		$n2 = mysql_num_rows($nicks2);
		if ($n1 == 0) { echo "<font color=red>Your search for playername1 didn't return any results..</font>"; echo_form(); die(); }
		if ($n2 == 0) { echo "<font color=red>Your search for playername1 didn't return any results..</font>"; echo_form(); die(); }
		echo "<h1>Found results for your searhterms:</h1>";
		echo "<h4>Please, choose playername1:</h4>";
		echo "<form method=POST>";
		if ($n1 > 1) {
			while ($row = mysql_fetch_assoc($nicks1)) {
				echo "<li><input type=radio name=player1 value=$row[playerid]>$row[playername]</li>";
			}
		} else {
			$row = mysql_fetch_assoc($nicks1);
			echo "<li><input type=radio name=player1 value=$row[playerid] checked>$row[playername]</li>";
		}
		echo "<h4>Please, choose playername2:</h4>";
		if ($n2 > 1) {
			while ($row = mysql_fetch_assoc($nicks2)) {
				echo "<li><input type=radio name=player2 value=$row[playerid]>$row[playername]</li>";
			}
		} else {
			$row = mysql_fetch_assoc($nicks2);
			echo "<li><input type=radio name=player2 value=$row[playerid] checked>$row[playername]</li>";
		}
		echo "<br><input type=submit value=\"Search for alts\">";
		echo "</form>";
	

}



?>