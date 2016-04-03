'use strict';

/* App Module */

var catalogueOfBooksApp = angular.module('catalogueOfBooksApp', [
  'ngRoute',
  'catalogueOfBooksControllers',
    'catalogueOfBooksServices'
]);




catalogueOfBooksApp.config(['$routeProvider',
  function($routeProvider) {
    $routeProvider.
      when('/bookList', {
        templateUrl: 'partials/book-list.html',
        controller: 'BookListCtrl'
      }).
      when('/bookForm/:bookid?', {
        templateUrl: 'partials/book-form.html',
        controller: 'BookFormCtrl'
      }).
    when('/bookInfo/:bookid', {
        templateUrl: 'partials/book-info.html',
        controller: 'BookInfoCtrl'
    }).
      otherwise({
        redirectTo: '/bookList'
      });
  }]);


