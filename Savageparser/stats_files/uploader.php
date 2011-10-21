<?php
        if (!empty($_POST)) {
                if (isset($_POST['match_id'])) {
                        $_POST[date] = time();
                        $name = "files/$_POST[match_id]";
                        $handle = fopen($name, 'w');
                        fwrite($handle, serialize($_POST));
                        fclose($handle);
                        //echo "Got it, thank you sir";
                        $str = "Saved $name from $_SERVER[REMOTE_ADDR] @ $_POST[date]";
                        shell_exec("echo $str >> /home/boubbino/public_html/savage/stats_files/log");
                } else {
                        foreach ($_POST as $key => $value) {
                                $matchid = str_replace("event", "", $key);
                                break;
                        }
                        if (is_numeric($matchid)) {
                                if ($matchid == 0) {
                                        $str = "Matchid 0 ($matchid) from $_SERVER[REMOTE_ADDR], omitting...";
                                        shell_exec("echo $str >> /home/boubbino/public_html/savage/stats_files/log");
                                        return; 
                                }
                                $cont = "event".$matchid;
                                $name = "files/$matchid.event";
                                $handle = fopen($name, 'w');
                                fwrite($handle, $_POST[$cont]);
                                fclose($handle);
                                $str = "Saved event $name from $_SERVER[REMOTE_ADDR]";
                                shell_exec("echo $str >> /home/boubbino/public_html/savage/stats_files/log");
                        } else {
                                $str = "Not event, not match, matchid: $matchid: ($_SERVER[REMOTE_ADDR])";
                                shell_exec("echo $str >> /home/boubbino/public_html/savage/stats_files/log");
                                foreach ($_POST as $key => $value) {
                                        $str = "Key: $key, value: $value";
                                        shell_exec("echo $str >> /home/boubbino/public_html/savage/stats_files/log");
                                }
                        }
                }
        } else {
                $str = "Visit from $_SERVER[REMOTE_ADDR], not uploaded anything yet";
                shell_exec("echo $str >> /home/boubbino/public_html/savage/stats_files/log");
                echo "Please Sir go away, this place is not for you";
        }

?>
