<?php
include('session.php');

mysql_query ("set character_set_results='utf8'");

$query_result = mysql_query("UPDATE ingredients SET price='7.66' WHERE id =1;", $connection);
if (!$query_result) {
   echo mysql_error();
}

?>
