<?php
include('session.php');
include('ingredients.php');
include('recipes.php');
?>
<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<html>
   <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
   <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular-sanitize.js"></script>
   <!--   <script src="http://angular-ui.github.io/bootstrap/ui-bootstrap-tpls-1.3.3.js"></script>-->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/1.3.3/ui-bootstrap-tpls.js"></script>
   <script type="text/javascript" src="js/controllers.js"></script>

   <head>
      <title>Cooking Plan</title>
      <link rel="icon" href="res/favicon.png"/>
      <link href="style.css" rel="stylesheet" type="text/css">
   </head>
   <body>
      <div id="profile">
         <b id="welcome">Bienvenue <i><?php echo $login;?></i></b>
         <b id="logout"><a id="logoutLink" href="logout.php">D&eacuteconnexion</a></b>
      </div>
      <div class="container" ng-app="cooking_plan">
         <div class="tab-controller-wrap" ng-controller="tabCtrl" ng-click="tabChange($event)">
            <a href="#ingredients" class="tab-control" ng-class="{'active':tabSelected=='#ingredients'}">Ingr&eacutedients</a>
            <a href="#recipes" class="tab-control" ng-class="{'active':tabSelected=='#recipes'}">Recettes</a>
            <a href="#createPlanning" class="tab-control" ng-class="{'active':tabSelected=='#createPlanning'}">Cr&eacuteer un planning</a>
            <a href="#history" class="tab-control" ng-class="{'active':tabSelected=='#history'}">Historique des plannings</a>
            <div class="content-wrapper">
               <div id="ingredients" class="ng-hide" ng-show="tabSelected=='#ingredients'"><?php echo $ingredientsTable; ?></div>
               <div id="recipes" class="ng-hide" ng-show="tabSelected=='#recipes'"><?php echo $recipesTable; ?></div>
               <div id="createPlanning" class="ng-hide" ng-show="tabSelected=='#createPlanning'">Le planning.</div>
               <div id="history" class="ng-hide" ng-show="tabSelected=='#history'">L'historique.</div>
            </div>
         </div>
      </div>
   </body>
</html>
