<?php
	// Require the php setup file
	require('phpsetup.php');
        
        /*
         * If we get sent here by another page, it may choose to send a url.
         * In this case, we set that as the url to go to after logging in. 
         * Sometimes it won't be sent by POST (as thought later on), there are
         * cases when it will be sent by GET.
         */
        
        if(isset($_GET['url'])){
            $url = $_GET['url'];
        }else{
            $url = "index.php";
        }
        
        
        /*
         * If already logged in, go to index page. Why did you even come here?
         * 
         * This is another case of when it's unlikely to happen, but it's
         * just nice user experience. OF course, if (in the incredibly unlikely
         * case that) we sent a url already, it'll go the that one.
         */
        if($loggedIn){
            header('Location: ' . $url);
        }
        
        /*
         * Sets the log error to the default. This will be showed if no 
         * data is sent. It will be modified later on if there is another 
         * reason that the user couldn't be logged in.
         * 
         * We also allow sending an error to the page so we can automatically 
         * say something from another page. For example, if we need to log in to
         * create a timetable, it'll send you here.
         */
        if(isset($_GET['err'])){
            $logerror = $_GET['err'];
        }else{
            $logerror = "Please log in.";
        }
        
        /*
         * If we have another url set from the login page (basically if we have
         * come from another page and logged in, then set that url. Otherwise
         * use the default of index.php.
         */
        if(isset($_POST['url'])){
            $url = $_POST['url'];
        }else{
            $url = "index.php";
        }
        $smarty->assign('url',$url);
        
        
        //If the user details were submitted
        if(isset($_POST['username'])){
            $username = $_POST['username'];
            /*
             * Check that the user actually exists. Although we don't actually
             * do anything different whether it is a correct user or not, it
             * makes it mildly more efficient. Probably pointless, but I'll
             * keep it in. It's not less efficient.
             */
            $user = $database->getUserDetails($username);
            if($user == false){
                $logerror = "No user.";
            }else{
                $password = $_POST['password'];
                $name = $user[0];
                if($database->checkCorrectDetails($username,$password,$name)){
                    /*
                     * The user exists, and the password was correct. Now
                     * we can set the session variables and go back to whatever 
                     * page we came from before (or the index page).
                     */
                    $_SESSION['username'] = $username;
                    $_SESSION['name'] = $name;
                    $_SESSION['password'] = $password;
                    header("Location: " . $url);
                }else{
                    $logerror = "Incorrect username or password.";
                }
            }
        }
        
        /*
         * Obviously, this will only happen if the user didn't log in. 
         * It takes the error message and shoves it above the login form.
         * 
         * This is also useful for degredation. If the user doesn't have
         * javascript, when they click on the login link, they will come 
         * straight to this page and be presented with the form and "please
         * log in".
         */
        if(isset($logerror)){
            $smarty->assign('logerror',$logerror);
        }
        $smarty->display('login.tpl');
?>