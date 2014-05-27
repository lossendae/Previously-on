'use strict';

define(['app', 'common/directives/input-filter', '../services/data.js'], function (app) {
    var HomeController = function ($scope, $filter, DataService) {
        $scope.success = false;
        $scope.total = 0;

        DataService.listTvShows(function (result) {
            $scope.success = result.success;
            if(result.success) {
                $scope.series = result.data;
                $scope.total = result.total;
            }
        });
    };

     app.register.controller('HomeController', ['$scope', '$filter', 'DataService', HomeController]);
});
