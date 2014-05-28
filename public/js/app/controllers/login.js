'use strict';

define(['app', 'common/directives/input-autofill'], function (app) {

    var LoginController = function ($scope, $rootScope, authService, ValidationService) {

        $scope.user = {};

        // Register validation messages
        ValidationService.setErrorMessage('username', 'required', "Veuillez saisir votre nom d'utilisateur");

        ValidationService.setErrorMessages('pwd', {
            required: 'Veuillez saisir votre mot de passe',
            minlength: 'Le mot de passe doit contenir au minimum 6 caractères',
            maxlength: 'Le mot de passe ne peut pas dépasser 18 caractères'
        });

        ValidationService.ready();

        $scope.login = function () {
            if($scope.loginForm.$valid) {

                authService.logIn({}, $scope.user, function (response) {
                    if (!response.logged) {
                        ValidationService.notify($scope.loginForm, response);
                    } else {
                        $rootScope.$broadcast('event:auth-loginSuccess', response);
                    }
                });
            }
        }
    };

    app.register.controller('LoginController',
        ['$scope', '$rootScope', 'authService', 'ValidationService', LoginController]);

});
