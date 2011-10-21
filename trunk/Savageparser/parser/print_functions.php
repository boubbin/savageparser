<?php

function print_commander_stats_table($stats) {
	echo "
	<table border=1>
	<tr>
		<td width=70>Exp</td>
		<td width=70>Orders</td>
		<td width=70>Gold</td>
		<td width=70>Erected</td>
		<td width=70>Repaired</td>
		<td width=70>Razed</td>
		<td width=70>Buffs</td>
		<td width=70>Healed</td>
		<td width=70>Debuffs</td>
		<td width=70>Damage</td>
		<td width=70>Kills</td>
		<td width=70>Time</td>
	</tr>
	<tr>
		<td width=70 align=right>
		".
		implode("</td><td width=70 align=right>", $stats)
		."
		</td>
	</tr>
	</table>
";
}

function print_player_stats_table($stats) {
	echo "
	<table border=1>
	<tr>
		<td width=70>Exp</td>
		<td width=70>Damage</td>
		<td width=70>Kills</td>
		<td width=70>Assists</td>
		<td width=70>Souls</td>
		<td width=70>NPC kills</td>
		<td width=70>Healed</td>
		<td width=70>Res</td>
		<td width=70>Gold</td>
		<td width=70>Repaired</td>
		<td width=70>BD</td>
		<td width=70>Razed</td>
		<td width=70>Deaths</td>
		<td width=70>KD ratio</td>
		<td width=70>Time</td>
		<td width=70>SF</td>

	</tr>
	<tr>
		<td width=70 align=right>
		".
		implode("</td><td width=70 align=right>", $stats)
		."
		</td>
	</tr>
	<table>
";
}




?>