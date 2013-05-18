<?php
        /**
         * This is the page for viewing rotas.
         * 
         * More information...
         */


	// Require the php setup file
	require('phpsetup.php');
        // Base url, this will get changed when we have the rota id
        $url = "viewRota.php";
        
        // Get the rota id, or set -1 if there is none
        if(isset($_GET['rota'])){
            $rotaid = $_GET['rota'];
        }else{
            $rotaid = -1;
        }
        $smarty->assign('rotaid',$rotaid);
        
        $query = "SELECT rotaname FROM rota
                    WHERE rotaid=$rotaid";
        $result = $database->sql($query);
        $row = $result->fetch_row();
        $rotaname = $row[0];
        $smarty->assign('rotaname',$rotaname);
        
        
        // Record the url for logging in
        $url = $url . "?rota=$rotaid";

        // Check if the rota id is correct.
        if(!$database->isValidRota($rotaid)){
            /* 
             * If the rota id is incorrect, just say so
             * Frankly, the user shouldn't ever be coming
             * to this page, so it doesn't matter if it looks nice, or
             * has much information.
             */
            $smarty->assign('validrota','false');
            $smarty->assign('error','Not valid rota id');
        }else{
            /*
             * If the rota id is correct. We need to get all the information
             * and pass it through to the template.
             */
            
            // Get the information from the database
            $array = $database->getTableArray($rotaid);
            if($array == false){
                /*
                 * Technically it should never reach this point. When the system
                 * creates a rota, it adds in at least 1 block and 1 strip, so
                 * it should never be empty. But I just can't leave this option
                 * not accounted for. So, just throw a weird error. The user 
                 * will never get to this point unless they delete everything 
                 * I guess...
                 * 
                 * Maybe I should add something in to allow for having deleted
                 * all strips/blocks. TODO!!!
                 */
                
                $smarty->assign('validrota','false');
                $smarty->assign('error','No valid information strips/blocks');
                
            }else{
                /*
                 * This is what we do when we have a correct rota 
                 * with information in. Basically, just passes the 2d array
                 * through to the template. It does the rest.
                 */
                
                $smarty->assign('validrota', 'true');
                $smarty->assign('table', $array); 
                $smarty->assign('tablearray', json_encode($array)); 
            
                /*
                 * This sets up the javascript arrays for the blocks/strips, so
                 * that my javascript know what ones it is dealing with.
                 */
                $blocks = $database->getBlockArray($rotaid);
                $strips = $database->getStripArray($rotaid);
                $smarty->assign('strips', json_encode($strips));
                $smarty->assign('blocks', json_encode($blocks));

                
                /*
                 * Check if the current user is the owner of this rota.
                 * We only allow editing of the rota if he is. Otherwise, they
                 * just get to view it.
                 */
                if($loggedIn){
                    $query = "SELECT r.rotaowner FROM `rota` r
                                WHERE r.rotaowner='$user'
                                AND r.rotaid=$rotaid";
                    $result = $database->sql($query);
                    $smarty->assign('user', $user);
                    if($result == false){
                        $smarty->assign('owner','false');
                    }else{
                        $smarty->assign('owner','true');
        
        
                        /*
                         * This is for the user list. Grab the names of the users, and
                         * add them into an array. It contains the names and the usernames,
                         * so that we can have the actual name showing on the page, but
                         * of course the username is the primary key, so we need that
                         * to chagne anything.
                         * 
                         * Note, it is within this if statement because there is
                         * no point in doing this if the user can't edit.
                         */
                        $users = $database->getRotaUsers($rotaid);
                        if($users != false){
                            $x = 0;
                            while($test = $users->fetch_row()){
                                /*
                                 * Just changing the data into an easy way for the
                                 * template to deal with it. It doesn't like mysqli
                                 * output for some reason.
                                 */
                                $userlist[$x][0] = $test[0];
                                $userlist[$x++][1] = $test[1];
                            }
                            $smarty->assign('users',$userlist);
                            $smarty->assign('userlistjson', json_encode($userlist));
                        }
                    }
                }else{
                    $smarty->assign('owner','false');
                }
            }
        }
        
        
        /*
         * This bit is obvious, however I might change this to have 2
         * different templates. Basically, I'm not convinced about having loads
         * of logic in my template, for example if there is an error, then
         * there is an if statement in the template. I can't get away from
         * some things, like the loop for the table. But I can get away from 
         * having the if statement just by extending the template, and having
         * 1 for correct rota, and 1 for incorrect...
         * 
         * Anyway, this just assigns the url and displays the page. The url
         * is assigned all the way down here incase it needs to change earlier
         * on in the page.
         */
        $smarty->assign('url',$url);
        $smarty->display('viewrota.tpl');
?>
