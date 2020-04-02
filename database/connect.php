<?php

function openConnection() {
  $dbhost = getenv('DB_HOST');
  $dbuser = getenv('DB_USER');
  $dbpass = getenv('DB_PASS');
  $db_name = getenv('DB_NAME');
  
  $conn = new mysqli($dbhost, $dbuser, $dbpass, $db_name) or die('Failed to connect to database: %s\n'. $conn->error);
  return $conn;
}

function closeConnection($conn) {
  $conn->close();
}
