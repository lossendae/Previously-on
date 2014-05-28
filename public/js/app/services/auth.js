'use strict';

define(['app'], function (app) {

    var authService = function ($resource, $rootScope, $state) {
        return $resource('/auth/:action', null,
            {
                'logIn': {
                    method: 'POST',
                    withCredentials: true,
                    params: {'action': 'login'},
                    loadingIndicator : true
                },
                'logOut': {
                    method: 'GET',
                    interceptor: {
                        response: function (response) {
                            $rootScope.loggedUser = {};
                            $state.transitionTo('index.login');
                        }
                    },
                    params: {'action': 'logout'},
                    loadingIndicator : true
                },
                'token': {
                    method: 'GET',
                    params: {'action': 'token'}
                },
                'check': {
                    method: 'GET',
                    params: {'action': 'check'}
                },
                'session': {
                    method: 'GET',
                    params: {'action': 'session'}
                }
            });
    };

    app.factory('authService', ['$resource', '$rootScope', '$state', authService]);

    var logOut = function(authService) {
        return function (scope, element) {
            element.bind('click', function(){
                authService.logOut();
            });
        }
    };

    app.directive('logOut', ['authService', logOut]);
});
