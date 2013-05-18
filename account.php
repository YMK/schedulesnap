<?php
	// Require the php setup file
	require('phpsetup.php');
        // Set the url. Pretty obvious.
        $url = "myaccount.php";
        $smarty->assign('url',$url);
        $error = "Change your details:";
        
        $userclass = "normal";
        $nameclass = "normal";
        $cpclass = "normal";
        $npclass = "normal";
        $cp2class = "normal";
        
        $details = $database->getUserDetails($user);
        
        $realname = $details[0];
        $uname = $details[1];
        
        
        
        /*
         * First we check to see if something was posted from the form on the
         * page. Currently, can't think of an easier way to do this than
         * just checking if each submit button is set. The thing is, I'll need
         * to check that at some point anyway, so may as well do it here.
         */
        
        if(isset($_POST['changeDetails'])){
            /*
             * 
             */
            $username = $_POST['username'];
            $name = $_POST['name'];
            
            if($_POST['currentPword'] != " "
                    && $database->checkCorrectDetails($user,
                            $_POST['currentPword'], $realname)){
                
                $oldpass = $_POST['currentPword'];
                    if($_POST['newPword'] != ""){
                        $pass = $_POST['newPword'];
                        $npclass = "correct";
                        $error = "Password changed";
                    }else{
                        $pass = $oldpass;
                    }
                $database->changeUsersName($user,$name,"mong00s31");
                if($name != $realname){
                    $nameclass = "correct";
                    $realname = $name;
                }
                if($username != $user){
                    if(!$database->changeUserName($user,$username,$name,$pass)){
                        $error = "That username is already taken.";
                        $userclass = "incorrect";
                    }else{
                        $_SESSION['username'] = $username;
                        $user = $username;
                        $uname = $user;
                        $userclass = "correct";
                        if($error == "Password changed. "){
                            $error = $error + " and user name changed.";
                        }else{
                            $error = "Username changed.";
                        }
                    }
                }
            }else{
                $error = "Please enter your current password.";
                $cpclass = "incorrect";
                $uname = $username;
                $realname = $name;
            }
        }else if(isset($_POST['delete'])){
            /*
             * This will only be pressed if they want to delete their account.
             * 
             * The question is do we want to ask them if they want to delete
             * their account before doing it, or do we just assume they wouldn't
             * type their password in and click the button if they didn't?
             * 
             * For just now, I'll assume.
             */
            
            if(trim($_POST['currentPword2']) != "" && $database->checkCorrectDetails($user,
                            $_POST['currentPword2'], $realname)){
                $database->deleteUser($user, $_POST['currentPword2'], $realname);
                session_destroy();
                header("Location: index.php");
            }else{
                $error = "Please enter your current password to delete account.";
                $cp2class = "incorrect";
            }
            
        }
        
        /*
         * We still do this next bit all the time. It might be an idea
         * to add in something that gets printed to the screen if we have 
         * changed something, but currently we still just show everything 
         * normally as if they just came to the page
         */
        
        $smarty->assign('userclass',$userclass);
        $smarty->assign('nameclass',$nameclass);
        $smarty->assign('cpclass',$cpclass);
        $smarty->assign('npclass',$npclass);
        $smarty->assign('cp2class',$cp2class);
        
        $smarty->assign('name',$realname);
        $smarty->assign('username',$uname);
        $smarty->assign('error',$error);
        if(!$loggedIn){
            header('Location: index.php');
        }
	$smarty->display('account.tpl');
?>