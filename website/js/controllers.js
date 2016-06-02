var app = angular.module("cooking_plan",[]);

app.controller("tabCtrl",function($scope){
    $scope.tabSelected = "#ingredients";
    $scope.tabChange = function(e){
        if (e.target.nodeName === 'A') {
            $scope.tabSelected = e.target.getAttribute("href");
            e.preventDefault();
        }
    }
});

// To filter ingredients
app.controller('filterCtrl', function($scope) {
  $scope.sortType     = 'name'; // set the default sort type
  $scope.sortReverse  = false;  // set the default sort order
  $scope.searchIngredients   = '';     // set the default search/filter term

});
