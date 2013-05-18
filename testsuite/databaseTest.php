<?php

    require_once('simpletest/autorun.php');
    require_once('../helpers/dataBase.php');
    require_once('../helpers/config.php');
    
    class TestUsers extends UnitTestCase {
        private $db;
        private $host, $user, $pass, $name;
        private $tuser, $tpass, $tname;
        
        function __construct() {
	    echo "Setting up";
            parent::__construct('User test');
            global $db_host, $db_user, $db_pass, $db_name;
            $this->host = $db_host;
            $this->user = $db_user;
            $this->pass = $db_pass;
            $this->name = $db_name;
            $this->db = new dataBase($this->host,$this->user, $this->pass, $this->name);
        
            $this->db->connect();
            
            $this->tuser = "DatabaseTest1234";
            $this->tpass = "Test";
            $this->tname = "Test";
        }
        
        
        function testAddingUsers(){
	    echo "Testing adding users";
            if(!$this->db->checkCorrectDetails(
                    $this->tuser, $this->tpass, $this->tname)){
                $this->db->deleteUser(
                        $this->tuser, $this->tpass, $this->tname);
            }
            $this->db->addUser($this->tuser,$this->tpass,$this->tname);
            $this->assertTrue($this->db->checkCorrectDetails(
                    $this->tuser, $this->tpass, $this->tname));
            $this->db->deleteUser($this->tuser, $this->tpass, $this->tname);
        }
        
        function testDeletingUsers(){
	    echo "Testing deleting users";
            $this->db->addUser($this->tuser, $this->tpass, $this->tname);
            $this->assertNotEqual($this->db->deleteUser(
                    $this->tuser, $this->tpass, $this->tname), false);
            $this->db->deleteUser($this->tuser, $this->tpass, $this->tname);
        }
        
        function testChangeUserName(){
	    echo "Testing changing a user's username";
            $this->db->addUser($this->tuser, $this->tpass, $this->tname);
            $this->db->changeUserName(
                    $this->tuser, "Test", $this->tname, $this->tpass);
            $this->assertTrue($this->db->checkCorrectDetails(
                    "Test", $this->tpass, $this->tname));
            $temp = $this->db->getUserDetails("Test");
            $this->assertTrue($temp[0] == $this->tname);
            $this->assertTrue($temp[1] == "Test");
            $this->db->deleteUser("Test", $this->tpass, $this->tname);
        }
        
        function testChangeUsersName(){
	    echo "Testing changing a user's name";
            $this->db->addUser($this->tuser, $this->tpass, $this->tname);
            $this->db->changeUserName(
                    $this->tname, "Test", $this->tuser, $this->tpass);
            $this->assertTrue($this->db->checkCorrectDetails(
                    $this->tuser, $this->tpass, "Test"));
            $temp = $this->db->getUserDetails($this->tuser);
            $this->assertTrue($temp[0] == "Test");
            $this->assertTrue($temp[1] == $this->tuser);
            $this->db->deleteUser("Test", $this->tpass, $this->tname);
        }
        
        
        
        function testGetUserDetails(){
	    echo "Testing getting the user details";
            $this->db->addUser($this->tuser, $this->tpass, $this->tname);
            $temp = $this->db->getUserDetails($this->tuser);
            $this->assertTrue($temp[0] == $this->tname);
            $this->assertTrue($temp[1] == $this->tuser);
        }
    }
    
    
    
    
    class TestRotas extends UnitTestCase {
        private $db;
        private $host, $user, $pass, $name;
        private $tuser, $tpass, $tname;
        
        function __construct() {
	    echo "Setting up rota testing";
            parent::__construct('User test');
            global $db_host, $db_user, $db_pass, $db_name;
            $this->host = $db_host;
            $this->user = $db_user;
            $this->pass = $db_pass;
            $this->name = $db_name;
            $this->db = new dataBase($this->host,$this->user, $this->pass, $this->name);
        
            $this->db->connect();
            
            $this->rowner = "YaManicKill";
            $this->rtype = "";
        }
        
        
        function testCreateDeleteRota(){
	    echo "Testing creating and deleting a rota";
            $rotaid = $this->db->createRota($this->rowner, $this->rtype);
            $this->assertNotEqual($rotaid, 0);
            $this->assertNotEqual($rotaid, false);
            $this->assertTrue($this->db->isValidRota($rotaid));
            $this->db->deleteRota($rotaid);
            $this->assertFalse($this->db->isValidRota($rotaid));
        }
        
        function testValidRota(){
	    echo "Testing checks to see if rota is valid";
            $this->assertFalse($this->db->isValidRota(-1));
            $this->assertFalse($this->db->isValidRota(-1000));
            $this->assertFalse($this->db->isValidRota(0));
            $this->assertFalse($this->db->isValidRota(10000));
            $rotaid = $this->db->createRota($this->rowner, $this->rtype);
            $this->assertNotEqual($rotaid, 0);
            $this->assertNotEqual($rotaid, false);
            $this->assertTrue($this->db->isValidRota($rotaid));
            $this->db->deleteRota($rotaid);
        }
        
        function testGetRotaDetails(){
	    echo "Testing getting the rota details";
            $rotaid = $this->db->createRota($this->rowner, $this->rtype);
            $this->assertNotEqual($rotaid, 0);
            $this->assertNotEqual($rotaid, false);
            
            $rota = $this->db->getRotaDetails($rotaid);
            $this->assertEqual($rota[1], $this->rowner);
            $this->db->deleteRota($rotaid);
        }
        
        function testChangeRotaName(){
	    echo "Testing changing the rota name";
            $rotaid = $this->db->createRota($this->rowner, $this->rtype);
            $this->assertNotEqual($rotaid, 0);
            $this->assertNotEqual($rotaid, false);
            
            $this->db->changeRotaName($rotaid, "Random test");
            $rota = $this->db->getRotaDetails($rotaid);
            $this->assertEqual($rota[0], "Random test");
            $this->db->deleteRota($rotaid);
            
        }
        
        
        function testRotaUsers(){
	    echo "Testing the user's owner";
            $rotaid = $this->db->createRota($this->rowner, $this->rtype);
            $this->assertNotEqual($rotaid, 0);
            $this->assertNotEqual($rotaid, false);
            
            $users = $this->db->getRotaUsers($rotaid);
            $test = $users->fetch_row();
            $this->assertEqual($test[1], $this->rowner);
            
            
            $this->db->deleteRota($rotaid);
        }
        
    }
?>
