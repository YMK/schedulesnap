<?php
	// Require the php setup file
	require('phpsetup.php');
        
        /*
         * Not much to be said. Destroys the session, logs the user out, and
         * returns back to the index.php file.
         */
        if($loggedIn){
            session_destroy();
        }
        header('Location: index.php');
?>