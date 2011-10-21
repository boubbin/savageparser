<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$var = $argv[1];
$pax = 0;
foreach (unserialize($var) as $server) {
	$players = $server['num_conn'];
	if ($players == 0) { continue; }
        $pax = $pax + $players;
}
echo $pax;
?>
