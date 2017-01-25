<?php
session_start(); // Starting Session
$_SESSION = array(); // Clean session

$error=''; // Variable To Store Error Message
if (isset($_POST['submit'])) {
   if (empty($_POST['login']) || empty($_POST['password'])) {
      $error = "Identifiant ou mot de passe invalide";
   } else {
      // Define $login and $password
      $login=$_POST['login'];
      $password=$_POST['password'];
      // Establishing Connection with Server by passing server_name, user_id and password as a parameter
      $connection = new mysqli("localhost", "root", "Emmanuelle_83", "cooking_plan_db");
      if ($connection->connect_errorno) {
         $error = $connection->connect_error;
      }
      else
      {
         // To protect MySQL injection for Security purpose
         $login = stripslashes($login);
         $password = stripslashes($password);
         $login = $connection->real_escape_string($login);
         $password = $connection->real_escape_string($password);
         //$error = "'$password' AND '$login'";
         // Check if the user is in the database.
         $query = $connection->query("select * from users where password='$password' AND login='$login'");
         if ($query->num_rows == 1) {
            $_SESSION['login'] = $login; // Initializing Session
            $row = $query->fetch_array();
            $account = $row['account_id'];
            header("location: profile.php"); // Redirecting To Other Page
         } else {
            $error = "Identifiant ou mot de passe invalide";
         }
         $query->close();
         $connection->close(); // Closing Connection
      }
   }
}
?>
