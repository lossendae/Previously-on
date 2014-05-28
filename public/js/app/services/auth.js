'use strict';

define(['app'], function (app) {

    var authService = function ($resource, $rootScope, $state) {
        var clearCache = function(response){
            if (response.status = 200 && !response.data.logged) {
                $rootScope.$broadcast('event:auth-logoutSuccess', response.data);
            }
            return response.data;
        };

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
                            return clearCache(response);
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

    app.factory('authService', ['$resource', '$rootScope', '$state', '$cacheFactory', authService]);

    var logOut = function($state, authService) {
        return function (scope, element) {
            element.bind('click', function(){
                $state.transitionTo('index');
                authService.logOut();
            });
        }
    };

    app.directive('logOut', ['$state', 'authService', logOut]);
});
