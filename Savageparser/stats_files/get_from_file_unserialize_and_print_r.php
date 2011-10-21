<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$file = $_GET['file'];
$str = file_get_contents("bu_files/$file");
$str = unserialize($str);
$str['player_stats'] = unserialize(stripslashes($str[player_stats]));
$str['commander_stats'] = unserialize(stripslashes($str['commander_stats']));
$str['team'] = unserialize(stripslashes($str['team']));
//$str['date'] = unserialize(stripslashes($str['date']));
print_r($str);
?>
