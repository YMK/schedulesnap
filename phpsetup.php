<?php
        /**
         * This is the setup php file. It is called by anything that wants
         * to deal with the database, or even just show a page in any way.
         * 
         * It allows me to centralise this code, so I don't have to keep it in
         * each of the pages individually.
         */

        // Include the configuration variables (mainly just database stuff)
        require('helpers/config.php');
        session_start();
        ini_set('display_errors', 'On');
        error_reporting(E_ALL);
        
        /*
         * Set up the smarty object, and set up all the variables needed
         * to make it run correctly.
         */
	define('SMARTY_DIR','helpers/Smarty-3.1.13/libs/');
	require_once('helpers/Smarty-3.1.13/libs/Smarty.class.php');
	$smarty = new Smarty();
	$smarty->setTemplateDir('templates/');
	$smarty->setCompileDir('templates/compiled/');
	$smarty->setConfigDir('helpers/configs/');
	$smarty->setCacheDir('helpers/cache/');
        
        /*
         * Create the database object, and pass all the correct information
         */
	require_once('helpers/dataBase.php');
        $database = new dataBase($db_host,$db_user,$db_pass,$db_name);
        $database->connect();
        
        /*
         * This is for dealing with whether a user is logged in etc. These
         * variables are useful for each of the pages dealing with stuff
         * seperately.
         */
        if(isset($_SESSION['username'])){
            $loggedIn = true;
            $user = $_SESSION['username'];
        }else{
            $loggedIn = false;
        }
        
        /*
         * Set up the login/account link. It either shows the 
         */
        if($loggedIn){
            $smarty->assign('myaccount', 
                    '<noscript><a href="account.php"  class="none"></noscript>
                        My Account<noscript></a></noscript>');
            $smarty->assign('loggedin', 'true');
        }else{
            $smarty->assign('myaccount', 
                    '<noscript><a href="login.php"  class="none"></noscript>
                        Log in/Register<noscript></a></noscript>');
            $smarty->assign('loggedin', 'false');
        }
        
?>
