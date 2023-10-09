<?php


if(isset($_GET["ip"])){
    $ip = $_GET["ip"];

    $servername = "localhost";
    $username = "vue";
    $password = "TaxsSQL83819";
    // $username = "root";
    // $password = "";
    $dbname = "sharkTDS";

    $conn = mysqli_connect($servername, $username, $password, $dbname); 
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Query to retrieve the hashed password
    $sql = "SELECT * FROM digitalprints WHERE IP = '$ip' LIMIT 1";
    $result = $conn->query($sql);

    // Check if the query was successful
    if ($result && $result->num_rows > 0) {
        // Fetch the first row
        $row = $result->fetch_assoc();
        // Echo the hashed password
        $ISP = $row['ISP'];
        $agent = $row['Useragent'];
        $country = $row['Country'];

        $data = array(
            'result' => 1,
            'ip' => $ip,
            'isp' => $ISP,
            'agent' => $agent,
            'country' => $country
        );
        
    } else {
        $data = array(
            'result' => 0
        );
    }

    // Encode the data as JSON
    $jsonData = json_encode($data);
        
    // Set the appropriate response headers to indicate that the response will be in JSON format
    header('Content-Type: application/json');
    
    // Output the JSON-encoded data
    echo $jsonData;
}