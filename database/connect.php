<?php

function openConnection() {
  $dbhost = getenv('DB_HOST');
  $dbuser = getenv('DB_USER');
  $dbpass = getenv('DB_PASS');
  $db_name = getenv('DB_NAME');
  
  $conn = new mysqli($dbhost, $dbuser, $dbpass, $db_name);
  if ($conn->connect_error) {
      exit('Failed to connect to database.');
  }

  return $conn;
}

function closeConnection($conn) {
  $conn->close();
}
