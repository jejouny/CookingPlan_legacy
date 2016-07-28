<?php
include('session.php');

$connection->query ("set character_set_results='utf8'");

$query = $connection->query("UPDATE ingredients SET price='7.66' WHERE id =1;");
if (!$query) {
   echo $query->error;
}

?>
