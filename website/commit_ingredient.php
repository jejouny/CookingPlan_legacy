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

$query = $connection->query("UPDATE ingredients SET name=\"" . $name  . "\", picture=\"" . $picture  . "\", price=" . $price . ", unit_id=" . $unitId . " WHERE id=" . $id  . ";");


if (!$query) {
   echo $query->error;
}

?>
