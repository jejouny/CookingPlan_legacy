<?php

// To init the angular structure from the sql request
$query_result = mysql_query("select name, picture, price from ingredients", $connection);
if (!$query_result) {
   echo mysql_error();
}

$ingredientsNgArray = "ingredients=[";
$comma = "";
while ($row = mysql_fetch_assoc($query_result)) {
   $ingredientsNgArray = $ingredientsNgArray . $comma  . "{name:'" . $row['name'] . "', picture:'" . $row['picture'] . "', price:'" . $row['price'] . "'}";
   $comma = ", ";
}
$ingredientsNgArray = $ingredientsNgArray . "]";

// Provide the ingredient array to Angular
$ingredientsTable = "<div class=\"container\" ng-controller=\"filterCtrl\" ng-init=\"" . $ingredientsNgArray . "\">";
$ingredientsTable = $ingredientsTable . "<form>";
$ingredientsTable = $ingredientsTable . "<div class=\"form-group\">";
$ingredientsTable = $ingredientsTable . "<div style=\"border:1px solid #ccc;border-radius:4px;\">";
$ingredientsTable = $ingredientsTable . "<i class=\"material-icons\">search</i>";
$ingredientsTable = $ingredientsTable . "<input type=\"text\" style=\"border:0px;\" placeholder=\"Rechercher un ingrédient\" ng-model=\"searchIngredient\">";
$ingredientsTable = $ingredientsTable . "</div>";
$ingredientsTable = $ingredientsTable . "</div>";
$ingredientsTable = $ingredientsTable . "</form>";

$ingredientsTable = $ingredientsTable . "<div style=\"overflow:auto;\"><table>";
$ingredientsTable = $ingredientsTable . "<tr ng-repeat=\"ingredient in ingredients | filter:searchIngredient\">"; // To filter on ingredient names
$ingredientsTable = $ingredientsTable . "<td><div class=\"tableItem\"><img src=\"uploads/ingredient_pictures/{{ingredient.picture}}\" style=\"width:100px;height:100px;\">{{ingredient.name}}</div></td>";
$ingredientsTable = $ingredientsTable . "</tr>";
$ingredientsTable = $ingredientsTable . "</table></div>";
$ingredientsTable = $ingredientsTable . "</div>";

/*$file_repos_dir = "/home/jjouber/Documents/Perso/Thomas_Coline";
$target_dir = $file_repos_dir . "/pictures/";
$files = scandir($target_dir);
$displayDownloadAll = 0;
foreach ($files as $file){
   if (is_dir($file)) {
      continue;
   }

   $pictureTable = $pictureTable . "<tr><td><img src=\"images/picture.png\" style=\"width:32px;height:32px;\"></td>";
   $pictureTable = $pictureTable . "<td>" . $file  . "</td>";

   if ($is_admin) {
      $pictureTable = $pictureTable . "<td style=\"padding-left:15px;\">";
      $pictureTable = $pictureTable . "<a href=\"pictures/" . $file . "\" download><img src=\"/images/download.png\" style=\"width:20px;height:20px;\" title=\"Télécharger\"></a>";
      $pictureTable = $pictureTable . "</td>";
      $pictureTable = $pictureTable . "<td style=\"padding-left:5px;\">";
      $pictureTable = $pictureTable . "<a href=\"removeFile.php?removed_file=" . $target_dir . $file . "\"><img src=\"/images/remove.png\" style=\"width:20px;height:20px;\" title=\"Supprimer\"></a>";
      $pictureTable = $pictureTable . "</td>";

      $displayDownloadAll = 1;
   }

   $pictureTable = $pictureTable . "</tr>";
}

$pictureTable=$pictureTable . "</table></div>";

// The download all button
if ($displayDownloadAll) {
   $pictureTable = $pictureTable . "<table style=\"width:625px;\"><tr><td style=\"text-align:right;\">";
   $pictureTable = $pictureTable . "<a href=\"downloadAllFiles.php?directory_to_download=" . $target_dir . "\" style=\"color:#000000;\">Tout télécharger</a>";
   $pictureTable = $pictureTable . "</td></tr></table>";
}*/
?>
