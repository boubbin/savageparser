<?php
        require('parser/mysql_functions.php');
        require('print_page_functions.php');
        require('get_functions.php');
	ini_set("display_errors", "1");
	error_reporting(1);
	header('content-type: image/jpeg');

        //  playerid=194827&1=1&3=1&4=1&5=1
        $item = array();
        $first = 0;
        $playerid = $_GET['playerid'];
        if (!is_numeric($playerid) || count($_GET) != 7) { die(); }
        foreach ($_GET as $value => $key) {
                $first++;
                if ($first < 3) { continue; }
                if ($key > 18 || $key < 0) {
                        die();
                } else {
                        $item[$first] = get_corresponding_text_label_for_sigpic_option($key)." ".get_corresponding_sigpic_result_for_option($key, $playerid);
                }
        }
        $playername = playerid_to_playername($_GET['playerid']);
	//Load our base image
        
        if ($_GET['bg']==1) {
                // mun
                $first = 22;
                $step  = 13;
                $image = imagecreatefrompng('images/sigpic/bg1.png');
        } else {
                // savagen defalti
                $first = 15;
                $step  = 16;
                $image = imagecreatefrompng('images/sigpic/bg2.png');
        }
        $image_width = imagesx($image);

	//Setup colors and font file
	$white = imagecolorallocate($image, 255, 255, 255);
        $yellow   = imagecolorallocate($image, 200, 200, 20);
	$black = imagecolorallocate($image, 0, 0, 0);
        $font_name = '/home/boubbino/public_html/savage/fonts/LucidaSansOblique.ttf';
        if ($_GET['bg']==1) {
                $font_path = '/home/boubbino/public_html/savage/fonts/LiberationSans-Regular.ttf';
                imagettftext($image, 10, 0, 40, $first, $yellow, $font_name, $playername);
                imagettftext($image, 9, 0, 50, ($first+$step), $white, $font_path, $item['3']);
                imagettftext($image, 9, 0, 50, ($first+2*$step), $white, $font_path, $item['4']);
                imagettftext($image, 9, 0, 50, ($first+3*$step), $white, $font_path, $item['5']);
                imagettftext($image, 9, 0, 50, ($first+4*$step), $white, $font_path, $item['6']);
                imagettftext($image, 9, 0, 50, ($first+5*$step), $white, $font_path, $item['7']);
        } else {
                $font_path = '/home/boubbino/public_html/savage/fonts/DejaVuSansMono.ttf';
                imagettftext($image, 10, 0, 140, $first, $yellow, $font_name, $playername);
                imagettftext($image, 9, 0, 150, ($first+$step), $white, $font_path, $item['3']);
                imagettftext($image, 9, 0, 150, ($first+2*$step), $white, $font_path, $item['4']);
                imagettftext($image, 9, 0, 150, ($first+3*$step), $white, $font_path, $item['5']);
                imagettftext($image, 9, 0, 10, ($first+4*$step), $white, $font_path, $item['6']);
                imagettftext($image, 9, 0, 10, ($first+5*$step), $white, $font_path, $item['7']);
        }
        imagejpeg($image, '', 100);
	//Clear up memory;
	imagedestroy($image);

?>

