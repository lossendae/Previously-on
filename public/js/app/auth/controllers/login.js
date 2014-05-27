'use strict';

define(['app', 'app/common/directives/inputAutofill'], function (app) {

    var AuthLoginController = function ($scope, $rootScope, authService) {

        $scope.wait = false;
        $scope.validationReady = false;
        $scope.hasErrorMessage = false;
        $scope.user = {};

        $scope.login = function(){
            $scope.wait = true;
            $scope.hasErrorMessage = false;

            authService.logIn({}, $scope.user, function(response){
                if(!response.logged){
                    $scope.hasErrorMessage = true;
                    $scope.errorMessage = response.message;
                    $scope.wait = false;
                } else {
                    $rootScope.$broadcast('event:auth-loginSuccess', response);
                }
            });
        }
    };

    app.register.controller('AuthLoginController',
        ['$scope', '$rootScope', 'authService', AuthLoginController]);

});
