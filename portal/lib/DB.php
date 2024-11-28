<?php

namespace Library;

class DB
{
    const servername = "localhost";
    const username = "vue";
    const password = "TaxsSQL83819";
    // const username = "root";
    // const password = "";
    const dbname = "testbase";

    public static function connect()
    {
        // Create connection
        $conn = mysqli_connect(self::servername, self::username, self::password, self::dbname);

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        return $conn;
    }

    public static function getUser($conn, $userId, $auth = false)
    {
        // mysqli_report(MYSQLI_REPORT_ALL);
        $query = null;
        if ($auth) {
            $query = $conn->prepare("SELECT username , password, userId, user_type, telegram, balance, webNotifs , lastUpdateBlockBTC, lastUpdateBlockETH, chatID,shortlinksPkg, memo, themeColor, Enable_LogsAsHome from users where userId=?");
        } else {
            $query = $conn->prepare("SELECT username , userId, user_type, telegram, balance, webNotifs , lastUpdateBlockBTC, lastUpdateBlockETH,chatID,shortlinksPkg, memo, themeColor, Enable_LogsAsHome from users where userId=?");
        }
        $query->bind_param('s', $userId);
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getAllUsers($conn, $userID){
        $query = $conn->prepare("SELECT username, balance, user_type, telegram, userId, chatID from users");
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getAllPanels($conn){
        $query = $conn->prepare("SELECT panels.*, users.username from panels inner join users on panels.userId = users.userId");
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getUserId($conn, $username)
    {
        $query = $conn->prepare("SELECT username , userId FROM users WHERE username=?");
        $query->bind_param("s", $username);
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getUserAuth($conn, $username)
    {
        $query = $conn->prepare("SELECT username , userId, password from users where username=?");
        $query->bind_param('s', $username);
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getUserBTCAddress($conn, $userId)
    {
        $query = $conn->prepare("SELECT * from btc_addresses where userId=?");
        $query->bind_param('i', $userId);
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            if (mysqli_num_rows($queryresult) == 0) {
                return "NO_ADDR";
            }
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function saveUserInfo($conn, $userId, $username, $password, $chatID, $webNotifs)
    {
        $query = $conn->prepare(
            "UPDATE users SET username=? , password=? , chatID=? , webNotifs=? WHERE userId=?"
        );
        $query->bind_param('sssii', $username, $password, $chatID, $webNotifs, $userId);
        $status = $query->execute();
        return $status;
    }

    public static function saveUserMemo($conn, $userId, $memo)
    {
        $query = $conn->prepare(
            "UPDATE users SET memo=? WHERE userId=?"
        );
        $query->bind_param('si', $memo, $userId);
        $status = $query->execute();
        return $status;
    }


    public static function saveUserTheme($conn, $userId, $color)
    {
        $query = $conn->prepare(
            "UPDATE users SET themeColor=? WHERE userId=?"
        );
        $query->bind_param('si', $color, $userId);
        $status = $query->execute();
        return $status;
    }

    public static function saveUserEnableLogs($conn, $userId, $enableLogs)
    {
        $query = $conn->prepare(
            "UPDATE users SET Enable_LogsAsHome=? WHERE userId=?"
        );
        $query->bind_param('ii', $enableLogs, $userId);
        $status = $query->execute();
        return $status;
    }

    public static function getUserETHAddress($conn, $userId)
    {
        $query = $conn->prepare(
            "SELECT * from eth_addresses where userId=?"
        );
        $query->bind_param('i', $userId);
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            if (mysqli_num_rows($queryresult) == 0) {
                return "NO_ADDR";
            }
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function saveBtcTransactions($conn, $user, $address, $txs)
    {
        $conn->begin_transaction();
        $query = $conn->prepare(
            "INSERT INTO btc_transactions (hash,userId,address,valueBtc,confirmedAt)
            VALUES (?,?,?,?,?)"
        );
        foreach ($txs as $tx) {
            $confirmedAt = rtrim($tx['confirmed'], 'Z');
            $query->bind_param('sisis', $tx['tx_hash'], $user['userId'], $address, $tx['value'], $confirmedAt);
            $execresult = $query->execute();
            if (!$execresult) {
                error_log("error inserting btc txs for user" . $user['userId']);
            }
        }
        try {
            $conn->commit();
        } catch (Exception $e) {
            error_log($e);
            return false;
        }
        return true;
    }

    public static function createBTCAdresssForUser($conn, $userId)
    {
        $query = $conn->prepare(
            "INSERT INTO btc_addresses " .
                "SELECT ?, COALESCE(MAX(wallet_index)+1,?) FROM btc_addresses"
        );
        $query->bind_param('ii', $userId, $userId);
        $status = $query->execute();
        return $status;
    }

    public static function createEthAdresssForUser($conn, $userId)
    {
        $query = $conn->prepare(
            "INSERT INTO eth_addresses " .
                "SELECT ?, COALESCE(MAX(eth_wallet_index)+1,?) FROM eth_addresses"
        );
        $query->bind_param('ii', $userId, $userId);
        $status = $query->execute();
        return $status;
    }


    public static function saveEthTransactions($conn, $user, $address, $txs)
    {
        $conn->begin_transaction();
        $query = $conn->prepare(
            "INSERT INTO eth_transactions (hash,userId,address,valueEth,valueUsd,rate)
            VALUES (?,?,?,?,?,?)"
        );
        foreach ($txs as $tx) {
            $res = $query->bind_param(
                'sisisi',
                $tx['hash'],
                $user['userId'],
                $address,
                $tx['value'],
                $tx['valueUsd'],
                $tx['rate']
            );
            if (!$res) {
                error_log($conn->error);
                return false;
            }
            $res = $query->execute();;
            if (!$res) {
                error_log($conn->error);
                return false;
            }
        }
        $commitResult = $conn->commit();
        if (!$commitResult) {
            error_log($conn->error);
            return false;
        }
        return true;
    }

    public static function getBtcTransactions($conn, $user)
    {
        $query = $conn->prepare("SELECT * from btc_transactions where userId=?");
        $query->bind_param('i', $user['userId']);
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getEthTransactions($conn, $user)
    {
        $query = $conn->prepare("SELECT * from eth_transactions where userId=?");
        $query->bind_param('s', $user['userId']);
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }


    public static function updateBalanceEthBlock($conn, $user, $newBalance, $block)
    {
        $query = $conn->prepare("UPDATE users SET balance = balance + ?, lastUpdateBlockETH=? WHERE userId=?");
        $query->bind_param('iii', $newBalance, $block, $user["userId"]);
        $status = $query->execute();
        return $status;
    }

    public static function updateBalanceBTCBlock($conn, $user, $newBalance, $block)
    {
        $query = $conn->prepare("UPDATE users SET balance = balance + ?, lastUpdateBlockBTC=? WHERE userId=?");
        $query->bind_param('iii', $newBalance, $block, $user["userId"]);
        $status = $query->execute();
        return $status;
    }

    public static function getPanelList($conn, $user)
    {
        $query = $conn->prepare("SELECT panels.panelId , nodes.nodeID, nodes.NodeName, expires FROM `panels` INNER JOIN `nodes` ON `panels`.`nodeID` = `nodes`.`nodeID` where userId=?");
        $query->bind_param('i', $user['userId']);
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getPanelsAddedTo($conn, $user)
    {
        $query = $conn->prepare(
            "SELECT panels.panelId , nodes.nodeID, nodes.NodeName, expires, access FROM panel_access " .
                "INNER JOIN panels ON panels.panelId = panel_access.panelId  " .
                "INNER JOIN nodes ON nodes.nodeId = panels.nodeId " .
                "where panel_access.userId=?"
        );
        $query->bind_param('i', $user['userId']);
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getPanelAddedToById($conn, $user, $panelId)
    {
        $query = $conn->prepare(
            "SELECT panels.panelId , nodes.nodeID, nodes.NodeName, expires, access FROM panel_access " .
                "INNER JOIN panels ON panels.panelId = panel_access.panelId  " .
                "INNER JOIN nodes ON nodes.nodeId = panels.nodeId " .
                "where panel_access.userId=? AND panel_access.panelId=?"
        );
        $query->bind_param('is', $user['userId'], $panelId);
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getPanel($conn, $user, $panelId)
    {
        $query = $conn->prepare(
            "SELECT * FROM `panels` INNER JOIN `nodes` ON `panels`.`nodeID` = `nodes`.`nodeID`"
                . " where userId=? AND panelId=?"
        );
        $query->bind_param('is', $user['userId'], $panelId);
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getPanelUser($conn, $panelId)
    {
        $query = $conn->prepare(
            "SELECT * FROM `panels` INNER JOIN `users` ON `panels`.`userId` = `users`.`userId`"
                . " where panelId=?"
        );
        $query->bind_param('s', $panelId);
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getPanelSettings($conn, $panelId)
    {
        $query = $conn->prepare("SELECT Mobile_Only, Redirect_All, Enable_Captcha, Enable_Turnstile, CFSiteSecret, CFSiteKey FROM panels where panelID=?");
        $query->bind_param('s', $panelId);
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function savePanelSettings($conn, $panelId, $newSettings)
    {
        $sql = "UPDATE panels SET"; 
        foreach(array_keys($newSettings) as $settingname){
            $value = $newSettings[$settingname];
            $sql .= " $settingname = '$value', ";
        }
        $sql = rtrim($sql,", ");
        $sql .= " WHERE panelID = '$panelId';";
        $query = $conn->prepare($sql);
        $query->execute();
        $status = $query->execute();
        return $status;
    }

    public static function getNode($conn, $nodeName)
    {
        $query = $conn->prepare(
            "SELECT * FROM nodes where nodeName=?"
        );
        $query->bind_param('s', $nodeName);
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getNodeById($conn, $nodeId)
    {
        $query = $conn->prepare(
            "SELECT * FROM nodes where nodeId=?"
        );
        $query->bind_param('s', $nodeId);
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
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

    public static function addHost($conn, $panelId, $domain)
    {
        $query = $conn->prepare(
            "INSERT INTO hosts (panelId,domain,hostStatus) VALUES (?,?,'PENDING_CHECK')" .
                "ON DUPLICATE KEY UPDATE panelId=? , hostStatus=\"PENDING_CHECK\""
        );
        $query->bind_param('sss', $panelId, $domain, $panelId);
        $status = $query->execute();
        return $status;
    }

    public static function getSessions($conn, $filter)
    {
        $time = time();
        $query = "SELECT * FROM logs ";
        $period = "7776000";
        if ($filter == "recent") {
            $period = "30";
        }

        if ($filter == "input") {
            $period = "5400";
        }

        $query = $query . "WHERE (Last_Online > ($time - $period)  OR Last_Online IS NULL) ";
        $hasinput = "cardnumber IS NOT NULL " .
            "OR email_address IS NOT NULL OR username IS NOT NULL";
        if (!$filter) {
            $query = $query . "AND (Last_Online > $time - 30 OR $hasinput)";
        }
        if ($filter == "inputs" || $filter == "recent") {
            $query = $query . "AND ($hasinput)";
        }
        if ($filter == "bookmarked") {
            $query = $query . "AND bookmark = TRUE ";
        }
        $query = $query . " ORDER BY Last_Online DESC";
        $statement = $conn->prepare($query);
        $statement->execute();
        $queryresult = $statement->get_result();
        if ($queryresult) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function checkPageTableExists($conn, $nodeName){
        $query = $conn->prepare("SHOW TABLES FROM `$nodeName` LIKE 'pages'");
        $query->execute();
        $result = $query->get_result();
        if ($result->num_rows > 0) {
            return true;
        }
        return false;
    }

    public static function getSession($conn, $sessionId)
    {
        $sql = "select * from logs where SessionID=?";
        $query = $conn->prepare($sql);
        $query->bind_param("s", $sessionId);
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getResponses($conn, $sessionId)
    {
        $query = $conn->prepare("select * from respons where SessionID=? ORDER BY timestamp;");
        $query->bind_param("s", $sessionId);
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getOptions($conn, $blueprintName)
    {
        $query = $conn->prepare(
            "select tokenButtonName,tokenButtonType,tokenName,pagefile,isMainRow,SendTokenWithError ".
            "FROM blueprints_tokens ".
            "where blueprint=? AND tokenButtonName IS NOT NULL");
            
        $query->bind_param("s", $blueprintName);
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function updateRedirect($conn, $sessionId, $newRedirect, $setError)
    {   
        
        if($newRedirect == "null"){
           $newRedirect == null;
        }
        $query = $conn->prepare("update logs SET Next_Redirect = ? , show_error=? where SessionID=?");
        $query->bind_param("sss", $newRedirect, $setError, $sessionId);
        $status = $query->execute();
        return $status;
    }

    public static function updateTestRedirect($conn, $user, $newRedirect, $setError)
    {
        $query = $conn->prepare("update logs_editor SET Next_Redirect = ? , show_error=? where SessionID=?");
        $query->bind_param("sss", $newRedirect, $setError, $user['username']);
        $status = $query->execute();
        return $status;
    }

    public static function sendData($conn, $sessionId, $newRedirect, $sentcode1, $sentcode2, $sentcode3, $sentcode4, $sentcode5, $setError)
    {
        $query = $conn->prepare("update logs SET Next_Redirect = ?, sentcode=? , sentcode2=? ,sentcode3=?, sentcode4=?, sentcode5=?, show_error=? where SessionID=?");
        $query->bind_param("ssssssss", $newRedirect, $sentcode1, $sentcode2, $sentcode3, $sentcode4, $sentcode5, $setError, $sessionId);
        $status = $query->execute();
        return $status;
    }

    public static function updateMemo($conn, $sessionId, $memo)
    {
        $query = $conn->prepare("update logs SET memo=? where SessionID=?");
        $query->bind_param("ss", $memo, $sessionId);
        $status = $query->execute();
        return $status;
    }

    public static function sendTestData($conn, $user, $newRedirect, $sentcode1, $sentcode2, $sentcode3, $sentcode4, $sentcode5, $setError)
    {
        $query = $conn->prepare("update logs_editor SET Next_Redirect = ?, sentcode=? , sentcode2=? ,sentcode3=?, sentcode4=?, sentcode5=?, show_error=? where SessionID=?");
        $query->bind_param("ssssssss", $newRedirect, $sentcode1, $sentcode2, $sentcode3, $sentcode4, $sentcode5, $setError, $user['username']);
        $status = $query->execute();
        return $status;
    }

    public static function bookmarkSession($conn, $sessionId, $bookmarked = false)
    {
        $query = $conn->prepare("update logs SET bookmark=? where SessionID=?");
        $query->bind_param("is", $bookmarked, $sessionId);
        $status = $query->execute();
        return $status;
    }

    public static function deleteSession($conn, $sessionId)
    {
        $query = $conn->prepare("update logs SET Last_Online = 0 where SessionID=?");
        $query->bind_param("s", $sessionId);
        $status = $query->execute();
        return $status;
    }

    public static function getNotifications($conn, $user, $limit)
    {
        $query = $conn->prepare("select * from notifications " .
            "JOIN users on notifications.userId=users.userId " .
            "where users.userId=? AND notifications.time > users.lastReadNotifications " .
            "ORDER BY time DESC LIMIT ?");

        $query->bind_param("si", $user['userId'], $limit);
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function markReadNotifications($conn, $user)
    {
        $query = $conn->prepare("update users SET lastReadNotifications = CURRENT_TIME() where userId=?");
        $query->bind_param("s", $user['userId']);
        $status = $query->execute();
        return $status;
    }

    public static function addNotification($conn, $userID, $type, $icon, $content, $isAlerted)
    {
        $query = $conn->prepare("INSERT into notifications VALUES(NULL,?,?,?,?,CURRENT_TIME(),?)");
        $query->bind_param("ssssi", $userID, $type, $icon, $content, $isAlerted);
        $status = $query->execute();
        return $status;
    }

    public static function getLocationStats($conn)
    {
        $query = $conn->prepare("SELECT Country, City, COUNT(*) AS Count FROM logs GROUP BY Country,City ORDER BY COUNT(*) DESC");
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getOsStats($conn)
    {
        $query = $conn->prepare("SELECT Count(*) AS Count,OS FROM logs GROUP BY OS ORDER BY COUNT(*) DESC");
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getCoords($conn)
    {
        $query = $conn->prepare("SELECT lat,lon FROM logs where lat IS NOT NULL AND lon IS NOT NULL ORDER BY Last_Online DESC LIMIT 30");
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getActivity($conn)
    {
        $query = $conn->prepare("SELECT * FROM notifications  WHERE time >= now() - INTERVAL 1 DAY ORDER BY time DESC");
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getSiteList($conn, $user)
    {
        $query = $conn->prepare("SELECT * FROM hosts INNER JOIN panels ON hosts.panelID = panels.panelID where userId=?");
        $query->bind_param('i', $user['userId']);
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getSiteOldList($conn, $user)
    {
        $query = $conn->prepare("SELECT * FROM hosts INNER JOIN panels ON hosts.panelID = panels.panelID where userId=? AND (hostStatus = 'DOMAIN_DOWN' OR hostStatus = 'SITE_DOWN' OR hostStatus = 'SERVER_DOWN') AND lastCheck < ?");

        $date = date("Y-m-d"); // current date
        // $date = strtotime(date("Y-m-d H:i:s", strtotime($date)) . "-14 day");
        $days_ago = date('Y-m-d H:i:s', strtotime('-14 day'));

        // $date = "2023-11-04";

        $query->bind_param('is', $user['userId'], $days_ago);
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function saveSite($conn, $user, $domain, $panelId, $hostStatus, $currentIp)
    {
        try {
            $query = $conn->prepare(
                "INSERT INTO hosts (`domain`, `panelId`, `hostStatus`, `lastCheck`, `currentIp`) " .
                    " VALUES (?, ?, ?, ?, ?)"
            );
            $query->bind_param('sssss', $domain, $panelId, $hostStatus, date("Y-m-d H:i:s"), $currentIp);
            $status = $query->execute();
            return $status;
        } catch (Exception  $e) {
            return $e->getMessage();
        }
    }

    public static function clearOldSites($conn, $user)
    {
        try {
            $query = $conn->prepare("SELECT * FROM hosts INNER JOIN panels ON hosts.panelID = panels.panelID where userId=? AND (hostStatus = 'DOMAIN_DOWN' OR hostStatus = 'SITE_DOWN' OR hostStatus = 'SERVER_DOWN') AND lastCheck < ?");

            $date = date("Y-m-d"); // current date
            $days_ago = date('Y-m-d H:i:s', strtotime('-14 day'));

            $query->bind_param('is', $user['userId'], $days_ago);
            $query->execute();
            $queryresult = $query->get_result();
            if ($queryresult) {
                $result = $queryresult->fetch_all(MYSQLI_ASSOC);

                $query = $conn->prepare("DELETE FROM hosts where domain=?");
                foreach ($result as $record) {
                    $query->bind_param('s', $record['domain']);
                    $query->execute();
                }
                return true;
            }
            return false;
        } catch (Exception  $e) {
            return $e->getMessage();
        }
    }


    public static function getProducts($conn)
    {
        $query = $conn->prepare("SELECT products.*, users.username as creatorName FROM products INNER JOIN users ON products.creator = users.userId WHERE shelfState != 'SOLD'");
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getProduct($conn, $productID)
    {
        $query = $conn->prepare("SELECT * FROM products WHERE productID=?");
        $query->bind_param("s", $productID);
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getProductTags($conn, $productID)
    {
        $query = $conn->prepare("SELECT * FROM productTags WHERE productID=?");
        $query->bind_param("s", $productID);
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getPanelAccessList($conn, $panelId)
    {
        $query = $conn->prepare(
            "SELECT panelId, users.userId, users.chatID ,users.username ,access " .
                "FROM `panel_access` " .
                "INNER JOIN users ON panel_access.userId = users.userId " .
                "WHERE panelID=?"
        );
        $query->bind_param('s', $panelId);
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function addPanelUser($conn, $panelId, $userId, $access)
    {
        $query = $conn->prepare(
            "INSERT INTO panel_access " .
                "VALUES (?,?,?)"
        );
        $query->bind_param('sis', $panelId, $userId, $access);
        $status = $query->execute();
        return $status;
    }

    public static function editPanelUser($conn, $panelId, $userId, $access)
    {
        $query = $conn->prepare(
            "UPDATE panel_access " .
                "SET access=? WHERE userId=? AND panelId=?"
        );
        $query->bind_param('sis', $access, $userId, $panelId);
        $status = $query->execute();
        return $status;
    }

    public static function removePanelUser($conn, $panelId, $userId)
    {
        $query = $conn->prepare(
            "DELETE FROM panel_access " .
                "WHERE userId=? AND panelId=?"
        );
        $query->bind_param('is', $userId, $panelId);
        $status = $query->execute();
        return $status;
    }

    public static function getShortlinkDomainList($conn, $user)
    {
        $query = $conn->prepare("SELECT * FROM `shortlink_domains`");
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getShortlinks($conn, $user)
    {
        $query = $conn->prepare("SELECT * FROM shortlink where userId=?");
        $query->bind_param('i', $user["userId"]);
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function addShortLink($conn, $user, $domain, $destination, $shortname, $blackTDS = 0)
    {
        $query = $conn->prepare(
            "INSERT INTO shortlink " .
                "VALUES (?, ?, ?, ?, ?)"
        );
        $query->bind_param('ssiss', $shortname, $domain, $user["userId"], $destination, $blackTDS);
        $status = $query->execute();
        return $status;
    }

    public static function deleteShortLink($conn, $user, $shortname)
    {
        $query = $conn->prepare(
            "DELETE FROM shortlink " .
                "WHERE userId=? AND link=?"
        );
        $query->bind_param('is', $user["userId"], $shortname);
        $status = $query->execute();
        return $status;
    }

    public static function editShortLink($conn, $user, $shortname, $destination)
    {
        $query = $conn->prepare(
            "UPDATE shortlink " .
                "SET destinationUrl=? " .
                "WHERE userId=? AND link=?"
        );
        $query->bind_param('sis', $destination, $user["userId"], $shortname);
        $status = $query->execute();
        return $status;
    }

    public static function updateUserBalance($conn, $userId, $update_balance)
    {
        $query = $conn->prepare(
            "UPDATE users " .
                "SET balance=? " .
                "WHERE userId=?"
        );
        $query->bind_param("ii", $update_balance, $userId);
        $status = $query->execute();
        return $status;
    }

    public static function updateProductState($conn, $productID, $state)
    {
        $query = $conn->prepare(
            "UPDATE products " .
                "SET shelfState=? " .
                "WHERE productID=?"
        );
        $query->bind_param("ss", $state, $productID);
        $status = $query->execute();
        return $status;
    }

    public static function insertOrder($conn, $userId, $typeOrder, $productID)
    {
        $orderId = "";
        for ($i = 0; $i < 32; $i++) {
            $orderId .= rand(0, 9);
        };
        $query = $conn->prepare(
            "INSERT INTO orders " .
                "VALUES (?, ?, ?, ?, NOW())"
        );
        $query->bind_param("siss", $orderId, $userId, $typeOrder, $productID);
        $status = $query->execute();
        return $status;
    }

    public static function getOrder($conn, $userId)
    {
        $query = $conn->prepare("SELECT orders.orderId, orders.typeOrder, products.title, products.price, orders.date, products.filePath FROM `orders` INNER JOIN `products` ON `orders`.`productID` = `products`.`productID` where userId=?");
        $query->bind_param("i", $userId);
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getBlueprints($conn,$userId)
    {
        $query = $conn->prepare("select blueprints.*, username as creator from blueprints join users on creatorUserId = userId where userId = ?");
        $query->bind_param("s",$userId);
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getBlueprint($conn, $blueprint_name)
    {
        $query = $conn->prepare("SELECT * FROM `blueprints` where blueprint=?");
        $query->bind_param("s", $blueprint_name);
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return false;
    }

    public static function getBlueprintTokens($conn, $blueprint_name)
    {
        $query = $conn->prepare("SELECT * FROM `blueprints_tokens` where blueprint=?");
        $query->bind_param("s", $blueprint_name);
        $query->execute();
        $queryresult = $query->get_result();
        
        if ($queryresult->num_rows > 0) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return [];
    }

    public static function getBlueprintLogs($conn, $username)
    {
        $query = $conn->prepare("SELECT * FROM `logs_editor` where SessionID=?");
        $query->bind_param("s", $username);
        $query->execute();
        $queryresult = $query->get_result();
        
        if ($queryresult->num_rows > 0) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result[0];
        }
        return [];
    }

    public static function getBlueprintResponse($conn, $username)
    {
        $query = $conn->prepare("SELECT * FROM `respons_editor` where SessionID=? ORDER BY created_at ASC ");
        $query->bind_param("s", $username);
        $query->execute();
        $queryresult = $query->get_result();
        
        if ($queryresult->num_rows > 0) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return [];
    }

    public static function deleteBlueprintResponse($conn, $username)
    {
        $query = $conn->prepare("DELETE FROM `respons_editor` where SessionID=?");
        $query->bind_param("s", $username);
        $status = $query->execute();
        return $status;
    }

    public static function deleteBlueprint($conn, $blueprint_name)
    {
        $query = $conn->prepare("DELETE FROM `blueprints` where blueprint=?");
        $query->bind_param("s", $blueprint_name);
        $status = $query->execute();
        return $status;
    }

    public static function deleteBlueprintIndex($conn, $fileID)
    {
        $query = $conn->prepare("DELETE FROM `blueprints_index` where fileID=?");
        $query->bind_param("s", $fileID);
        $status = $query->execute();
        return $status;
    }

    // public static function insertBlueprint($conn, $blueprint, $assetDir, $engine)
    // {
    //     $query = $conn->prepare(
    //         "INSERT INTO blueprints " .
    //             "VALUES (?, ?, ?)"
    //     );
    //     $query->bind_param("sss", $blueprint, $assetDir, $engine);
    //     $status = $query->execute();
    //     return $status;
    // }

    public static function insertBlueprint($conn, $engine, $pageName, $creator)
    {
        // $query = $conn->prepare(
        //     "INSERT INTO blueprints " .
        //         "VALUES (?, '', '', ?, '', '', '', '', '', '', ?, '','','','','','', '')"
        // );
        // $query->bind_param("sss", $pageName, $engine, $creator);
        // $status = $query->execute();

        $status = $conn->query("INSERT INTO blueprints (blueprint, engine, creator) values('$pageName', '$engine', '$creator')");
        return $status;
    }

    public static function saveBlueprint($conn, $blueprint)
    {
        $query = $conn->prepare(
            "UPDATE blueprints " .
                "SET engine=?, " .
                " assetDir=?, " .
                " country=?, " .
                " default_backlink=?, " .
                " startpage=?, " .
                " errorMsg1=?, " .
                " errorMsg2=?, " .
                " errorMsg3=?, " .
                " dataName1=?, " .
                " dataName2=?, " .
                " dataName3=?, " .
                " dataName4=?, " .
                " dataName5=?, " .
                " MainField=? " .
                "WHERE blueprint=?"
        );

        $query->bind_param("sssssssssssssss", $blueprint->engine, $blueprint->assetDir, $blueprint->country, $blueprint->default_backlink, $blueprint->startpage, $blueprint->errorMsg1, $blueprint->errorMsg2, $blueprint->errorMsg3, $blueprint->dataName1, $blueprint->dataName2, $blueprint->dataName3, $blueprint->dataName4, $blueprint->dataName5, $blueprint->MainField, $blueprint->blueprint);
        $status = $query->execute();
        return $status;
    }

    public static function saveBlueprintThumb($conn, $blueprint, $thumbnail)
    {
        $query = $conn->prepare(
            "UPDATE blueprints " .
                "SET engine=?, " .
                " assetDir=?, " .
                " country=?, " .
                " default_backlink=?, " .
                " startpage=?, " .
                " errorMsg1=?, " .
                " errorMsg2=?, " .
                " errorMsg3=?, " .
                " MainField=?, " .
                " thumbnail=? " .
                "WHERE blueprint=?"
        );

        $query->bind_param("sssssssssss", $blueprint->engine, $blueprint->assetDir, $blueprint->country, $blueprint->default_backlink, $blueprint->startpage, $blueprint->errorMsg1, $blueprint->errorMsg2, $blueprint->errorMsg3, $blueprint->MainField, $thumbnail, $blueprint->blueprint);
        $status = $query->execute();
        return $status;
    }

    public static function insertBlueprintToken($conn, $token)
    {
        $token_id = random_int(100000, 999999);

        $query = $conn->prepare(
            "INSERT INTO blueprints_tokens (`blueprint`, `tokenID`, `pagefile`, `exception_antibot`, `tokenButtonName`, `tokenButtonType`, `isMainRow`, `SendTokenWithError`, `tokenName`, `wait_lag`, `enable_redirectpulse`) " .
                "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $query->bind_param(
            "sssissiisii",
            $token->blueprint,
            $token_id,
            $token->pagefile,
            $token->exception_antibot,
            $token->tokenButtonName,
            $token->tokenButtonType,
            $token->isMainRow,
            $token->SendTokenWithError,
            $token->tokenName,
            $token->wait_lag,
            $token->enable_redirectpulse
        );

        $status = $query->execute();
        return $status;
    }

    public static function deleteBlueprintToken($conn, $blueprint_tokenId)
    {
        $query = $conn->prepare("DELETE FROM `blueprints_tokens` where tokenID=?");
        $query->bind_param("s", $blueprint_tokenId);
        $status = $query->execute();
        return $status;
    }

    public static function getBlueprintIndex($conn, $blueprint_name)
    {
        $query = $conn->prepare("SELECT * FROM `blueprints_index` where blueprint=?");
        $query->bind_param("s", $blueprint_name);
        $query->execute();
        $queryresult = $query->get_result();
        if ($queryresult->num_rows > 0) {
            $result = $queryresult->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        return [];
    }

    public static function insertBlueprintIndex($conn, $blueprint_name, $pagefile){
        $query = $conn->prepare(
            "INSERT INTO blueprints_index (`blueprint`, `pagefile`) " .
                "VALUES (?, ?)"
        );
        $query->bind_param("ss", $blueprint_name, $pagefile);
        $status = $query->execute();
        return $status;
    }
}
