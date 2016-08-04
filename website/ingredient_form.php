<?php
include('session.php');

// To populate the unit combobox
$unitList = "";
$connection->query("set character_set_results='utf8'");

// Populate the combobox
$query = $connection->query("SELECT id, mnemonic AS unit FROM units");
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
}
?>

<form name="ingredientForm">
<div class="modal-header">
    <p class="search-result-content">Modification de {{ingredient.name}}</p>
</div>
 <div class="modal-body" ng-controller='modalDialogsCtrl'>
   <table class="formular-main-layout">
      <tr>
         <td class="formular-icon-cell" rowspan="4"><div><img id="ingredientPictureView" src="{{ingredient.picture}}" height="100px"></div></td>
         <td class="formular-label-cell" style="vertical-align:top;">
            <p class="formular-label-content">Description :</p>
         </td>
         <td class="formular-input-cell">
            <textarea class="formular-input-content" name="ingredientNameInput" style="resize:vertical;" rows="2" cols="20" ng-model="ingredient.newName" maxlength="120" ng-bind-html="ingredient.name" required></textarea>
         </td>
      </tr>
      <tr>
         <td class="formular-label-cell">
            <p class="formular-label-content">Image :</p>
         </td>
         <td class="formular-input-cell">
            <input class="formular-input-content" id="ingredientPictureInput" type="file" accept="image/*" style="width:95%" ng-model="ingredient.newPicture" ng-controller="imageBrowserCtrl" on-file-change="showIngredientImage()"></input>
         </td>
      </tr>
 
      <tr>
         <td class="formular-label-cell">
            <p class="formular-label-content">Prix :</p>
         </td>
         <td class="formular-input-cell">
            <input name="ingredientPriceInput" class="formular-input-content" type="number" step="0.05" value="{{ingredient.price}}" ng-model="ingredient.newPrice" min="0" max="100" required></input>
         </td>
      </tr>
      <tr>
         <td class="formular-label-cell">
            <p class="formular-label-content">Unité :</p>
         </td>
         <td class="formular-input-cell">
            <select class="formular-input-content" style="width:100%;margin-bottom:0px;" ng-init="<?php echo $unitsNgArray; ?>" ng-controller="comboboxCtrl" ng-model="ingredient.newUnitId" ng-bind-html="populateUnitCombobox()">
            </select>
         </td>
      </tr>
   </table>
 </div>

 <div ng-messages="ingredientForm.ingredientNameInput.$error" class="error">
   <span ng-message="required">Nom d'ingrédient incomplet!</span>
 </div>
 <div ng-messages="ingredientForm.ingredientPriceInput.$error" class="error">
   <span ng-message="required">Prix d'ingrédient incomplet!</span>
   <span ng-message="min">Prix d'ingrédient invalide!</span>
   <span ng-message="max">Prix d'ingrédient trop élevé!</span>
   <span ng-message="number">Prix d'ingrédient invalide!</span>
 </div><br>

 <div class="modal-footer">
     <a href="" class="modal-dialog-button" ng-class="{disabled: !ingredientForm.$valid}" ng-click="accept(ingredientForm.$valid);"/>Valider</a>
     <a href="" class="modal-dialog-button" ng-click="reject()"/>Annuler</a>
 </div>
</form>
