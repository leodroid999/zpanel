<?php

// Create a new cURL resource
$curl = curl_init();

// Set the request URL
curl_setopt($curl, CURLOPT_URL, 'http://z-panel.io/portal/checkIP.php?ip=5.211.66.54');
// curl_setopt($curl, CURLOPT_URL, 'http://z-panel.io/portal/checkIP.php?ip=5.211.66.54');

// Set additional options if needed
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Return the response instead of outputting it

// Execute the request
$response = curl_exec($curl);

// Check for errors
if ($response === false) {
    $error = curl_error($curl);
    // Handle the error
} else {
    // Process the response
    $json_data = json_decode($response);
    if($json_data->result == 1){
        $ip = $json_data->ip;
        $agent = $json_data->agent;
        $isp = $json_data->isp;
        $country = $json_data->country;

        echo "{$ip} : {$isp} : {$agent} : {$country}";
    }else
        echo "Nothing matched";

}

// Close the cURL session
curl_close($curl);