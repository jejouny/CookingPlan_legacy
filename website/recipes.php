<?php

// To init the angular structure from the sql request
mysql_query ("set character_set_results='utf8'");
$query_result = mysql_query("select name, picture, description from recipes where account_id='$account'", $connection);
if (!$query_result) {
   echo mysql_error();
}

$recipesNgArray = "recipes=[";
$comma = "";
while ($row = mysql_fetch_assoc($query_result)) {
   $recipeName = $row['name'];
   $recipePicture = $row['picture'];
   // Missing picture
   if (empty($recipePicture)) {
      $recipePicture = "res/no_picture.png";
   }
   else {
      $recipePicture = "uploads/recipe_pictures/" . $recipePicture;
   }

   // Picture not available
   if (!file_exists($recipePicture)) {
      $recipePicture = "res/no_picture.png";
   }

   //$recipeDescription = nl2br($row['description'], false);
   $recipeDescription = $row['description'];

   //$recipeType = $row['price'];
   $recipesNgArray = $recipesNgArray . $comma  . "{name:'" . $recipeName . "', picture:'" . $recipePicture . "', description:'" . $recipeDescription . "'}";
   $comma = ", ";
}

$recipesNgArray = $recipesNgArray . "]";

// Provide the recipe array to Angular
$recipesTable = "\n<div class=\"tab-content\" ng-controller=\"filterRecipeCtrl\" ng-init=\"" . $recipesNgArray . "\">\n";
$recipesTable = $recipesTable . "   <form class=\"search-bar\">\n";
$recipesTable = $recipesTable . "     <table>\n";
$recipesTable = $recipesTable . "        <tr>\n";
$recipesTable = $recipesTable . "           <td class=\"search-icon-cell\"><i class=\"material-icons\" style=\"padding:0\">search</i></td>\n";
$recipesTable = $recipesTable . "           <td class=\"search-input-cell\"><input class=\"search-input\" type=\"text\" placeholder=\"Rechercher une recette\" ng-model=\"searchRecipe\"></td>\n";
$recipesTable = $recipesTable . "        </tr>\n";
$recipesTable = $recipesTable . "      </table>\n";
$recipesTable = $recipesTable . "   </form>\n";

$recipesTable = $recipesTable . "   <div class=\"search-result\">\n";
$recipesTable = $recipesTable . "      <table>\n";
$recipesTable = $recipesTable . "         <tr ng-repeat=\"recipe in recipes | filter:{name : searchRecipe}\">\n"; // To filter on recipe names
$recipesTable = $recipesTable . "            <td class=\"search-result-cell\">\n";
$recipesTable = $recipesTable . "               <table class=\"search-result-cell-content\">\n";
$recipesTable = $recipesTable . "                  <tr>\n";
$recipesTable = $recipesTable . "                     <td class=\"search-result-icon-cell\"><div><img src=\"{{recipe.picture}}\" height=\"100px\"></div></td>\n";
$recipesTable = $recipesTable . "                     <td class=\"search-result-description-cell\" style=\"vertical-align:top;\">{{recipe.name}}<font style=\"font-family:raleway;font-size:16px;\"><pre>{{recipe.description}}</font></td>\n";
$recipesTable = $recipesTable . "                     <td class=\"search-result-price-cell\">{{recipe.price}} €</td>\n";
$recipesTable = $recipesTable . "                  </tr>\n";
$recipesTable = $recipesTable . "               </table>\n";
$recipesTable = $recipesTable . "            </td>\n";
$recipesTable = $recipesTable . "         </tr>\n";
$recipesTable = $recipesTable . "      </table>\n";
$recipesTable = $recipesTable . "   </div>\n";
$recipesTable = $recipesTable . "</div>\n";

?>
