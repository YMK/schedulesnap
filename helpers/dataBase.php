<?php 
include_once('iDatabase.php');
class dataBase implements iDatabase{
    private $con = false;
    protected $myconn = null;
    private $db_host = "";
    private $db_user = "";
    private $db_pass = "";
    private $db_name = "";
    
    /**
     * 
     * @param type $host
     * @param type $user
     * @param type $pass
     * @param type $name
     */
    function __construct($host,$user,$pass,$name) {
        $this->db_host = $host;
        $this->db_name = $name;
        $this->db_user = $user;
        $this->db_pass = $pass;
    }

    /**
     * 
     * Connects to the database
     * and sets up the connection properly.
     * Returns true if it successfully connects to database, or is already
     * connected.
     * Returns false if not (under various circumstances).
     * 
     * 
     * @return boolean
     */
    public function connect(){
	if(!$this->con){  
            $this->myconn = new mysqli($this->db_host,   
                                        $this->db_user,$this->db_pass,
                                            $this->db_name);  
    
            if($this->myconn->connect_errno){
                return false;
            }
            $this->con = true;
            return true;
        }
        return true;
    }
    
    /**
     * 
     * Disconnects from the database, and cleans up.
     * return true if it disconnects from the database
     * returns false if it doesn't, or didn't need to
     * 
     * @return boolean
     */
    public function disconnect(){
        if($this->con){
            if($this->myconn->close()){
                $this->con = false;
                return true;
            }
            return false;
        }
        return false;
    }
    
    /**
     * 
     * @param type $query
     * @return type
     */
    public function sql($query){
        $result = $this->myconn->query($query);
        if($result != false){
            return $result;
        }
        return false;
    }
    
    
    
    /**
     * Rota related functions
     */
    
    /**
     * 
     * @param type $rotaid
     * @return boolean
     */
    public function isValidRota($rotaid){
        if($rotaid < 0){
            return false;
        }
        $result = $this->myconn->query("SELECT * FROM
            rota WHERE rotaid=" . $rotaid);
        if($result != false && $result->num_rows > 0){
            return true;
        }
        return false;
    }
    
    /**
     * 
     * Simply, returns 'block' if the rota is a block type, or returns
     * 'week' if the rota is week view type, or returns false if the rota
     * doesn't exist.
     * @param type $rotaid
     * @return boolean
     */
    public function getRotaType($rotaid){
        if($rotaid < 0){
            return false;
        }
        $result = $this->myconn->query("SELECT * FROM
            rota WHERE rotaid=" . $rotaid);
        if($result->num_rows > 0){
            $row = $result->fetch_row();
            return $row[2];
        }
        return false;
    }
    
    
    /**
     * 
     * @param type $owner
     * @param type $type
     * @return type
     */
    public function createRota($owner, $type){
        /*
         * 
         */
        $query = "INSERT INTO rota (rotaname, rotaowner, RotaType)
                    VALUES('New Rota', '$owner', 'block')";
        $result = $this->sql($query);
        $result = $this->sql("SELECT LAST_INSERT_ID()");
        $row = $result->fetch_row();
        $rotaid = $row[0];
        $query = "INSERT INTO rotaUser(rotaid,username)
                    VALUES('$rotaid','$owner')";
        $result = $this->sql($query);
        $result = $this->sql("SELECT LAST_INSERT_ID()");
        $row = $result->fetch_row();
        
        /*
         * Now that we have created the rota itself, we need to create the base
         * strip/blocks. This will depend on the type, obviously. And we need
         * to fill in the block entries as well.
         */
        switch ($type) {
            case 1:
                // Custom
                $strips = array();
                for($i = 0; $i<2; $i++){
                    $this->addStrip($rotaid,"Custom");
                    $result = $this->sql("SELECT LAST_INSERT_ID()");
                    $row = $result->fetch_row();
                    $strips[$i] = $row[0];
                }
                
                $blocks = array();
                for($i = 0; $i<2; $i++){
                    $this->addBlock($rotaid,"Custom");
                    $result = $this->sql("SELECT LAST_INSERT_ID()");
                    $row = $result->fetch_row();
                    $blocks[$i] = $row[0];
                }
                
                for($i = 0; $i < 2; $i++){
                    for($j = 0; $j < 2; $j++){
                        $this->addBlockEntry($blocks[$j], $strips[$i]);
                    }
                }
                
                
                
                break;
            
            case 2:
                // 4 items a week
                $strips = array();
                for($i = 0; $i<5; $i++){
                    $this->addStrip($rotaid,"Week " . ($i+1));
                    $result = $this->sql("SELECT LAST_INSERT_ID()");
                    $row = $result->fetch_row();
                    $strips[$i] = $row[0];
                }
                
                $blocks = array();
                for($i = 0; $i<4; $i++){
                    $this->addBlock($rotaid,"Job " . ($i+1));
                    $result = $this->sql("SELECT LAST_INSERT_ID()");
                    $row = $result->fetch_row();
                    $blocks[$i] = $row[0];
                }
                
                for($i = 0; $i < 5; $i++){
                    for($j = 0; $j < 4; $j++){
                        $this->addBlockEntry($blocks[$j], $strips[$i]);
                    }
                }
                break;
            
            case 3:
                // 6 items a day
                $strips = array();
                for($i = 0; $i<5; $i++){
                    $this->addStrip($rotaid, date("jS M", 
                            mktime(0, 0, 0, date("m")  , date("d")+$i, date("Y"))));
                    $result = $this->sql("SELECT LAST_INSERT_ID()");
                    $row = $result->fetch_row();
                    $strips[$i] = $row[0];
                }
                
                $blocks = array();
                for($i = 0; $i<6; $i++){
                    $this->addBlock($rotaid, date("H:i -", 
                            mktime((6)+($i*3), 0, 0, date("m")  , date("d"), date("Y"))) .
                            date(" H:i", 
                            mktime((9)+($i*3), 0, 0, date("m")  , date("d"), date("Y"))));
                    $result = $this->sql("SELECT LAST_INSERT_ID()");
                    $row = $result->fetch_row();
                    $blocks[$i] = $row[0];
                }
                
                for($i = 0; $i < 5; $i++){
                    for($j = 0; $j < 6; $j++){
                        $this->addBlockEntry($blocks[$j], $strips[$i]);
                    }
                }
                break;
            
            case 4:
                // 4 items a week
                $strips = array();
                for($i = 0; $i<5; $i++){
                    $this->addStrip($rotaid, date("jS M", 
                            mktime(0, 0, 0, date("m")  , date("d")+($i*7), date("Y"))));
                    $result = $this->sql("SELECT LAST_INSERT_ID()");
                    $row = $result->fetch_row();
                    $strips[$i] = $row[0];
                }
                
                $blocks = array();
                for($i = 0; $i<5; $i++){
                    $this->addBlock($rotaid,"Job " . ($i+1));
                    $result = $this->sql("SELECT LAST_INSERT_ID()");
                    $row = $result->fetch_row();
                    $blocks[$i] = $row[0];
                }
                
                for($i = 0; $i < 5; $i++){
                    for($j = 0; $j < 5; $j++){
                        $this->addBlockEntry($blocks[$j], $strips[$i]);
                    }
                }
                break;

            default:
                break;
        }
        
        return $rotaid;
    }
    
    
    
    public function deleteRota($rotaid){
        $query = "DELETE from rota WHERE rotaid=$rotaid";
        $result = $this->sql($query);
        $query = "DELETE from rotaUser WHERE rotaid=$rotaid";
        $result = $this->sql($query);
        $query = "DELETE FROM blockEntry
                    WHERE blockid IN(SELECT blockid FROM block
                        WHERE rotaid=$rotaid) 
                    OR stripid IN(SELECT stripid FROM strip
                        WHERE rotaid=$rotaid)";
        $result = $this->sql($query);
        $query = "DELETE FROM block
                    WHERE rotaid=$rotaid";
        $result = $this->sql($query);
        $query = "DELETE FROM strip
                    WHERE rotaid=$rotaid";
        $result = $this->sql($query);
        return $result;
    }
    
    
    public function changeRotaName($rotaid, $name){
        $query = "UPDATE rota SET rotaname='$name'
                    WHERE rotaid=$rotaid";
        $result = $this->sql($query);
        return $result;
    }
    
    
    
    /**
     * 
     * Gets the strips from the rota specified. It returns an array of 
     * just each strip's description
     * 
     * @param type $rotaid
     * @return boolean
     */
    public function getRotaStrips($rotaid){
        if($rotaid < 0){
            return false;
        }
        $result = $this->sql("SELECT s.StripDescrip, s.StripID FROM
            rota r, strip s WHERE r.rotaid=$rotaid AND
                r.rotaid=s.rotaid
                ORDER BY s.stripid");
        return $result;
    }
    
    /**
     * 
     * @param type $rotaid
     * @return boolean
     */
    public function getRotaBlocks($rotaid){
        // TODO - The SQL isn't correct yet
        $result = $this->myconn->query("SELECT b.BlockDescrip, b.blockID FROM
            rota r, block b WHERE r.rotaid=$rotaid AND
                r.rotaid=b.rotaid
                ORDER BY b.blockid");
        if($result != false && $result->num_rows > 0){
            return $result;
        }
        return false;
        
    }
    
    /**
     * 
     * @param type $rotaid
     * @return boolean
     */
    public function getStripArray($rotaid){
        // TODO - The SQL isn't correct yet
        $result = $this->sql("SELECT s.stripID FROM
            rota r, strip s WHERE r.rotaid=$rotaid AND
                r.rotaid=s.rotaid");
        if($result){
            $x = 0;
            while($test = $result->fetch_row()){
                $return[$x] = $test[0];
                $x++;
            }
            return $return;
        }
        return false;
    }
    
    /**
     * 
     * @param type $rotaid
     * @return boolean
     */
    public function getBlockArray($rotaid){
        // TODO - The SQL isn't correct yet
        $result = $this->sql("SELECT b.blockID FROM
            rota r, block b WHERE r.rotaid=$rotaid AND
                r.rotaid=b.rotaid");
        if($result){
            $x = 0;
            while($test = $result->fetch_row()){
                $return[$x] = $test[0];
                $x++;
            }
            return $return;
        }
        return false;
    }
    
    /**
     * 
     * @param type $rotaid
     * @return type
     */
    public function addBlock($rotaid, $name = "New block"){
        $query = "INSERT INTO block (RotaID, BlockDescrip)
                        VALUES ($rotaid,'$name')";
        $this->sql($query);
        $blockR = $this->sql("SELECT LAST_INSERT_ID()");
        $block = $blockR->fetch_row();
        return $block[0];
    }
    
    /**
     * 
     * @param type $blockid
     * @return type
     */
    public function delBlock($blockid){
        $query = "DELETE FROM block
                WHERE blockid=$blockid";
        $this->sql($query);
        
        $query = "DELETE FROM blockEntry
                WHERE blockid=$blockid";
        $this->sql($query);
        return;
    }
    
    /**
     * 
     * @param type $stripid
     * @return type
     */
    public function delStrip($stripid){
        $query = "DELETE FROM strip
                WHERE stripid=$stripid";
        $this->sql($query);
        $query = "DELETE FROM blockEntry
                WHERE stripid=$stripid";
        $this->sql($query);
        return;
    }
    
    /**
     * 
     * @param type $rotaid
     * @return type
     */
    public function addStrip($rotaid, $name = "New strip"){
        $query = "INSERT INTO strip (RotaID, StripDescrip)
                        VALUES ($rotaid,'$name')";
        $this->sql($query);
        $stripR = $this->sql("SELECT LAST_INSERT_ID()");
        $strip = $stripR->fetch_row();
        return $strip[0];
    }
    
    /**
     * 
     * @param type $blockid
     * @param type $stripid
     * @return type
     */
    public function addBlockEntry($blockid,$stripid){
        $query = "INSERT INTO blockEntry (blockid, stripid, username)
                        VALUES ($blockid, $stripid,' ')";
        $result = $this->sql($query);
        return $result;
    }
    
    /**
     * 
     * @param type $id
     * @param type $name
     * @return type
     */
    public function changeStripName($id, $name){
        $query = "UPDATE strip SET stripdescrip='$name'
                    WHERE stripid=$id";
        $result = $this->sql($query);
        return $result;
    }
    
    /**
     * 
     * @param type $id
     * @param type $name
     * @return type
     */
    public function changeBlockName($id, $name){
        $query = "UPDATE block SET blockdescrip='$name'
                        WHERE blockid=$id";
        $result = $this->sql($query);
        return $result;
    }
    
    /**
     * 
     * @param type $blockids
     * @param type $stripids
     * @return type
     */
    public function getBlockEntries($blockids,$stripids){
        // Turn the arrays into strings for the sql
        $blocks = implode("','",$blockids);
        $strips = implode("','",$stripids);
        $query="SELECT u.name,b.blockid,b.stripid FROM `blockEntry` b, `user` u 
            WHERE blockID IN ('$blocks')
            AND stripID IN ('$strips')
            AND b.username=u.username
            ORDER BY stripid, blockid";
        $result = $this->sql($query);
        return $result;
    }
    
    /**("','",
     * 
     * This gets the strips and blocks, and creates the table array of 
     * information. Returns false if the rota doesn't exist.
     * 
     * @param type $rotaid
     * @return boolean
     */
    public function getTableArray($rotaid){
        if(!$this->isValidRota($rotaid)){
            return false;
        }
        
        $strips = $this->getRotaStrips($rotaid);
        $blocks = $this->getRotaBlocks($rotaid);
        
        if($strips == false || $blocks == false){
            return false;
        }
        
        $blockids = array();
        $stripids = array();
        
        // Build up the 2d array for the table
        $table[0][0] = "";
        
        $x = 0;
        // Go through the rest of the strips and add them to the table
        while ($test = $strips->fetch_row()){ 
            $table[$test[1]][0] = $test[0];
            $stripids[$x] = $test[1]; // Add id to array
            $x++; 
        }
        
        
        // Now for the block headers
        $y = 0;
        // Fill the block names
        while ($test = $blocks->fetch_row()){
            $blockids[$y] = $test[1];
            $table[0][$test[1]] = $test[0]; // Set the header to be the description
            $y++;
        }
        
        // Grab the block entries from the database
        $blockentries = $this->getBlockEntries($blockids,$stripids);
        
        if($blockentries == false){
            // We don't return false, because it does work fine, but we 
            // don't need to go through the massive loop down there
            // as there is nothing to add in.
            return $table;
        }
        
        $x1 = 1;
        $y1 = 1;
        // Loop through block entries
        while($test = $blockentries->fetch_row()){
            // Set the table entrie of blockid, stripid to be equal to username
            $table[$test[2]][$test[1]] = $test[0];
            if($y1 < $y){
                $y1++;
            }else{
                $y1 = 1;
                $x1++;
            }
        }
        
        return $table;
    }  
    
    /**
     * 
     * @param type $rotaid
     * @return boolean
     */
    public function getRotaUsers($rotaid){
        if($rotaid<1){
            return false;
        }
        $query="SELECT u.name, u.username FROM 
                    `user` u, `rota` r, `rotaUser` ru
                        WHERE ru.rotaid=r.rotaid
                        AND ru.username=u.username
                        AND r.rotaid=$rotaid";
        
        $result = $this->sql($query);
        return $result;
    }
    
    /**
     * 
     * @param type $block
     * @param type $strip
     * @param type $user
     * @return type
     */
    public function changeBlockEntry($block, $strip, $user){
        $query = "UPDATE blockEntry SET username='$user'
                        WHERE blockid=$block AND stripid=$strip";
        return $this->sql($query);
    }
    
    
    public function getRotaDetails($rotaid){
        $query = "SELECT rotaname, rotaowner FROM rota
                    WHERE rotaid=$rotaid";
        $result = $this->sql($query);
        return $result->fetch_row();
    }
    
    
    
    
    
    
    
    
    
    
    /**
     * User related functions
     */
    
    
    /**
     * 
     * Takes in the user's username, password and full name and checks
     * whether it is correct user details.
     * 
     * @param type $user
     * @param type $pass
     * @param type $uname
     * @return boolean
     */
    public function checkCorrectDetails($user, $pass, $uname){
        $hash = $this->hashPass($user, $pass, $uname);
        $query = "SELECT * from user where PassHash='".$hash."'";
        $result = $this->myconn->query($query);
        if($result!= false){
            return true;
        }
        return false;
    }
    
    /**
     * 
     * @param type $user
     * @param type $pass
     * @param type $uname
     * @return type
     */
    public function hashPass($user, $pass, $uname){
        $username = strtolower($user);
        $name = strtolower(str_replace(" ","",$uname));
        $hash = hash("sha256", $username.$pass.$name);
        return $hash;
    }
    
    /**
     * 
     * @param type $user
     * @param type $pass
     * @param type $name
     * @return boolean
     */
    public function addUser($user, $pass, $name){
        $hash = $this->hashPass($user, $pass, $name);
        $query = "INSERT INTO user(username, passhash, name)
            VALUES('$user','$hash','$name')";
        $result = $this->myconn->query($query);
        if($result != false){
            return true;
        }
        return false;
    }
    
    /**
     * 
     * Get user details. Returns the mysql row array for the user that 
     * is passed in as an argument. Returns false if user doesn't exist.
     * 
     * @param type $username
     * @return boolean
     */
    public function getUserDetails($username){
        $query = "SELECT name,username from user where UserName='$username'";
        $result = $this->myconn->query($query);
        if($result != false){
            return $result->fetch_row();
        }
        return false;
    }
    
    
    public function changeUserName($old, $new, $name, $pass){
        $passhash = $this->hashPass($new, $pass, $name);
        $query = "UPDATE user SET username='$new', passhash='$passhash'
                    WHERE username='$old'";
        $result = $this->sql($query);
        if($result != false){
            $query = "UPDATE rotaUser SET username='$new'
                    WHERE username='$old'";
            $this->sql($query);
            
            $query = "UPDATE rota SET rotaowner='$new'
                    WHERE rotaowner='$old'";
            $this->sql($query);
            
            $query = "UPDATE blockEntry SET username='$new'
                    WHERE username='$old'";
            $this->sql($query);
            return true;
        }
        return false;
    }
    
    public function changeUsersName($username, $name, $pass){
        $passhash = $this->hashPass($username, $pass, $name);
        $query = "UPDATE user SET name='$name', passhash='$passhash'
                    WHERE username='$username'";
        $result = $this->sql($query);
        if($result != false){
            return true;
        }
        return false;
    }
    
    public function deleteUser($username, $pass, $name){
        $query = "DELETE FROM rota 
                    WHERE rotaowner='$username'";
        $this->sql($query);
        $query = "DELETE FROM strip 
                    WHERE rotaid NOT IN (SELECT rotaid from rota)";
        $this->sql($query);
        $query = "DELETE FROM block 
                    WHERE rotaid NOT IN (SELECT rotaid from rota)";
        $this->sql($query);
        $query = "DELETE FROM blockEntry 
                    WHERE blockid NOT IN (SELECT blockid from block)
                    OR stripid NOT IN (SELECT stripid FROM strip)";
        $this->sql($query);
        $query = "DELETE from user
                    WHERE username='$username'";
        $result = $this->sql($query);
        return $result;
    }
	
}

?>
