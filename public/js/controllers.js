var catalogueOfBooksControllers = angular.module('catalogueOfBooksControllers', ['ui.bootstrap']);

catalogueOfBooksControllers.controller('BookListCtrl', function ($scope, $http) {


	$scope.defaultValues = function() {

		$scope.currentPage = 1,
		$scope.paginMaxSize = 5,

		$scope.search = '',

		$scope.sortColumn = '',
		$scope.sortType = 'acs';
	}

	$scope.switchSortType = function() {
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

	$scope.searchInBD = function() {
		if ($scope.search.length > 0
			&& $scope.search.length < 3
			|| $scope.search.length > 50) return;
		$scope.refresh();
	}

	$scope.refresh = function() {

		var req_data = {
				action: 'list',
				params: {
					pageNumber : $scope.currentPage,
					sort : {
						column : $scope.sortColumn,
						type : $scope.sortType
					},
					searchQuery : $scope.search
				}
			};

		req_data = JSON.stringify(req_data);

		var req = {
			method: 'POST',
			url: 'book',
			headers: {
			  'Content-Type': 'application/json'
			},
			data: { q : req_data },
			params : { q : req_data }
		};



		$http(req).then(
			function successCallback(res){
				console.log('norm');
				console.log(res.data);

				$scope.books = res.data.data,

				$scope.totalItems = res.data.total,
				$scope.currentPage = res.data.current_page,
				$scope.numPerPage = res.data.per_page,
				$scope.maxSize = 5;

			}, function errorCallback(res){
				console.log('err');
				console.log(res);
			});
	}

	$scope.defaultValues();
	$scope.refresh();
  //$scope.orderProp = 'age';
});
