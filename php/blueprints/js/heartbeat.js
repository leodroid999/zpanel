function heartbeat(x) {
  // Create a new XMLHttpRequest object
  let xhr = new XMLHttpRequest();

  // Set up the request
  xhr.open("POST", "../scripts/heartbeat.php", true);



  // Set the content type of the request
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  // Define the data to be sent in the request
  let data = "z=" + encodeURIComponent(x);

  // Send the request with the data
  xhr.send(data);
}
