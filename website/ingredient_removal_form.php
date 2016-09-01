<?php
include('session.php');

$ingredientId = $_GET['ingredientId'];
$message = "Voulez-vous vraiment supprimer l'ingrédient?";

// To get the number of recipes referencing this ingredient
$query = $connection->query("SELECT COUNT(recipe_id) AS recipe_count FROM recipes_ingredients WHERE ingredient_id=" . $ingredientId . ";");

if (!$query) {
   echo $query->error;
}
else {
   $row = $query->fetch_array();
   $recipeCount = $row['recipe_count'];
   $query->close();

   // Format the displayed message
   if ($recipeCount > 0) {
      $message = "L'ingrédient est utilisé dans " . $recipeCount . " recettes.<br>";
      $message = $message . "Voulez-vous vraiment le supprimer?";
   }
}

?>

<form name="ingredientRemovalForm">
<div class="modal-header">
    <p class="search-result-content">Suppression de l'ingrédient "{{ingredient.name}}"</p>
</div>
 <div class="modal-body" ng-controller='modalDialogsCtrl'>
   <p class="formular-label-content"><?php echo $message ?> </p>
 </div>
<br>

 <div class="modal-footer">
     <a href="" class="modal-dialog-button" ng-click="accept(true);"/>Supprimer</a>
     <a href="" class="modal-dialog-button" ng-click="reject()"/>Annuler</a>
 </div>
</form>
