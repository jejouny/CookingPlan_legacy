<?php
session_start(); // Starting Session
$_SESSION = array(); // Clean session

$error=''; // Variable To Store Error Message
if (isset($_POST['submit'])) {
if (empty($_POST['login']) || empty($_POST['password'])) {
$error = "Identifiant ou mot de passe invalide";
}
else
{
// Define $login and $password
$login=$_POST['login'];
$password=$_POST['password'];
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
$connection = mysql_connect("localhost", "root", "Emmanuelle_83");
// To protect MySQL injection for Security purpose
$login = stripslashes($login);
$password = stripslashes($password);
$login = mysql_real_escape_string($login);
$password = mysql_real_escape_string($password);
//$error = "'$password' AND '$login'";
// Selecting Database
$db = mysql_select_db("cooking_plan_db", $connection);
// SQL query to fetch information of registerd users and finds user match.
$query = mysql_query("select * from users where password='$password' AND login='$login'", $connection);
$rows = mysql_num_rows($query);
if ($rows == 1) {
$_SESSION['login']=$login; // Initializing Session
header("location: profile.php"); // Redirecting To Other Page
} else {
$error = "Identifiant ou mot de passe invalide";
}
mysql_close($connection); // Closing Connection
}
}
?>
