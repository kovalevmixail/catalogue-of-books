var catalogueOfBooksServices = angular.module('catalogueOfBooksServices', []);

catalogueOfBooksServices.service('bookManager', ['$http', function ($http) {

    this.saveBook = function (url, book, success, error) {
        var fd = new FormData();
        for (var key in book)
            if (book[key] != null) fd.append(key, book[key]);

        $http.post(url, fd, {
            "transformRequest": angular.identity,
            "headers": {
                "Content-Type": undefined
            }
        }).then(
            function successCallback(res) {
                success(res);
            },
            function errorCallback(res) {
                error(res);
            });
    };

    this.getBook = function (url, id, success, error) {

        var data = {
                action: 'details',
                params: {
                    id: id
                }
            },

            req = {
                method: 'POST',
                url: url,
                headers: {
                    'Content-Type': 'application/json'
                },
                data: {
                    q: JSON.stringify(data)
                }
            };

        $http(req).then(
            function successCallback(res) {
                success(res);
            },
            function errorCallback(res) {
                error(res);
            });
    }

    this.geleteBook = function (url, id, success, error) {
        var data = {
                action: 'delete',
                params: {
                    id: id
                }
            },

            req = {
                method: 'POST',
                url: url,
                headers: {
                    'Content-Type': 'application/json'
                },
                data: {
                    q: JSON.stringify(data)
                }
            };

        $http(req).then(
            function successCallback(res) {
                success(res);
            },
            function errorCallback(res) {
                error(res);
            });
    }
}])
