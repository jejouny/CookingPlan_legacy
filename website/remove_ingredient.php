<?php
include('session.php');

$id = $_POST['id'];

$queryString = "DELETE FROM ingredients WHERE id=" . $id  . ";";
$query = $connection->query($queryString);

if (!$query) {
echo $query->error;
}

?>
