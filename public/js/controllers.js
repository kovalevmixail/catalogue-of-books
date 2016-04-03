var catalogueOfBooksControllers = angular.module('catalogueOfBooksControllers', ['ui.bootstrap']);



catalogueOfBooksControllers.controller('BookListCtrl', function ($scope, $http) {

    $scope.defaultValues = function () {

        $scope.currentPage = 1,
            $scope.paginMaxSize = 5,

            $scope.search = '',

            $scope.sortColumn = '',
            $scope.sortType = 'acs';
    }

    $scope.switchSortType = function () {
        if ($scope.sortType === 'acs')
            $scope.sortType = 'desc';
        else $scope.sortType = 'acs';
    }

    $scope.nameSort = function () {
        $scope.sortColumn = 'name';
        $scope.switchSortType();
        $scope.refresh();
    }

    $scope.authorSort = function () {
        $scope.sortColumn = 'author';
        $scope.switchSortType();
        $scope.refresh();
    }

    $scope.searchInBD = function () {
        if ($scope.search.length > 0 && $scope.search.length < 3 || $scope.search.length > 50) return;
        $scope.refresh();
    }

    $scope.refresh = function () {

        var req_data = {
            action: 'list',
            params: {
                pageNumber: $scope.currentPage,
                sort: {
                    column: $scope.sortColumn,
                    type: $scope.sortType
                },
                searchQuery: $scope.search
            }
        };

        req_data = JSON.stringify(req_data);

        var req = {
            method: 'POST',
            url: 'book',
            headers: {
                'Content-Type': 'application/json'
            },
            data: {
                q: req_data
            },
            params: {
                q: req_data
            }
        };



        $http(req).then(
            function successCallback(res) {
                console.log(res.data);

                $scope.books = res.data.data,

                    $scope.totalItems = res.data.total,
                    $scope.currentPage = res.data.current_page,
                    $scope.numPerPage = res.data.per_page,
                    $scope.maxSize = 5;

            },
            function errorCallback(res) {
                console.log('err!!!1');
                console.log(res);
            });
    }

    $scope.defaultValues();
    $scope.refresh();
});

catalogueOfBooksControllers.controller('BookFormCtrl', ['$scope', 'bookManager', '$routeParams', function ($scope, bookManager, $routeParams) {

    $scope.book = {};
    if ($routeParams.bookid) {
        bookManager.getBook('/book', $routeParams.bookid,
            function (res) {
                $scope.book = res.data;
                $scope.book.coverStr = res.data.cover;
            delete $scope.book.cover;
            })
    }

    $scope.errors = {
        name: [],
        author: [],
        description: [],
        year: [],
        cover: []
    };

    $scope.uploadedFile = function (element) {
        $scope.$apply(function ($scope) {
            console.log(element.files[0]);
            var reader = new FileReader();

            reader.onload = (function (theFile) {
                return function (e) {
                    var span = document.createElement('span');
                    span.innerHTML = ['<img class="thumb-form" src="', e.target.result,
                            '" title="', escape(theFile.name), '"/>'].join('');
                    console.log(span);
                    var imgBlock = document.getElementById('cover-preview');
                    imgBlock.innerHTML = '';
                    imgBlock.insertBefore(span, null);
                };
            })(element.files[0]);

            reader.readAsDataURL(element.files[0]);
            $scope.book.cover = element.files[0];
            delete $scope.book.coverStr;
        });
    }

    $scope.sendBook = function () {
        bookManager.saveBook('book/save', $scope.book,
            function success(res) {
                console.log(res.data);
                alert('Данные сохранены!');
                location.href = '#/bookInfo/' + res.data;
            },
            function error(res) {
                console.log(res);
                for (var key in $scope.errors)
                    $scope.errors[key] = [];

                for (var key in res.data)
                    $scope.errors[key] = res.data[key];
            })
    }
}])

catalogueOfBooksControllers.controller('BookInfoCtrl', function ($scope, $routeParams, bookManager) {
    $scope.errors = [];

    if (!$routeParams.bookid) {
        $scope.errors.push('Не передан id книги');
        return;
    }

    bookManager.getBook('/book', $routeParams.bookid,
        function success(res) {
            $scope.book = res.data;
        },
        function error(res) {
            console.log(res);
            if (res.data instanceof Array) {
                for (var err in res.data)
                    $scope.errors.push(res.data[err]);
            }
        })

    $scope.delete = function() {
        bookManager.geleteBook('/book', $scope.book.id,
        function success(res) {
             alert('Удалено успешно');
            location.href="#/booklist";
        },
        function error(res) {
            $scope.errors.push('Не удалось удалить книгу');
        })
    }
})
