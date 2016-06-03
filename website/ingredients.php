<?php

// To init the angular structure from the sql request
mysql_query ("set character_set_results='utf8'");
$query_result = mysql_query("select name, picture, price from ingredients", $connection);
if (!$query_result) {
   echo mysql_error();
}

$ingredientsNgArray = "ingredients=[";
$comma = "";
while ($row = mysql_fetch_assoc($query_result)) {
   $ingredientName = $row['name'];
   $ingredientPicture = $row['picture'];
   // Missing picture
   if (empty($ingredientPicture)) {
      $ingredientPicture = "res/no_picture.png";
   }
   else {
      $ingredientPicture = "uploads/ingredient_pictures/" . $ingredientPicture;
   }

   // Picture not available
   if (!file_exists($ingredientPicture)) {
      $ingredientPicture = "res/no_picture.png";
   }

   $ingredientPrice = $row['price'];
   $ingredientsNgArray = $ingredientsNgArray . $comma  . "{name:'" . $ingredientName . "', picture:'" . $ingredientPicture . "', price:'" . $ingredientPrice . "'}";
   $comma = ", ";
}
//msql_free_result($query_result);

$ingredientsNgArray = $ingredientsNgArray . "]";

// Provide the ingredient array to Angular
$ingredientsTable = "\n<div class=\"tab-content\" ng-controller=\"filterCtrl\" ng-init=\"" . $ingredientsNgArray . "\">\n";
$ingredientsTable = $ingredientsTable . "   <form class=\"search-bar\">\n";
$ingredientsTable = $ingredientsTable . "     <table>\n";
$ingredientsTable = $ingredientsTable . "        <tr>\n";
$ingredientsTable = $ingredientsTable . "           <td class=\"search-icon-cell\"><i class=\"material-icons\" style=\"padding:0\">search</i></td>\n";
$ingredientsTable = $ingredientsTable . "           <td class=\"search-input-cell\"><input class=\"search-input\" type=\"text\" placeholder=\"Rechercher un ingrédient\" ng-model=\"searchIngredient\"></td>\n";
$ingredientsTable = $ingredientsTable . "        </tr>\n";
$ingredientsTable = $ingredientsTable . "      </table>\n";
$ingredientsTable = $ingredientsTable . "   </form>\n";

$ingredientsTable = $ingredientsTable . "   <div class=\"search-result\">\n";
$ingredientsTable = $ingredientsTable . "      <table>\n";
$ingredientsTable = $ingredientsTable . "         <tr ng-repeat=\"ingredient in ingredients | filter:{name : searchIngredient}\">\n"; // To filter on ingredient names
$ingredientsTable = $ingredientsTable . "            <td class=\"search-result-cell\">\n";
$ingredientsTable = $ingredientsTable . "               <table class=\"search-result-cell-content\">\n";
$ingredientsTable = $ingredientsTable . "                  <tr>\n";
$ingredientsTable = $ingredientsTable . "                     <td class=\"search-result-icon-cell\"><div><img src=\"{{ingredient.picture}}\" height=\"100px\"></div></td>\n";
$ingredientsTable = $ingredientsTable . "                     <td class=\"search-result-description-cell\">{{ingredient.name}}</td>\n";
$ingredientsTable = $ingredientsTable . "                     <td class=\"search-result-price-cell\">{{ingredient.price}} €</td>\n";
$ingredientsTable = $ingredientsTable . "                  </tr>\n";
$ingredientsTable = $ingredientsTable . "               </table>\n";
$ingredientsTable = $ingredientsTable . "            </td>\n";
$ingredientsTable = $ingredientsTable . "         </tr>\n";
$ingredientsTable = $ingredientsTable . "      </table>\n";
$ingredientsTable = $ingredientsTable . "   </div>\n";
$ingredientsTable = $ingredientsTable . "</div>\n";

?>
