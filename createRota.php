<?php
	// Require the php setup file
	require('phpsetup.php');
        // Set the url. Pretty obvious.
        $url = "createRota.php";
        $smarty->assign('url',$url);
        
        if(!$loggedIn){
            $err = "Please log in to create rota";
            header("Location: login.php?err=$err&url=$url");
        }
        
        if(isset($_GET['type'])){
            $type = $_GET['type'];
            
            // Create rota and go to the rota page
            $rotaid = $database->createRota($user,$type);
            header("Location: viewRota.php?rota=".$rotaid);
            
        }
        
	$smarty->display('createRota.tpl');
?>