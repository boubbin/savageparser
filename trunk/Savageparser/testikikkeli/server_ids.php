<?php
        require_once('/home/boubbino/public_html/savage/parser/mysql_functions.php');
        var_dump(unserialize('a:30:{i:5;a:10:{s:2:"id";s:1:"5";s:4:"port";s:5:"11235";s:2:"ip";s:13:"174.36.224.91";s:8:"max_conn";s:2:"26";s:8:"num_conn";s:1:"0";s:4:"name";s:11:"yUS-EAST 1";s:11:"description";s:56:"Hosted by S2games - Support them in buying prime and HoN";s:8:"minlevel";s:1:"0";s:8:"maxlevel";s:10:"2147483647";s:8:"official";s:1:"1";}i:6;a:10:{s:2:"id";s:1:"6";s:4:"port";s:5:"17640";s:2:"ip";s:14:"173.192.17.146";s:8:"max_conn";s:2:"34";s:8:"num_conn";s:1:"0";s:4:"name";s:23:"gn00bstories.com Newbs";s:11:"description";s:23:"gn00bstories.com Newbs";s:8:"minlevel";s:1:"0";s:8:"maxlevel";s:2:"20";s:8:"official";s:1:"1";}i:10;a:10:{s:2:"id";s:2:"10";s:4:"port";s:5:"11245";s:2:"ip";s:13:"193.33.186.10";s:8:"max_conn";s:2:"30";s:8:"num_conn";s:1:"0";s:4:"name";s:12:"gUK Customs";s:11:"description";s:56:"Hosted by S2games - support them in buying prime and HoN";s:8:"minlevel";s:1:"5";s:8:"maxlevel";s:10:"2147483647";s:8:"official";s:1:"1";}i:13;a:10:{s:2:"id";s:2:"13";s:4:"port";s:5:"11236";s:2:"ip";s:13:"174.36.224.91";s:8:"max_conn";s:2:"26";s:8:"num_conn";s:1:"0";s:4:"name";s:16:"gUS EAST Custom";s:11:"description";s:56:"Hosted by S2games - support them in buying prime and HoN";s:8:"minlevel";s:1:"0";s:8:"maxlevel";s:10:"2147483647";s:8:"official";s:1:"1";}i:15;a:10:{s:2:"id";s:2:"15";s:4:"port";s:5:"13235";s:2:"ip";s:13:"174.36.178.88";s:8:"max_conn";s:2:"30";s:8:"num_conn";s:1:"0";s:4:"name";s:11:"yUS West 1";s:11:"description";s:56:"Hosted by S2games - support them in buying prime and HoN";s:8:"minlevel";s:1:"0";s:8:"maxlevel";s:10:"2147483647";s:8:"official";s:1:"1";}i:16;a:10:{s:2:"id";s:2:"16";s:4:"port";s:5:"11241";s:2:"ip";s:13:"174.36.178.88";s:8:"max_conn";s:2:"30";s:8:"num_conn";s:1:"0";s:4:"name";s:17:"gUS WEST Customs";s:11:"description";s:15:"Savage 2 Server";s:8:"minlevel";s:1:"5";s:8:"maxlevel";s:10:"2147483647";s:8:"official";s:1:"1";}i:21;a:10:{s:2:"id";s:2:"21";s:4:"port";s:5:"11265";s:2:"ip";s:13:"193.33.186.10";s:8:"max_conn";s:2:"30";s:8:"num_conn";s:2:"23";s:4:"name";s:6:"yUK 4";s:11:"description";s:15:"Savage 2 Server";s:8:"minlevel";s:1:"0";s:8:"maxlevel";s:10:"2147483647";s:8:"official";s:1:"1";}i:36;a:10:{s:2:"id";s:2:"36";s:4:"port";s:5:"11238";s:2:"ip";s:13:"174.36.224.91";s:8:"max_conn";s:2:"30";s:8:"num_conn";s:1:"0";s:4:"name";s:11:"yUS-EAST 4";s:11:"description";s:56:"Hosted by S2games - support them in buying prime and HoN";s:8:"minlevel";s:1:"0";s:8:"maxlevel";s:10:"2147483647";s:8:"official";s:1:"1";}i:51;a:10:{s:2:"id";s:2:"51";s:4:"port";s:5:"11235";s:2:"ip";s:12:"41.76.105.14";s:8:"max_conn";s:2:"26";s:8:"num_conn";s:1:"0";s:4:"name";s:20:"Reaper ZA *official*";s:11:"description";s:15:"Savage 2 Server";s:8:"minlevel";s:1:"0";s:8:"maxlevel";s:10:"2147483647";s:8:"official";s:1:"1";}i:100;a:10:{s:2:"id";s:3:"100";s:4:"port";s:5:"11239";s:2:"ip";s:12:"188.40.92.72";s:8:"max_conn";s:2:"26";s:8:"num_conn";s:1:"0";s:4:"name";s:20:"wPlaySavage2.com #1";s:11:"description";s:17:"bStonys wfirst";s:8:"minlevel";s:1:"0";s:8:"maxlevel";s:10:"2147483647";s:8:"official";s:1:"1";}i:107;a:10:{s:2:"id";s:3:"107";s:4:"port";s:5:"11236";s:2:"ip";s:12:"150.101.0.57";s:8:"max_conn";s:2:"30";s:8:"num_conn";s:1:"1";s:4:"name";s:19:"cInternode Sav2 AU";s:11:"description";s:21:"Hosted by Internode !";s:8:"minlevel";s:1:"0";s:8:"maxlevel";s:10:"2147483647";s:8:"official";s:1:"1";}i:126;a:10:{s:2:"id";s:3:"126";s:4:"port";s:5:"17740";s:2:"ip";s:14:"173.192.17.146";s:8:"max_conn";s:2:"34";s:8:"num_conn";s:1:"0";s:4:"name";s:22:"gn00bstories.com ALL2";s:11:"description";s:22:"gn00bstories.com ALL2";s:8:"minlevel";s:1:"0";s:8:"maxlevel";s:10:"2147483647";s:8:"official";s:1:"1";}i:127;a:10:{s:2:"id";s:3:"127";s:4:"port";s:5:"17840";s:2:"ip";s:14:"173.192.17.146";s:8:"max_conn";s:2:"34";s:8:"num_conn";s:2:"14";s:4:"name";s:21:"gn00bstories.com ALL";s:11:"description";s:21:"gn00bstories.com ALL";s:8:"minlevel";s:1:"0";s:8:"maxlevel";s:10:"2147483647";s:8:"official";s:1:"1";}i:37012;a:10:{s:2:"id";s:5:"37012";s:4:"port";s:1:"0";s:2:"ip";s:13:"69.251.188.17";s:8:"max_conn";s:2:"16";s:8:"num_conn";s:1:"0";s:4:"name";N;s:11:"description";N;s:8:"minlevel";s:1:"0";s:8:"maxlevel";s:1:"0";s:8:"official";s:1:"0";}i:12475;a:10:{s:2:"id";s:5:"12475";s:4:"port";N;s:2:"ip";N;s:8:"max_conn";s:2:"16";s:8:"num_conn";s:1:"0";s:4:"name";N;s:11:"description";N;s:8:"minlevel";s:1:"0";s:8:"maxlevel";s:1:"0";s:8:"official";s:1:"0";}i:26100;a:10:{s:2:"id";s:5:"26100";s:4:"port";N;s:2:"ip";N;s:8:"max_conn";s:2:"16";s:8:"num_conn";s:1:"0";s:4:"name";N;s:11:"description";N;s:8:"minlevel";s:1:"0";s:8:"maxlevel";s:1:"0";s:8:"official";s:1:"1";}i:7;a:10:{s:2:"id";s:1:"7";s:4:"port";s:5:"11235";s:2:"ip";s:13:"193.33.186.10";s:8:"max_conn";s:2:"30";s:8:"num_conn";s:1:"0";s:4:"name";s:6:"yUK 1";s:11:"description";s:56:"Hosted by S2games - support them in buying prime and HoN";s:8:"minlevel";s:1:"0";s:8:"maxlevel";s:10:"2147483647";s:8:"official";s:1:"1";}i:32454;a:10:{s:2:"id";s:5:"32454";s:4:"port";s:5:"30017";s:2:"ip";s:13:"188.40.76.206";s:8:"max_conn";s:2:"22";s:8:"num_conn";s:1:"0";s:4:"name";s:19:"pardus.at Savage II";s:11:"description";s:70:"Sponsored by the free space-themed browser game Pardus (www.pardus.at)";s:8:"minlevel";s:1:"0";s:8:"maxlevel";s:10:"2147483647";s:8:"official";s:1:"0";}i:34761;a:10:{s:2:"id";s:5:"34761";s:4:"port";s:5:"11235";s:2:"ip";s:13:"77.37.147.187";s:8:"max_conn";s:2:"26";s:8:"num_conn";s:1:"0";s:4:"name";s:12:"csavage2.ru";s:11:"description";s:16:"winex.org server";s:8:"minlevel";s:1:"0";s:8:"maxlevel";s:10:"2147483647";s:8:"official";s:1:"0";}i:36103;a:10:{s:2:"id";s:5:"36103";s:4:"port";s:5:"14235";s:2:"ip";s:14:"173.192.17.146";s:8:"max_conn";s:2:"20";s:8:"num_conn";s:1:"3";s:4:"name";s:22:"gn00bstories.com DUEL";s:11:"description";s:36:"yTourney - Last Winner: rCougar114";s:8:"minlevel";s:1:"0";s:8:"maxlevel";s:10:"2147483647";s:8:"official";s:1:"0";}i:36762;a:10:{s:2:"id";s:5:"36762";s:4:"port";s:5:"11239";s:2:"ip";s:13:"174.36.224.91";s:8:"max_conn";s:2:"50";s:8:"num_conn";s:1:"0";s:4:"name";s:19:"wCbOwIL-EbAwST";s:11:"description";s:40:"wCOIL-Warserver for the east of the USA";s:8:"minlevel";s:1:"0";s:8:"maxlevel";s:10:"2147483647";s:8:"official";s:1:"0";}i:36940;a:10:{s:2:"id";s:5:"36940";s:4:"port";s:5:"11235";s:2:"ip";s:12:"72.174.66.85";s:8:"max_conn";s:2:"26";s:8:"num_conn";s:1:"0";s:4:"name";s:11:"Pubstomped!";s:11:"description";s:15:"Savage 2 Server";s:8:"minlevel";s:1:"0";s:8:"maxlevel";s:10:"2147483647";s:8:"official";s:1:"0";}i:37155;a:10:{s:2:"id";s:5:"37155";s:4:"port";s:5:"11236";s:2:"ip";s:12:"41.76.105.14";s:8:"max_conn";s:2:"30";s:8:"num_conn";s:1:"0";s:4:"name";s:14:"Reaper ZA duel";s:11:"description";s:46:"South African Duel server - courtesy of REAPER";s:8:"minlevel";s:1:"0";s:8:"maxlevel";s:10:"2147483647";s:8:"official";s:1:"0";}i:37327;a:10:{s:2:"id";s:5:"37327";s:4:"port";s:1:"0";s:2:"ip";s:13:"174.36.178.88";s:8:"max_conn";s:2:"16";s:8:"num_conn";s:1:"0";s:4:"name";N;s:11:"description";N;s:8:"minlevel";s:1:"0";s:8:"maxlevel";s:1:"0";s:8:"official";s:1:"0";}i:37234;a:10:{s:2:"id";s:5:"37234";s:4:"port";s:5:"11235";s:2:"ip";s:13:"94.23.226.110";s:8:"max_conn";s:2:"20";s:8:"num_conn";s:1:"0";s:4:"name";s:11:"bens server";s:11:"description";s:11:"bens Server";s:8:"minlevel";s:1:"0";s:8:"maxlevel";s:10:"2147483647";s:8:"official";s:1:"0";}i:37785;a:10:{s:2:"id";s:5:"37785";s:4:"port";s:5:"11238";s:2:"ip";s:12:"188.40.92.72";s:8:"max_conn";s:2:"30";s:8:"num_conn";s:1:"0";s:4:"name";s:21:"rS2uesday yEVENT(r)";s:11:"description";s:15:"Savage 2 Server";s:8:"minlevel";s:1:"0";s:8:"maxlevel";s:10:"2147483647";s:8:"official";s:1:"0";}i:37688;a:10:{s:2:"id";s:5:"37688";s:4:"port";s:1:"0";s:2:"ip";s:12:"188.40.92.72";s:8:"max_conn";s:2:"16";s:8:"num_conn";s:1:"0";s:4:"name";N;s:11:"description";N;s:8:"minlevel";s:1:"0";s:8:"maxlevel";s:1:"0";s:8:"official";s:1:"0";}i:37812;a:10:{s:2:"id";s:5:"37812";s:4:"port";s:5:"11238";s:2:"ip";s:13:"174.36.178.88";s:8:"max_conn";s:2:"30";s:8:"num_conn";s:1:"0";s:4:"name";s:21:"rS2uesday yEVENT(r)";s:11:"description";s:15:"Savage 2 Server";s:8:"minlevel";s:1:"0";s:8:"maxlevel";s:10:"2147483647";s:8:"official";s:1:"0";}i:37901;a:10:{s:2:"id";s:5:"37901";s:4:"port";s:5:"11235";s:2:"ip";s:14:"201.67.130.179";s:8:"max_conn";s:2:"20";s:8:"num_conn";s:1:"0";s:4:"name";s:17:"gBrazucas Server";s:11:"description";s:21:"gServidor Brasileiro";s:8:"minlevel";s:1:"0";s:8:"maxlevel";s:10:"2147483647";s:8:"official";s:1:"0";}i:37899;a:10:{s:2:"id";s:5:"37899";s:4:"port";s:5:"11235";s:2:"ip";s:12:"150.101.0.57";s:8:"max_conn";s:2:"24";s:8:"num_conn";s:1:"0";s:4:"name";s:16:"cInternode DUEL";s:11:"description";s:17:"Hosted by s2games";s:8:"minlevel";s:1:"0";s:8:"maxlevel";s:10:"2147483647";s:8:"official";s:1:"0";}}'));
        var_dump(unserialize($g));
        foreach (array() as $server) {
                /*
                [id] => 5
                [port] => 11235
                [ip] => 174.36.224.91
                [max_conn] => 26
                [num_conn] => 0
                [name] => US-EAST 1
                [description] => Hosted by S2games - Support them in buying prime and HoN
                [minlevel] => 0
                [maxlevel] => 2147483647
                [official] => 1
                 *
                 */
                $id   = $server['id'];
                $name = preg_replace('/\./i', "", $server['name']);
                $ip   = $server['ip'];
                $desc = preg_replace('/\./i', "", $server['description']);
                if ($server['official']==1) { add_server($id, $name, $ip, $desc); }
        }
?>
