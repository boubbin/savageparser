<?php

function get_playcount($post) {
	$headers = array(
                'POST /irc_updater/svr_request_pub.php HTTP/1.1',
                'Host: masterserver.savage2.s2games.com',
                'User-Agent: PHP Script',
                'Content-Type: application/x-www-form-urlencoded',
                'Content-Length: 12',
                'Connection: close',
	);
	$fields_string = '';
        foreach($post as $key => $value) {
                $fields_string .= $key.'='.$value.'&';
        }
        rtrim($fields_string,'&');
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, "f=get_online");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl, CURLOPT_URL, 'http://masterserver.savage2.s2games.com/irc_updater/svr_request_pub.php');
	$html = unserialize(curl_exec($curl));
	curl_close($curl);
        echo "<table border=1>";
        $total = 0;
        $servers = array();
        print_r($html);
	foreach ($html as $server) {
                if ($server['num_conn']<=0) { continue; }
                $total += $server['num_conn'];
                $servers[$server['name']] = $server['num_conn'];
        }
        asort($servers, SORT_NUMERIC);
        print_r($servers);
        $servers = array_reverse($servers);
        print_r($servers);
        echo "$total";
}
get_playcount(array("f" => "get_online"));
?>
