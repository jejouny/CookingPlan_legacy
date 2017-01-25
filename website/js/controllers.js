var app = angular.module("cooking_plan",['ngSanitize', 'ui.bootstrap', 'ngRoute', 'ngMessages']);

app.controller("tabCtrl",function($scope){
    $scope.tabSelected = "#ingredients";
    $scope.tabChange = function(e){
        //if (e.target.nodeName === 'A') {
        if (e.target.className === 'tab-control') {
            $scope.tabSelected = e.target.getAttribute("href");
            e.preventDefault();
        }
    }
});

// To filter ingredients
app.controller('filterIngredientCtrl', function($scope) {
  $scope.sortType = 'name'; // set the default sort type
  $scope.sortReverse = false;  // set the default sort order
  $scope.searchIngredients = '';     // set the default search/filter term
});

// To filter recipes
app.controller('filterRecipeCtrl', function($scope) {
  $scope.sortType = 'name'; // set the default sort type
  $scope.sortReverse = false;  // set the default sort order
  $scope.searchIngredients = '';     // set the default search/filter term
});

// To expand recipe descriptions
app.controller('readMoreCtrl',   ['$scope', '$sce', function($scope, $sce) {

   // Truncate test according to line number and length
   function truncateText(text, maxLineNumber, maxLineLength) {
      var lines = text.match(/[^\r\n]+/g);
      var isTrunc = false;
      var truncText = '';
      var lineCount = 0;

      // First split lines
      if (lines.length > maxLineNumber) {
         // Need to truncate
         isTrunc = true;
         for (var iLine = 0; iLine < lines.length; iLine++) {
            if (iLine >= maxLineNumber) {
                break;
            }
            truncText = truncText + lines[iLine];
            lineCount++;

            if (iLine != maxLineNumber -1 ) {
               truncText = truncText + "\r\n";
            }
         }
      }
      else {
         truncText = text;
         lineCount = lines.length;
      }

      // Then truncate lines
      if (truncText.length > maxLineLength) {
         isTrunc = true;
         truncText = truncText.substr(0, maxLineLength);
      }

      var result = {isTruncated:isTrunc, truncatedText:truncText, lineNumber:lineCount}; 
      return result;
   }

   // To reduce or expand the text depending on the button state
   function expandOrReduce()
   {
      var maxLineNumber = 1;
      var maxLineLength = 70;

      var ingredientsArray = $scope.recipe.ingredients;
      if ($scope.moreChecked) {
         $scope.buttonLabel ='Réduire';
         $scope.displayedContent = $scope.recipe.description;

         if (ingredientsArray.length > 0) {
            $scope.displayedContent = $scope.displayedContent + "<ul class=\"search-result-list\">";
         }

         for (var iIngredient = 0; iIngredient < ingredientsArray.length; iIngredient++) {
            $scope.displayedContent = $scope.displayedContent + "<li>" + ingredientsArray[iIngredient]["name"] + " <b>(" +  ingredientsArray[iIngredient]["amount"] + ingredientsArray[iIngredient]["unit"] + ")</b>" + "</li>";
         }

         if (ingredientsArray.length > 0) {
            $scope.displayedContent = $scope.displayedContent + "</ul>";
         }

      } else {
         $scope.buttonLabel ='Détail';
         var descriptionText = $scope.recipe.description;
         var truncated = truncateText(descriptionText, maxLineNumber, maxLineLength);
         $scope.displayedContent = truncated["truncatedText"];
         maxLineNumber = maxLineNumber - truncated["lineNumber"];

         // The description is truncated => nothing to display more
         var isTruncated = truncated["isTruncated"] || (maxLineNumber == 0 && ingredientsArray.length != 0);
         if (isTruncated) {
            $scope.showButton = true;
            $scope.displayedContent = $scope.displayedContent + " ...";
         }
         // The description is not truncated => process ingredients
         else {
            for (var iIngredient = 0; iIngredient < ingredientsArray.length; iIngredient++) {
               // Ingredient is truncated => nothing to display more
               var truncated2 = truncateText(ingredientsArray[iIngredient]["name"] + " <b>(" + ingredientsArray[iIngredient]["amount"]  + ingredientsArray[iIngredient]["unit"] + ")</b>", maxLineNumber, maxLineLength);
               maxLineNumber = maxLineNumber - truncated2["lineNumber"];
               var isTruncated = truncated2["isTruncated"]  || maxLineNumber == 0 ;

               if (iIngredient == 0) {
                  $scope.displayedContent = $scope.displayedContent + "<ul class=\"search-result-list\">";
               }

               $scope.displayedContent = $scope.displayedContent + "<li>" + truncated2["truncatedText"] + "</li>";

               if (isTruncated) {
                  $scope.showButton = true;
                  $scope.displayedContent = $scope.displayedContent + " ...";
                  $scope.displayedContent = $scope.displayedContent + "</ul>";
                  break;
               }
            }
         }
      }
   }

   // Initialization
   $scope.buttonLabel = 'Détail';
   $scope.moreChecked = false;
   $scope.showButton = false;
   $scope.displayedContent = '';
   expandOrReduce();
   $scope.displayedContent = $sce.trustAsHtml($scope.displayedContent);

   // Button callback
   $scope.readMore = function() {
      $scope.moreChecked = !$scope.moreChecked;
      expandOrReduce();
   }
}]);

// To compute hours from minutes
app.controller('formatTimeCtrl', function($scope) {

$scope.formatMinutesToHours = function(){
   return Math.floor($scope.recipe.time*1/60) + 'h' + $scope.recipe.time*1%60 + 'min';
}

});

// To populate comboboxes
app.controller('comboboxCtrl', ['$scope', '$sce', function($scope, $sce) {

   // To populate the units combobox
   $scope.populateUnitCombobox = function(){

      var comboboxContent = '';
      for (var iUnit = 0; iUnit < $scope.units.length; iUnit++) {
         var unit = $scope.units[iUnit];
         comboboxContent = comboboxContent + '<option value="' + unit.id + '"';
         if (unit.id == $scope.ingredient.unitId) {
            comboboxContent = comboboxContent + ' selected="selected"';
         }
         comboboxContent = comboboxContent + '>' + unit.name + '</option>\n';
      }

      return $sce.trustAsHtml(comboboxContent);
   }

}]);

// To add a callback of file input
app.directive('onFileChange', function() {
   return {
            restrict: 'A',
            link: function (scope, element, attrs) {
                     var bindedFunctionName = attrs.onFileChange;
                     bindedFunctionName = bindedFunctionName.replace(/[()]/g, '');
                     var onChangeFunc = scope.$eval(bindedFunctionName);
                     element.bind('change', onChangeFunc);
                  }
         };
});

// To control the selected file size
// File size in bytes
app.directive('maxFileSize', function() {
   return {
            require:'ngModel',
            link: function (scope, element, attrs, ctrl) {
                     ctrl.$setValidity('maxFileSize', true);
                     element.bind('change', function() {
                                                var maxFileSize = attrs.maxFileSize;
                                                var fileSize = 0;
                                                if (this.files[0]) {
                                                   fileSize = this.files[0].size;
                                                }
                                                ctrl.$setValidity('maxFileSize', fileSize < maxFileSize);
                                                scope.$apply(function() {
                                                      ctrl.$setViewValue(element.val());
                                                      ctrl.$render();
                                                      });
                                                   });
                  }
         };
});

// To control the selected file
app.directive('emptyImage', function() {
   return {
            require:'ngModel',
            link: function (scope, element, attrs, ctrl) {

                     var validity = true;
                     if (scope.ingredient.id == -1) {
                        validity = false;
                     }
                     ctrl.$setValidity('emptyImage', validity);
                     element.bind('change', function() {
                                                var validity = true;
                                                if (scope.ingredient.id == -1 && !this.files[0]) {
                                                   validity = false;
                                                }
                                                ctrl.$setValidity('emptyImage', validity);
                                                scope.$apply(function() {
                                                      ctrl.$setViewValue(element.val());
                                                      ctrl.$render();
                                                      });
                                                   });
                  }
         };
});


// To preview the selected image
app.controller('imageBrowserCtrl', ['$scope', function($scope) {

   // Ingredient image browser
   $scope.showIngredientImage = function () {
     var pictureView = document.getElementsByName('ingredientPictureView')[0];
     var file = document.getElementsByName('ingredientPictureInput')[0].files[0];

      var imageReader = new FileReader();
      imageReader.addEventListener('load', function() { pictureView.src = imageReader.result; }, false);

      if (file) {
         imageReader.readAsDataURL(file);
      }
   }
}]);

// To edit/create modal dialogs
app.controller('modalDialogsCtrl', ['$scope', '$uibModal', '$log', '$http', '$window', '$location', function($scope, $uibModal, $log, $http, $window, $location) {

   // Function to create a modal dialog
   function openModalDialog(template, acceptCallback, rejectCallback)
   {
      function ModalInstanceCtrl($scope, $uibModalInstance) {
         $scope.form = {}
         $scope.accept = function(canAccept) {
                                       if (canAccept) {
                                          if (acceptCallback) {
                                             acceptCallback();
                                          }
                                          $uibModalInstance.close('closed');
                                       }
                                    }
         $scope.reject = function() {
                                       if (rejectCallback) {
                                          rejectCallback();
                                       }
                                       $uibModalInstance.dismiss('cancel');
                                    }
      }

      var modalInstance = $uibModal.open({ animation: true,
                                           templateUrl: template,
                                           controller: ModalInstanceCtrl,
                                           scope:$scope,
                                         });
   };

   // Edit button callback
   $scope.editIngredient = function() {

      // No ingredient defined => that's a creation
      var ingredientCreation = !angular.isDefined($scope.ingredient);

      // Ingredient creation
      if (ingredientCreation) {
         $scope.ingredient = {id:'-1',
                              name:'', 
                              price:'0.0',
                              picture:'res/no_picture.png',
                              unitId: '-1'};
      }

      $scope.ingredient.newName = $scope.ingredient.name;
      $scope.ingredient.newPrice = $scope.ingredient.price;
      $scope.ingredient.newUnitId = $scope.ingredient.unitId;

      // Callback for the dialog
      function commitIngredient() {
         var formData = new FormData();
         formData.append('id', $scope.ingredient.id);
         formData.append('oldPicture', $scope.ingredient.picture);
         formData.append('picture', document.getElementsByName('ingredientPictureInput')[0].files[0]);
         formData.append('name', $scope.ingredient.newName);
         formData.append('price', $scope.ingredient.newPrice);
         formData.append('unitId', $scope.ingredient.newUnitId);

         // Call the PHP function
         var request = $http({
                                method: "post",
                                url: "commit_ingredient.php",
                                data: formData,
                                transformRequest: angular.identity, // To set $_POST and $_FILES php globals
                                headers: { 'Content-Type': undefined }
                             }).then(function(response) { $location.path('/ingredients.php');
                                                           $window.location.reload();
                                                         });
      }

      openModalDialog('ingredient_form.php', commitIngredient, null);
   }

   // To display the ingredient creation/edition dialog title
   $scope.formatIngredientDialogTitle = function(ingredient) {

      var title = 'Ajout d\'un ingrédient';
      if (ingredient.id != -1) {
         title = 'Modification de l\'ingrédient \"' + ingredient.name + "\"";
      }

      return title;
   }

   // Remove ingredient callback
   $scope.removeIngredient = function() {

      // Callback for the dialog
      function removeIngredient() {
         var formData = new FormData();
         formData.append('id', $scope.ingredient.id);

         // Call the PHP function
         var request = $http({
                                method: "post",
                                url: "remove_ingredient.php",
                                data: formData,
                                transformRequest: angular.identity, // To set $_POST and $_FILES php globals
                                headers: { 'Content-Type': undefined }
                             }).then(function(response) { $location.path('/ingredients.php');
                                                          $window.location.reload();
                                                         });
      }

      // Pass the ingredient id to PHP form
      openModalDialog('ingredient_removal_form.php?ingredientId='+$scope.ingredient.id, removeIngredient, null);
   }

   // Edit recipe button callback
   $scope.editRecipe = function() {

     // // No recipe defined => that's a creation
     // var recipeCreation = !angular.isDefined($scope.recipe);

     // // Recipe creation
     // if (recipeCreation) {
     //    $scope.recipe = {id:'-1',
     //                     name:'', 
     //                     price:'0.0',
     //                     picture:'res/no_picture.png',
     //                     unitId: '-1'};
     // }

     // $scope.recipe.newName = $scope.recipe.name;
     // $scope.recipe.newPrice = $scope.recipe.price;
     // $scope.recipe.newUnitId = $scope.recipe.unitId;

     // // Callback for the dialog
     // function commitRecipe() {
     //    var formData = new FormData();
     //    formData.append('id', $scope.recipe.id);
     //    formData.append('oldPicture', $scope.recipe.picture);
     //    formData.append('picture', document.getElementsByName('recipePictureInput')[0].files[0]);
     //    formData.append('name', $scope.recipe.newName);
     //    formData.append('price', $scope.recipe.newPrice);
     //    formData.append('unitId', $scope.recipe.newUnitId);

     //    // Call the PHP function
     //    var request = $http({
     //                           method: "post",
     //                           url: "commit_recipe.php",
     //                           data: formData,
     //                           transformRequest: angular.identity, // To set $_POST and $_FILES php globals
     //                           headers: { 'Content-Type': undefined }
     //                        }).then(function(response) { $location.path('/recipes.php');
     //                                                      $window.location.reload();
     //                                                    });
     // }

     // openModalDialog('recipe_form.php', commitRecipe, null);
   }

}]);

