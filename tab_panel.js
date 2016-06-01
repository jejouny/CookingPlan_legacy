var app = angular.module("tabPanel",[]);

app.controller("headerCtrl", function($scope){
  $scope.header = 'Tab Widget Angular Style';
  $scope.mySite = "Petrus Rex";
});

app.controller("tabCtrl",function($scope){
    $scope.tabSelected = "#awesome1";
    $scope.tabChange = function(e){
        if (e.target.nodeName === 'A') {
            $scope.tabSelected = e.target.getAttribute("href");
            e.preventDefault();
        }
    }
});
