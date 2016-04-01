'use strict';

/* App Module */

var catalogueOfBooksApp = angular.module('catalogueOfBooksApp', [
  'ngRoute',
  'catalogueOfBooksControllers'
]);

catalogueOfBooksApp.config(['$routeProvider',
  function($routeProvider) {
    $routeProvider.
      when('/boolList', {
        templateUrl: 'partials/book-list.html',
        controller: 'BookListCtrl'
      }).
      when('/bookForm', {
        templateUrl: 'partials/book-form.html',
        controller: 'BookFormCtrl'
      }).
      otherwise({
        redirectTo: '/boolList'
      });
  }]);
