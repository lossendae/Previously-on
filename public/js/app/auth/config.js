'use strict';

define(['app', './services/auth', './directives/logout'], function (app) {

    var config = function ($stateProvider, $urlRouterProvider, $httpProvider, routeResolverProvider, commonsProvider) {

        var route = routeResolverProvider.route;

        $stateProvider
            .state('index.login', {
                url: "/login",
                data: {
                    bodyClass: 'login'
                },
                views: {
                    "sidebar@": {
                        template : ''
                    },
                    "main@": route.resolve('login', 'auth')
                }
            });

        $httpProvider.interceptors.push(['$rootScope', '$q', '$window', function($rootScope, $q, $window) {
            return {
                request: function (config) {
                    config.headers = config.headers || {};
                    if ($window.sessionStorage['_token']) {
                        config.headers['X-XSRF-TOKEN'] = $window.sessionStorage['_token'];
                    }
                    return config;
                }
            };
        }]);
    };

    angular.module('app.authModule', [])
        .config(['$stateProvider', '$urlRouterProvider', '$httpProvider', 'routeResolverProvider', 'commonsProvider', config ]);
});

