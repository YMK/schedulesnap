<?php

interface iDatabase {
    
    
    public function connect();
    
    public function disconnect();
    
    public function sql($query);
    
    public function isValidRota($rotaid);
    
    public function getRotaType($rotaid);
    
    public function createRota($owner, $type);
    
    public function deleteRota($rotaid);
    
    public function changeRotaName($rotaid, $name);
    
    public function getRotaStrips($rotaid);
    
    public function getRotaBlocks($rotaid);
    
    public function getStripArray($rotaid);
    
    public function getBlockArray($rotaid);
    
    public function addBlock($rotaid, $name = "New block");
    
    public function delBlock($blockid);
    
    public function delStrip($stripid);
    
    public function addStrip($rotaid, $name = "New strip");
    
    public function addBlockEntry($blockid,$stripid);
    
    public function changeStripName($id, $name);
    
    public function changeBlockName($id, $name);
    
    public function getBlockEntries($blockids,$stripids);
    
    public function getTableArray($rotaid);
    
    public function getRotaUsers($rotaid);
    
    public function changeBlockEntry($block, $strip, $user);
    
    public function getRotaDetails($rotaid);
    
    public function checkCorrectDetails($user, $pass, $uname);
    
    public function hashPass($user, $pass, $uname);
    
    public function addUser($user, $pass, $name);
    
    public function getUserDetails($username);
    
    public function changeUserName($old, $new, $name, $pass);
    
    public function changeUsersName($username, $name, $pass);
    
    public function deleteUser($username, $pass, $name);
    
    
}

?>
