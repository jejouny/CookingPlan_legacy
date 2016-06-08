<?php

// To init the angular structure from the sql request
mysql_query ("set character_set_results='utf8'");
$query_result = mysql_query("select id, name, picture, description from recipes where account_id='$account'", $connection);
if (!$query_result) {
   echo mysql_error();
}

$recipesNgArray = "recipes=[";
$comma = "";
while ($row = mysql_fetch_assoc($query_result)) {
   $recipeId = $row['id'];
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

   $recipeDescription = $row['description'];

   // Get the recipe ingredients
   $ingredients_query_result = mysql_query("SELECT IF(ingredient_id=-1, 'Manquant',  name) AS name, IF(ingredient_id=-1, '??',  ingredient_amount) AS amount, IF(ingredient_id=-1, '??',  price) AS price FROM recipes_ingredients LEFT JOIN ingredients ON recipes_ingredients.ingredient_id=ingredients.id WHERE recipes_ingredients.recipe_id=" . $recipeId, $connection);
   if (!$ingredients_query_result) {
      echo mysql_error();
   }

   $ingredientsNgArray = "[";
   $commaIngredient = "";
   while ($ingredientRow = mysql_fetch_assoc($ingredients_query_result)) {
      $ingredientName = $ingredientRow['name'];
      $ingredientAmount = $ingredientRow['amount'];
      $ingredientPrice = $ingredientRow['price'];
      $ingredientsNgArray = $ingredientsNgArray . $commaIngredient  . "{name:'" . $ingredientRow['name'] . "', amount:'" . $ingredientRow['amount']. "', price:'" . $ingredientRow['price'] . "'}";
      $commaIngredient = ", ";
   }
   $ingredientsNgArray = $ingredientsNgArray . "]";

   $recipeIngredients = $row['price'];
   $recipesNgArray = $recipesNgArray . $comma  . "{id:'" . $recipeId . "', name:'" . $recipeName . "', picture:'" . $recipePicture . "', description:'" . $recipeDescription  . "', ingredients:" . $ingredientsNgArray . "}";
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
$recipesTable = $recipesTable . "               <table class=\"search-result-cell-content\" ng-controller=\"readMoreCtrl\">\n";
$recipesTable = $recipesTable . "                  <tr>\n";
$recipesTable = $recipesTable . "                     <td class=\"search-result-icon-cell\"><div><img src=\"{{recipe.picture}}\" height=\"100px\"></div></td>\n";
$recipesTable = $recipesTable . "                     <td class=\"search-result-description-cell\" style=\"vertical-align:top;\">\n";
$recipesTable = $recipesTable . "                        {{recipe.name}}\n";
$recipesTable = $recipesTable . "                        <p>{{displayedContent}}</p>\n";
$recipesTable = $recipesTable . "                        <a href=\"\" class=\"read-more-button\" ng-click=\"readMore()\" ng-show=\"{{showButton}}\"/>{{buttonLabel}}</a>\n";
$recipesTable = $recipesTable . "                     </td>\n";
$recipesTable = $recipesTable . "                     <td class=\"search-result-price-cell\">{{recipe.price}} €</td>\n";
$recipesTable = $recipesTable . "                  </tr>\n";
$recipesTable = $recipesTable . "               </table>\n";
$recipesTable = $recipesTable . "            </td>\n";
$recipesTable = $recipesTable . "         </tr>\n";
$recipesTable = $recipesTable . "      </table>\n";
$recipesTable = $recipesTable . "   </div>\n";
$recipesTable = $recipesTable . "</div>\n";
?>
