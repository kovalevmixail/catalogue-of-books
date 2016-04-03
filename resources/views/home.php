<!DOCTYPE html>
<html ng-app="catalogueOfBooksApp">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Список книг</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <script src="bower_components/angular/angular.js"></script>
        <script src="bower_components/angular-route/angular-route.min.js"></script>
        <script src="bower_components/angular-bootstrap/ui-bootstrap.min.js"></script>
        <script src="bower_components/angular-bootstrap/ui-bootstrap-tpls.min.js"></script>

        <script src="js/app.js"></script>
        <script src="js/controllers.js"></script>
        <script src="js/service.js"></script>
    </head>
    <body ng-view>

<!--         <script>
        //Вспомогательные функции
        function isNumeric(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
        }
        String.prototype.replaceAll = function (search, replace) {
        return this.split(search).join(replace);
        }
        </script> -->
    </body>
</html>
