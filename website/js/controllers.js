var app = angular.module("cooking_plan",[]);

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
app.controller('readMoreCtrl', function($scope) {

   function truncateText(text, maxLineNumber, maxLineLength) {
      var lines = text.match(/[^\r\n]+/g);
      var isTrunc = false;
      var truncText = '';

      // First split lines
      if (lines.length > maxLineNumber) {
         // Show the button
         isTrunc = true;
         for (var iLine = 0; iLine < lines.length; iLine++) {
            if (iLine >= maxLineNumber) {
                break;
            }
            truncText = truncText + lines[iLine];

            if (iLine != maxLineNumber -1 ) {
               truncText = truncText + "\r\n";
            }
         }
      }
      else {
         truncText = text;
      }

      // Then truncate lines
      if (truncText.length > maxLineLength) {
         isTrunc = true;
         truncText = truncText.substr(0, maxLineLength);
      }

      if (isTrunc) {
         truncText = truncText + " ...";
      }

      var result = {isTruncated:isTrunc, truncatedText:truncText}; 
      return result;
   }

   // Initialization
   $scope.buttonLabel = 'Détail';
   $scope.moreChecked = false;
   $scope.showButton = false;
   $scope.displayedContent = '';

   // Recipe description + ingredients
   var fullText = $scope.recipe.description;
   var ingredientsArray = $scope.recipe.ingredients;
   for (var iIngredient = 0; iIngredient < ingredientsArray.length; iIngredient++) {
      fullText = fullText + "\r\n" + "- " + ingredientsArray[iIngredient]["name"];
   }

   var maxLineNumber = 1;
   var maxLineLength = 70;
   var truncatedText = truncateText(fullText, maxLineNumber, maxLineLength);
   $scope.displayedContent = truncatedText["truncatedText"];
   $scope.showButton = truncatedText["isTruncated"];

   $scope.readMore = function() {
      $scope.moreChecked = !$scope.moreChecked;

      // Recipe description + ingredients
      var fullText = $scope.recipe.description;
      var ingredientsArray = $scope.recipe.ingredients;
      for (var iIngredient = 0; iIngredient < ingredientsArray.length; iIngredient++) {
         fullText = fullText + "\r\n" + "- " + ingredientsArray[iIngredient]["name"];
      }

      if ($scope.moreChecked) {
         $scope.buttonLabel ='Réduire';
         $scope.displayedContent = fullText;
      } else {
         $scope.buttonLabel ='Détail';
         var truncatedText = truncateText(fullText, maxLineNumber, maxLineLength);
         $scope.displayedContent = truncatedText["truncatedText"];
      }
   }
});
