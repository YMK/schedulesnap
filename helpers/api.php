<?php
    chdir('../');
    require('phpsetup.php');
    
    /*
     * Check we have the minimum required info to do an api call.
     * 
     * We firstly need to be logged in, and we need to have an action set.
     * Otherwise, it's not legitimate, and we probably tried coming here
     * directly...
     */
    if(!$loggedIn){
        header('HTTP/1.1 403 Forbidden');
        echo "I'm sorry, you don't have access to whatever you are looking for";
        exit();
    }
    if(!isset($_GET['action'])){
        error404("Its funny, I don't know what you want me to get.");
    }
    
    $action = $_GET['action'];
    
    /*
     * Just a simple switch statement over the action. Really easy to then
     * figure out what it is we need to do.
     */
    switch ($action) {
    case 1:
        /*
         * Adding a user to a rota. We need the username, and a rota id.
         * Then we can add the user to the rota. 
         */
        if(!isset($_GET['user'])){
            error404("You never supplied a user.");
        }elseif(!isset($_GET['rota'])){
            error404("You never supplied a rota.");
        }
        $query = "INSERT INTO rotaUser (username,rotaid)
                    VALUES ('$_GET[user]','$_GET[rota]')";
        echo $query;
        $result = $database->sql($query);
        if(!$result){
            error404("User is already in rota");
        }
        echo $result;
        break;
    case 2:
        /*
         * Searching for a user. Basically, need a 'query', for part of a user's
         * name, which is then searched for. We return the results in a
         * modified array, and json encode it. 
         */
        if(!isset($_POST['query'])){
            error404("You never supplied a search term.");
        }
        $query = "SELECT name, username FROM user 
                    WHERE name LIKE '$_POST[query]%'";
        $result = $database->sql($query);
        
        $test = array();
        while($row = $result->fetch_row()){
            $array = array("value" => $row[0], "data" => $row[1]);
            $test[] = $array;
        }
        $json = array('query' => $_POST['query'], 'suggestions' => $test);
        echo json_encode($json, JSON_FORCE_OBJECT);
        break;
    
    case 3:
        /*
         * Change a specific block entry to the specified user. We need the
         * block and strip ids, as well as the username. Return the name in
         * the body of the page.
         */
        if(!isset($_GET['user'])){
            error404("You never supplied a username");
        }  elseif (!isset($_GET['block'])) {
            error404("You never supplied a block id");
        }  elseif (!isset($_GET['strip'])) {
            error404("You never supplied a strip id");
        }
        $user = $_GET['user'];
        $block = $_GET['block'];
        $strip = $_GET['strip'];
        $result = $database->changeBlockEntry($block, $strip, $user);
        if(!$result){
            error404("Issue");
        }else{
            $result = $database->getUserDetails($user);
            $name = $result[0];
            echo $name;
        }
        break;
            
            
    case 4:
        /*
         * Adds a new block to the specified rota. Need a rotaid.
         */
        if(!isset($_GET['rota'])) {
            error404("You never supplied a rota id");
        }
        $rota = $_GET['rota'];

        $result = $database->addBlock($rota);
        echo $result;
        
        break;
        
    case 5:
        /*
         * Adds a new strip to the specified rota. Needs a rota id.
         */
        if(!isset($_GET['rota'])) {
            error404("You never supplied a rota id");
        }
        $rota = $_GET['rota'];

        $result = $database->addStrip($rota);
        echo $result;
        
        break;
        
    case 6:
        /*
         * Adds a new blockentry for the specified block and strip. Needs a 
         * block and strip id. Sets the user to the default blanks user.
         */
        
        if (!isset($_GET['block'])) {
            error404("You never supplied a block id");
        }  elseif (!isset($_GET['strip'])) {
            error404("You never supplied a strip id");
        }
        $user = $_GET['user'];
        $block = $_GET['block'];
        $strip = $_GET['strip'];

        $result = $database->addBlockEntry($block,$strip);
        echo $result;
        
        break;
        
    case 7:
        /*
         * Deletes the specified block. We need a block id.
         */
        if (!isset($_GET['block'])){
            error404("You never supplied a block id");
        }
        $block = $_GET['block'];
        $result = $database->delBlock($block);
        echo "Success";
        break;
    case 8:
        /*
         * Deletes the specified strip. We need a strip id.
         */
        if (!isset($_GET['strip'])){
            error404("You never supplied a strip id");
        }
        $strip = $_GET['strip'];
        $result = $database->delStrip($strip);
        echo "Success";
        break;
        
    case 9:
        /*
         * Changes the name a strip or block. We need the id of the table cell,
         * which we then modify to get whether it is a strip/block and what the
         * id is. Then we need the name to be changed, and change it.
         */
        if (!isset($_POST['id'])){
                error404("You never supplied an id");
        }elseif (!isset($_POST['value'])) {
                error404("You never supplied a value");
        }
        
        $ids = $_POST['id'];
        $data = $_POST['value'];
        $id = explode(",",$ids);
        
        if($id[0] == 0){
            $result = $database->changeBlockName($id[1],$data);
        }else{
            $result = $database->changeStripName($id[0],$data);
        }
        echo $data;
        break;
        
        
    case 10:
        /*
         * Deletes a rota. Need a rota id.
         */
        if (!isset($_GET['rota'])){
            error404("You did not supply a rota id");
        }
        // Need to check if user is the owner
        
        // Delete
        $rota = $_GET['rota'];
        $result = $database->deleteRota($rota);
        if($result){
            echo "Deleted";
        }else{
            error404("Error of some sort...");
        }
        break;
        
    case 11:
        /*
         * Changes the name of a rota. Needs the rota id and the name to be
         * changed to.
         */
        if (!isset($_POST['id'])){
                error404("You never supplied a rota id");
        }elseif (!isset($_POST['value'])) {
                error404("You never supplied a value");
        }
        
        $data = $_POST['value'];
        $rotaid = $_POST['id'];
        
        $result = $database->changeRotaName($rotaid,$data);
        
        
        echo $data;
        break;
        
    case 12:
        
        if(!isset($_GET['user'])){
            error404("You never supplied a user");
        }elseif(!isset($_GET['rota'])){
            error404("You never supplied a rota id");
        }
        
        // Get all constraints relevant to the rota, and return them json
        // encoded back :-)
        
        $query = "SELECT * FROM `userAttribute` u, `attribute` a WHERE u.username='$_GET[user]'";
        $result = $database->sql($query);
        $response = array();
        $line = $result->fetch_row();
        while($line != null){
            $stuff = explode(",",$line[3]);
            if($stuff[1] == $_GET['rota']){
                $response['user'] = $line[0];
                $response['name'] = $line[1];
                $response['content'] = $line[3];
            }
            
            $line = $result->fetch_row();
        }
        
        if($response == null){
            error404("Test");
        }else{
            header('Content-type: application/json');
            echo json_encode($response);
        }
        
        break;
    
        
    default:
        /*
         * If we have any other action, send a 404 cause we don't know what we
         * need to do. Chances are they came directly to the page. Silly idea.
         */
        error404("Its funny, I don't know what you want me to get.");
    }
    
    
    /**
     * Sends a 404, and prints a specified message.
     * 
     * @param String $errorString - The error string to be printed.
     */
    function error404($errorString){
        header('HTTP/1.1 404 Resource not found');
        echo $errorString;
        exit();
    }
    
    
?>
