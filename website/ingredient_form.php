<?php
include('session.php');

// To populate the unit combobox
$unitList = "";
mysql_query ("set character_set_results='utf8'");

// Populate the combobox
$query_result = mysql_query("SELECT id, mnemonic AS unit FROM units", $connection);
if (!$query_result) {
   echo mysql_error();
}
else {
   // For Angular
   $unitsNgArray = "units=[";
   $comma = "";
   while ($row = mysql_fetch_assoc($query_result)) {
      $unitId = $row['id'];
      $unitName = $row['unit'];
      $unitsNgArray = $unitsNgArray . $comma  . "{id:'" . $unitId . "', name:'" . $unitName . "'}";
      $comma = ", ";
   }

   $unitsNgArray = $unitsNgArray . "]";
}
?>

<div class="modal-header">
    <p class="search-result-content">Modification de {{ingredient.name}}</p>
</div>
 <div class="modal-body" ng-controller='modalDialogsCtrl'>
   <table class="formular-main-layout">
      <tr>
         <td class="formular-icon-cell" rowspan="4"><div><img src="{{ingredient.picture}}" height="100px"></div></td>
         <td class="formular-label-cell" style="vertical-align:top;">
            <p class="formular-label-content">Description :</p>
         </td>
         <td class="formular-input-cell">
            <textarea class="formular-input-content" style="resize:vertical;" rows="2" cols="20" ng-model="newName" ng-bind-html=ingredient.name></textarea>
         </td>
      </tr>
      <tr>
         <td class="formular-label-cell">
            <p class="formular-label-content">Image :</p>
         </td>
         <td class="formular-input-cell">
            <input class="formular-input-content" type="file" accept="image/*" style="width:94%" ng-model="newPicture"></input>
         </td>
      </tr>
 
      <tr>
         <td class="formular-label-cell">
            <p class="formular-label-content">Prix :</p>
         </td>
         <td class="formular-input-cell">
            <input class="formular-input-content" type="number" step="0.05" value="{{ingredient.price}}" ng-model="newPrice"></input>
         </td>
      </tr>
      <tr>
         <td class="formular-label-cell">
            <p class="formular-label-content">Unité :</p>
         </td>
         <td class="formular-input-cell">
            <select class="formular-input-content" style="width:99%;margin-bottom:0px;" ng-init="<?php echo $unitsNgArray; ?>" ng-controller="comboboxCtrl" ng-model="newUnitId" ng-bind-html="populateUnitCombobox()">
            </select>
         </td>
      </tr>
   </table>
 </div>
 <div class="modal-footer">
     <a href="" class="modal-dialog-button" ng-click="accept()"/>Valider</a>
     <a href="" class="modal-dialog-button" ng-click="reject()"/>Annuler</a>
 </div>
