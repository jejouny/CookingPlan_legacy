<?php
// Database connection
$connection = new mysqli("localhost", "root", "Emmanuelle_83", "cooking_plan_db");
session_start();// Starting Session
// Storing Session
$user_check = $_SESSION['login'];
// SQL Query To Fetch Complete Information Of User
$query = $connection->query("select login, account_id from users where login='$user_check'");
$row = $query->fetch_array();
$login = $row['login'];
$account = $row['account_id'];
if(!isset($login)){
   $query->close();
   $connection->close(); // Closing Connection
   header('Location: index.php'); // Redirecting To Home Page
}
?>
