<?php
include('login.php'); // Includes Login Script

if(isset($_SESSION['login_user'])){
header("location: profile.php");
}
?>
<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<html>
   <head>
      <title>Cooking Plan</title>
      <link rel="icon" href="res/favicon.png"/>
      <link href="style.css" rel="stylesheet" type="text/css">
   </head>
   <body>
      <img src="res/banner.svg" style="max-width: 100%"/>
      <div id="login">
            <form action="" method="post">
               <label>Identifiant :</label>
               <input id="name" name="login" placeholder="identifiant" type="text">
               <br>
               <br>
               <label>Mot de passe :</label>
               <input id="password" name="password" placeholder="**********" type="password">
               <br>
               <br>
               <input name="submit" type="submit" value=" Connexion ">
               <span><?php echo $error; ?></span>
            </form>
         </div>
   </body>
</html>
