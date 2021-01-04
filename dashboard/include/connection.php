<?php
// Database Connection Parameters
$dsn = 'mysql:host=localhost;dbname=personal_blog';
$user = 'root';
$pass = '';

// PDO Options
$options = array(
  PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
);

// Try To Connect
try {
  // Create Connection
  $db = new PDO($dsn, $user, $pass, $options);
  // Set Fetch Method Globally
  $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
  // Failed To Connect Message
  echo 'Failed to connect with database. Error:  ' . $e->getMessage();
}
