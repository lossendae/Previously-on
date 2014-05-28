'use strict';

define(['app', '../services/data.js'], function (app) {
    var ManageController = function ($scope, $stateParams, $state, DataService) {
        $scope.serie = {};

        DataService.listEpisodes($stateParams, {}, function (response) {
            $scope.serie = response.data.serie;
            $scope.seasons = response.data.seasons;
        });

        $scope.removeTvShow = function (s) {
            DataService.removeTvShow({id: s}, {}, function (response) {
                if(response.success){
                    $state.transitionTo('index');
                }
            });
        }

        $scope.markAsWatched = function (season) {
            angular.forEach(season.episodes, function(value, key) {
                if(!value.status && !value.disabled){
                    value.status = true;
                    $scope.hasChanged(value);
                }
            }, season);
        }

        $scope.hasChanged = function (episode) {
            var p = {
                id: episode.id,
                action: episode.status ? 1 : 0
            }
            DataService.updateEpisodeStatus(p, {}, function (response) {
                $scope.serie.remaining = response.remaining;
            });
        }
    };

    app.register.controller('ManageController', ['$scope', '$stateParams', '$state', 'DataService', ManageController]);
});
