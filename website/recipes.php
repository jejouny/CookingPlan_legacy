<?php

// To init the angular structure from the sql request
mysql_query ("set character_set_results='utf8'");
mysql_query ("SET lc_time_names = 'fr_FR';");
$query_result = mysql_query("SELECT recipes.id AS id, recipes.name AS name, recipes.picture AS picture, recipes.description AS description, recipe_types.name AS type, time_slots.name AS time_slot, MONTHNAME(STR_TO_DATE(recipes.month_start, '%m')) AS month_start, MONTHNAME(STR_TO_DATE(recipes.month_end, '%m')) AS month_end, recipes.customer_count AS customer_count, recipes.time AS time FROM recipes LEFT JOIN recipe_types ON recipe_types.id=type_id LEFT JOIN time_slots ON time_slots.id=time_slot_id where account_id='$account'", $connection);
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
   $recipeType = $row['type'];
   $recipeTimeSlot = $row['time_slot'];
   $recipeMonthStart = $row['month_start'];
   $recipeMonthEnd = $row['month_end'];
   $recipeCustomerCount = $row['customer_count'];
   $recipeTime = $row['time'];

   // Get the recipe ingredients and their amount
   $ingredients_query_result = mysql_query("SELECT name, amount, IF(ingredient_id=-1, '?', mnemonic) AS unit, price FROM (SELECT ingredient_id, IF(ingredient_id=-1, 'Manquant',  name) AS name, IF(ingredient_id=-1, '0.000',  ingredient_amount) AS amount, ingredient_unit_id, IF(ingredient_id=-1, 0.00, price) AS price FROM recipes_ingredients LEFT JOIN ingredients ON recipes_ingredients.ingredient_id=ingredients.id WHERE recipes_ingredients.recipe_id=" . $recipeId  . ") AS temp LEFT JOIN units ON temp.ingredient_unit_id=units.id", $connection);

   if (!$ingredients_query_result) {
      echo mysql_error();
   }

   $ingredientsNgArray = "[";
   $commaIngredient = "";
   $recipePrice = 0.00;
   while ($ingredientRow = mysql_fetch_assoc($ingredients_query_result)) {
      $ingredientName = $ingredientRow['name'];
      $ingredientAmount = $ingredientRow['amount'];
      $ingredientPrice = $ingredientRow['price'];

      $recipePrice = $recipePrice + $ingredientPrice * $ingredientAmount;
      $ingredientsNgArray = $ingredientsNgArray . $commaIngredient  . "{name:'" . $ingredientRow['name'] . "', amount:'" . $ingredientRow['amount']  . "', unit:'" . $ingredientRow['unit'] . "'}";
      $commaIngredient = ", ";
   }
   $ingredientsNgArray = $ingredientsNgArray . "]";

   $recipesNgArray = $recipesNgArray . $comma  . "{id:'" . $recipeId . "', name:'" . $recipeName . "', picture:'" . $recipePicture . "', description:'" . $recipeDescription  . "', ingredients:" . $ingredientsNgArray . ", price:'" . $recipePrice . "', type:'". $recipeType . "', time_slot:'" . $recipeTimeSlot . "', month_start:'" . $recipeMonthStart . "', month_end:'" . $recipeMonthEnd . "', time:'" . $recipeTime . "', customer_count:'" . $recipeCustomerCount . "'}";
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
$recipesTable = $recipesTable . "                        <p class=\"search-result-content\" ng-bind-html=\"recipe.name\"></p>\n";
$recipesTable = $recipesTable . "                        <p class=\"search-result-sub-content\" ng-bind-html=\"displayedContent\"></p>\n";
$recipesTable = $recipesTable . "                        <a href=\"\" class=\"read-more-button\" ng-click=\"readMore()\" ng-show=\"{{showButton}}\"/>{{buttonLabel}}</a>\n";
$recipesTable = $recipesTable . "                     </td>\n";
$recipesTable = $recipesTable . "                     <td class=\"search-result-info-cell\">\n";
$recipesTable = $recipesTable . "                        <table><tr><td class=\"search-result-info-icon-cell\"><img src=\"res/time_slot.png\" height=\"32px\"></td><td class=\"search-result-info-value-cell\">{{recipe.time_slot}}</td></tr><tr><td class=\"search-result-info-icon-cell\"><img src=\"res/customer.png\" height=\"32px\"></td><td class=\"search-result-info-value-cell\">{{recipe.customer_count}} personnes</td></tr></table>\n";
$recipesTable = $recipesTable . "                     </td>\n";
$recipesTable = $recipesTable . "                     <td class=\"search-result-info-cell\">\n";
$recipesTable = $recipesTable . "                        <table><tr><td  class=\"search-result-info-icon-cell\"><img src=\"res/course.png\" height=\"32px\"></td><td class=\"search-result-info-value-cell\">{{recipe.type}}</td></tr><tr><td  class=\"search-result-info-icon-cell\"><img src=\"res/time.png\" height=\"32px\"></td><td class=\"search-result-info-value-cell\" ng-controller=\"formatTimeCtrl\" ng-bind=\"formatMinutesToHours()\"></td></tr></table>\n";
$recipesTable = $recipesTable . "                     </td>\n";
$recipesTable = $recipesTable . "                     <td class=\"search-result-info-cell\">\n";
$recipesTable = $recipesTable . "                        <table><tr><td class=\"search-result-info-icon-cell\"><img src=\"res/calendar.png\" height=\"32px\"></td><td class=\"search-result-info-value-cell\">{{recipe.month_start}} à {{recipe.month_end}}</td></tr><tr><td class=\"search-result-info-icon-cell\"><img src=\"res/price.png\" height=\"32px\"></td><td class=\"search-result-info-value-cell\">{{recipe.price}} €</td></tr></table>\n";
$recipesTable = $recipesTable . "                     </td>\n";
$recipesTable = $recipesTable . "                  </tr>\n";
$recipesTable = $recipesTable . "               </table>\n";
$recipesTable = $recipesTable . "            </td>\n";
$recipesTable = $recipesTable . "         </tr>\n";
$recipesTable = $recipesTable . "      </table>\n";
$recipesTable = $recipesTable . "   </div>\n";
$recipesTable = $recipesTable . "</div>\n";
?>
