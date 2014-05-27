'use strict';

define(['app', '../services/data.js', '../services/api.js'], function (app) {
    var SearchController = function ($scope, $state, DataService, ApiService) {
        $scope.success = false;
        $scope.showSearchResult = false;
        $scope.series = null;

        // Search a TV Show
        $scope.search = '';
        $scope.filter = function () {
            ApiService.search({q:$scope.search}, function (result) {
                $scope.success = result.success;
                $scope.showSearchResult = true;
                if(result.success) {
                    $scope.series = result.data;
                }
            });
        };

        $scope.add = function(id) {
            ApiService.put({id:id}, {}, function (response) {
                if(response.success){
                    $state.transitionTo('index');
//                    console.log(response);
                } else {
                    console.log(response);
                }
            });
        }
    };

     app.register.controller('SearchController', ['$scope', '$state', 'DataService', 'ApiService', SearchController]);
});
