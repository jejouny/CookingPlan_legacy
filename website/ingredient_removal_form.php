<?php
include('session.php');

// To get the number of recipes referencing this ingredient
/*$recipeCount = 0;

$query = $connection->query("SELECT COUNT(recipe_id) FROM recipes_ingredients WHERE ingredient_id=" . ingredient_id;
if (!$query) {
   echo $query->error;
}
else {
   // For Angular
   $unitsNgArray = "units=[";
   $comma = "";
   foreach($query as $row) {
      $unitId = $row['id'];
      $unitName = $row['unit'];
      $unitsNgArray = $unitsNgArray . $comma  . "{id:'" . $unitId . "', name:'" . $unitName . "'}";
      $comma = ", ";
   }

   $unitsNgArray = $unitsNgArray . "]";
}*/
?>

<form name="ingredientRemovalForm">
<div class="modal-header">
    <p class="search-result-content">Suppression de l'ingrédient "{{ingredient.name}}"</p>
</div>
 <div class="modal-body" ng-controller='modalDialogsCtrl'>
   <p class="formular-label-content">Etes vous sur de vouloir supprimer l'ingrédient?</p>
 </div>
<br>

 <div class="modal-footer">
     <a href="" class="modal-dialog-button" ng-click="accept(true);"/>Supprimer</a>
     <a href="" class="modal-dialog-button" ng-click="reject()"/>Annuler</a>
 </div>
</form>
