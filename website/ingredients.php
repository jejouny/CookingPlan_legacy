<?php

// To init the angular structure from the sql request
mysql_query ("set character_set_results='utf8'");
$query_result = mysql_query("SELECT ingredients.name, ingredients.picture, ingredients.price, units.mnemonic AS unit FROM ingredients LEFT JOIN units ON units.id=unit_id WHERE account_id='$account'", $connection);
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
   $ingredientUnit = $row['unit'];
   $ingredientsNgArray = $ingredientsNgArray . $comma  . "{name:'" . $ingredientName . "', picture:'" . $ingredientPicture . "', price:'" . $ingredientPrice . "', unit:'" . $ingredientUnit . "'}";
   $comma = ", ";
}

$ingredientsNgArray = $ingredientsNgArray . "]";

// Provide the ingredient array to Angular
$ingredientsTable = "\n<div class=\"tab-content\" ng-controller=\"filterIngredientCtrl\" ng-init=\"" . $ingredientsNgArray . "\">\n";
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
$ingredientsTable = $ingredientsTable . "                     <td class=\"search-result-icon-cell\" rowspan=\"2\"><div><img src=\"{{ingredient.picture}}\" height=\"100px\"></div></td>\n";
$ingredientsTable = $ingredientsTable . "                     <td class=\"search-result-description-cell\" rowspan=\"2\">\n";
$ingredientsTable = $ingredientsTable . "                        <p class=\"search-result-content\" ng-bind-html=\"ingredient.name\"></p>\n";
$ingredientsTable = $ingredientsTable . "                     </td>\n";
$ingredientsTable = $ingredientsTable . "                     <td class=\"search-result-price-cell\">\n";
$ingredientsTable = $ingredientsTable . "                        <table><tr><td class=\"search-result-info-icon-cell\"><img src=\"res/price.png\" height=\"32px\"></td><td class=\"search-result-info-value-cell\">{{ingredient.price}} € par {{ingredient.unit}}</td></tr></table>\n";
$ingredientsTable = $ingredientsTable . "                     </td>\n";
$ingredientsTable = $ingredientsTable . "                  </tr>\n";
$ingredientsTable = $ingredientsTable . "                     <td style=\"padding-left:4px;vertical-align:bottom;\">\n";
$ingredientsTable = $ingredientsTable . "                        <table ng-controller=\"editIngredientCtrl\">\n";
$ingredientsTable = $ingredientsTable . "                           <tr>\n";
$ingredientsTable = $ingredientsTable . "                              <td>\n";
$ingredientsTable = $ingredientsTable . "                                 <a href=\"\" class=\"search-result-cell-button\" style=\"font-size:12px;padding:5px 10px;\" ng-click=\"editIngredient()\"/>Editer</a>\n";
$ingredientsTable = $ingredientsTable . "                              </td>\n";
$ingredientsTable = $ingredientsTable . "                              <td style=\"padding-left:10px;\">\n";
$ingredientsTable = $ingredientsTable . "                                 <a href=\"\" class=\"search-result-cell-button\"  style=\"font-size:12px;padding: 5px 10px;\" ng-click=\"removeIngredient()\"/>Supprimer</a>\n";
$ingredientsTable = $ingredientsTable . "                              </td>\n";
$ingredientsTable = $ingredientsTable . "                           </tr>\n";
$ingredientsTable = $ingredientsTable . "                        </table>\n";
$ingredientsTable = $ingredientsTable . "                     </td>\n";
$ingredientsTable = $ingredientsTable . "                  </tr>\n";
$ingredientsTable = $ingredientsTable . "                  </tr>\n";
$ingredientsTable = $ingredientsTable . "               </table>\n";
$ingredientsTable = $ingredientsTable . "            </td>\n";
$ingredientsTable = $ingredientsTable . "         </tr>\n";
$ingredientsTable = $ingredientsTable . "      </table>\n";
$ingredientsTable = $ingredientsTable . "   </div>\n";
$ingredientsTable = $ingredientsTable . "</div>\n";

?>
