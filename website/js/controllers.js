var app = angular.module("cooking_plan",['ngSanitize', 'ui.bootstrap', 'ngRoute']);

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

// To populate image browsers
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

app.controller('imageBrowserCtrl', ['$scope', function($scope) {

   // Ingredient image browser
   $scope.showIngredientImage = function () {
      var imagePath = $scope.ingredient.newPicture;
      //var test = angular.element('#ingredientPictureInput');
      //var test2=0;
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
      $scope.ingredient.newName = $scope.ingredient.name;
      $scope.ingredient.newPicture = $scope.ingredient.picture;
      $scope.ingredient.newPrice = $scope.ingredient.price;
      $scope.ingredient.newUnitId = $scope.ingredient.unitId;


      // Callback for the dialog
      function commitIngredient() {

         // Call the PHP function
            var request = $http({
                                 method: "post",
                                 url: "commit_ingredient.php",
                                 data: {
                                        id: $scope.ingredient.id,
                                        name: $scope.ingredient.newName,
                                        picture: $scope.ingredient.newPicture,
                                        price: $scope.ingredient.newPrice,
                                        unitId: $scope.ingredient.newUnitId
                                 },
                                 headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                              }).then(function(response) { $location.path('/ingredients.php');
                                                           $window.location.reload();
                                                         });
      }

      openModalDialog('ingredient_form.php', commitIngredient, null);
   }

   // Remove ingredient callback
   $scope.removeIngredient = function() {
      openModalDialog('ingredient_form.html', null, null);
   }

}]);

