<?php
	// Require the php setup file
	require('phpsetup.php');
        /*
         * This variable works very similar to the one in login. Basically,
         * if the user came straight to this page, it will show this above the 
         * form. If there is a specific reason the user failed to register,
         * this will be replaced by something else.
         */
        $logerror = 'Fill in details to register';
        
        /*
         * If the user is already logged in, go straight to index.php, do not
         * pass go, do not collect £200. I have no idea why someone would
         * directly come to this page when logged in, but people have done
         * weirder things with websites...
         */
        if($loggedIn){
            header('Location: index.php');
        }
        
        
        if(isset($_POST['username']) && isset($_POST['password'])
                && isset($_POST['name'])){
            /*
             * Ok, so the user submitted a username, password and full name.
             * Fine, we can check that user doesn't already exist, and then add
             * the user into the database.
             */
            $username = $_POST['username'];
            $user = $database->getUserDetails($username);
            if($user != false){
                $logerror = "User already exists.";
            }else{
                $password = $_POST['password'];
                $name = $_POST['name'];
                
                if($database->addUser($username,$password,$name)){
                    header("Location: login.php?err=Successfully registered,
                         please log in to continue");
                }else{
                    $logerror = "Some error...";
                }
            }
        }
        
        /*
         * If, for some reason, the user didn't correctly register, we can
         * show the register page, and give them another chance to do such a 
         * simple task.
         */
        if(isset($logerror)){
            $smarty->assign('logerror',$logerror);
        }
        $smarty->display('register.tpl');
?>