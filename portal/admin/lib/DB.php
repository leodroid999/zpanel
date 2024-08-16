<?php
class DB {
  const servername = "localhost";
  const username = "taxsfree";
  const password = "TaxsSQL83819";
  const dbname = "testbase";

    public static function connect() {
    // Create connection
    $conn = mysqli_connect(self::servername, self::username, self::password, self::dbname);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
        return $conn;
    }
    
    public static function getUser($conn,$userId) {
        $query = $conn->prepare("SELECT username , userId, telegram, balance, webNotifs , user_type, lastUpdateBlockBTC, lastUpdateBlockETH, chatID from users where userId=?");
        $query->bind_param('s', $userId);
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getUserList($conn) {
        $query = $conn->prepare("SELECT username, userId from users");
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getPanelList($conn) {
        $query = $conn->prepare("SELECT * from panels");
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getPageList($conn) {
        $query = $conn->prepare("SELECT * from blueprints");
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getPage($conn,$pageId){
        $query = $conn->prepare("SELECT * from blueprints where blueprint=?");
        $query->bind_param('s',$pageId);
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getNodeList($conn){
        $query = $conn->prepare("SELECT * from nodes");
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getNode($conn,$nodeId){
        $query = $conn->prepare("SELECT * from nodes where nodeId=?");
        $query->bind_param('s',$nodeId);
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function addPanel($conn,$nodeId,$panelId,$userId){
        if($nodeId==''){
            $nodeId=null;
        }
        $query = $conn->prepare(
            "INSERT INTO panels (panelId,nodeId,userId,status) VALUES (?,?,?,'PENDING') ". 
            "ON DUPLICATE KEY UPDATE nodeId=? , userId=?"
        );
        $query->bind_param('sssss', $panelId,$nodeId,$userId,$nodeId,$userId);
        $status = $query->execute();
        return $status;
    }

    public static function removePanel($conn,$nodeId,$panelId){
        if($nodeId==''){
            $nodeId=null;
        }
        $query = $conn->prepare(
            "DELETE from panels where panelId=? AND nodeId=? "
        );
        $query->bind_param('ss', $panelId,$nodeId);
        $status = $query->execute();
        return $status;
    }
  
    public static function addHost($conn,$panelId,$domain) {
        $query = $conn->prepare(
            "INSERT INTO hosts (panelId,domain,hostStatus) VALUES (?,?,'PENDING_CHECK')".
            "ON DUPLICATE KEY UPDATE panelId=? , hostStatus=\"PENDING_CHECK\""
        );
        $query->bind_param('sss', $panelId,$domain,$panelId);
        $status = $query->execute();
        return $status;
    }  

    public static function getHostList($conn) {
        $query = $conn->prepare("SELECT domain,panelId from hosts");
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function saveHostStatus($conn,$domain,$status,$ip){
        $query = $conn->prepare(
            "UPDATE hosts SET status=?, currentIp=?, lastCheck=now() where domain=?"
        );
        $query->bind_param('sss', $status,$ip,$domain);
        $status = $query->execute();
        return $status;
    }
}
?>