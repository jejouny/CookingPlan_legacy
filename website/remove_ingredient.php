<?php
include('session.php');

$id = $_POST['id'];
$picture='';

// Get the ingredient picture
$queryString = "SELECT picture FROM ingredients WHERE id=" . $id  . ";";
$query = $connection->query($queryString);
if ($query) {
   $row = $query->fetch_array();
   $picture = $row['picture'];
}

$queryString = "DELETE FROM ingredients WHERE id=" . $id  . ";";
$query = $connection->query($queryString);

if (!$query) {
echo $query->error;
}
// Remove the picture
else {
   if (!empty($picture)) {
      unlink(getcwd() . "/uploads/ingredient_pictures/" . $picture);
   }
}

?>
