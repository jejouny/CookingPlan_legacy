<?php
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
$connection = mysql_connect("localhost", "root", "Emmanuelle_83");
// Selecting Database
$db = mysql_select_db("cooking_plan_db", $connection);
session_start();// Starting Session
// Storing Session
$user_check=$_SESSION['login'];
// SQL Query To Fetch Complete Information Of User
$ses_sql=mysql_query("select login, admin from users where login='$user_check'", $connection);
$row = mysql_fetch_assoc($ses_sql);
$login =$row['login'];
$is_admin=$row['admin'];
if(!isset($login)){
mysql_close($connection); // Closing Connection
header('Location: index.php'); // Redirecting To Home Page
}
?>
