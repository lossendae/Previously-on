'use strict';

define(['app'], function (app) {

    var authService = function ($resource, $rootScope, $state) {
        return $resource('/{{ api }}/auth/:action', null,
            {
                'logIn': {
                    method: 'POST',
                    withCredentials: true,
                    params: {'action': 'login'}
                },
                'logOut': {
                    method: 'GET',
                    interceptor: {
                        response: function (response) {
                            $rootScope.loggedUser = {};
                            $state.transitionTo('index.login');
                        }
                    },
                    params: {'action': 'logout'}
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
});
