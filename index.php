<?php
	// Require the php setup file
	require('phpsetup.php');
        // Set the url. Pretty obvious.
        $url = "index.php";
        $smarty->assign('url',$url);
        
        if($loggedIn){
            /**
             * This is what it shows when the user is logged in. Should show a list
             * of all the rotas the user is part of, and their details.
             */
            
            $query = "SELECT r.RotaName,r.rotaid from `rota` r, `user` u, `rotaUser` ru
                            WHERE r.rotaid=ru.rotaid
                            AND ru.username=u.username
                            AND u.username='$user'";
            $result = $database->sql($query);
            
            if($result == false){
                // Say that you don't have any rotas.
            }else{
                $x = 0;
                while($row = $result->fetch_row()){
                    $rotas[$x][0] = $row[0];
                    $rotas[$x++][1] = $row[1];
                }
                if(isset($rotas)){
                    $smarty->assign('rotas',$rotas);
                }
            }
            
        }
	$smarty->display('index.tpl');
?>