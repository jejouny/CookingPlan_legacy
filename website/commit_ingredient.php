<?php
include('session.php');

$connection->query ("set character_set_results='utf8'");

$postData = file_get_contents("php://input");
$request=json_decode($postData);

$id = $request->id;
$name = $request->name;
$picture = $request->picture;
$price = $request->price;
$unitId = $request->unitId;

$queryString = "UPDATE ingredients SET name=\"" . $name  . "\",";
if (!empty($picture)) {
 // Format the file name
 $extension = $path_parts($picture)['extension'];

 // Upload the file
 $targetFile = "uploads/" . $id . "." . $extension;
 if (!move_uploaded_file($picture, $targetFile)) {
   echo "Transfer issue";
 }
 else {
    $queryString = $queryString . " picture=\"" . $id . "." . $extension  . "\",";
 }
}
$queryString = $queryString . " price=" . $price . ", unit_id=" . $unitId . " WHERE id=" . $id  . ";";

$query = $connection->query($queryString);


if (!$query) {
   echo $query->error;
}

?>
