<?php
include('session.php');

// To handle unicode characters at saving and loading
$connection->query("set character_set_results='utf8'");
$connection->query("set names utf8");

$id = $_POST['id'];
$name = $_POST['name'];
$picture = "";
if (!empty($_FILES)) {
   $picture = $_FILES['picture']['tmp_name'];
}
$price = $_POST['price'];
$unitId = $_POST['unitId'];

// Case of a creation => create the row in the database
$canUpdate = True;
if ($id == -1) {
   $queryString = "INSERT INTO ingredients (id, account_id) VALUES (NULL, " . $account . ");";
   $query = $connection->query($queryString);
   if (!$query) {
      $canUpdate = False;
   }
   // Get the inserted row Id
   else {
      $id = mysqli_insert_id($connection);
   }
}

if ($canUpdate) {
   $queryString = "UPDATE ingredients SET name=\"" . $name  . "\",";
   if (!empty($picture)) {
      // Format the file name
      $extension = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);

      // Upload the file (ingredient id is used to format the picture file name
      $targetFile = getcwd() . "/uploads/ingredient_pictures/" . $id . "." . $extension;
      if (move_uploaded_file($picture, $targetFile)) {
         $queryString = $queryString . " picture=\"" . $id . "." . $extension  . "\",";

         // Remove the old picture if needed
         $oldPicture = getcwd() . "/" . $_POST['oldPicture'];
         $oldExtension = pathinfo($oldPicture, PATHINFO_EXTENSION);
         if (oldExtension != extension) {
            unlink($oldPicture);
         }
      }
   }
   $queryString = $queryString . " price=" . $price . ", unit_id=" . $unitId . " WHERE id=" . $id  . ";";

   $query = $connection->query($queryString);

   if (!$query) {
      echo $query->error;
   }
}

?>
