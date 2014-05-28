'use strict';

define(['app'], function (app) {

    var config = function ($stateProvider, $urlRouterProvider, $locationProvider, $controllerProvider, $compileProvider, $filterProvider,
                           $provide, $logProvider, $httpProvider, commonsProvider, routeResolverProvider, cfpLoadingBarProvider) {

        // Turn off on prod
        $logProvider.debugEnabled(true);
        $locationProvider.html5Mode(true);

        cfpLoadingBarProvider.includeSpinner = false;

        app.register =
        {
            controller: $controllerProvider.register,
            directive: $compileProvider.directive,
            filter: $filterProvider.register,
            factory: $provide.factory,
            service: $provide.service
        };

        //Define routes - controllers will be loaded dynamically
        var route = routeResolverProvider.route;

        $stateProvider
            .state('index', {
                url: "/",
                views: {
                    "main": route.resolve('home'),
                    "head": ''
                }
            })

            .state('index.login', {
                url: "login",
                views: {
                    "auth@": route.resolve('login'),
                    "main@": ''
                },
                data: {
                    bodyClass: 'login'
                }
            })

            .state('index.gestion-serie', {
                url: "gestion/{id}",
                views: {
                    "main@": route.resolve('manage')
                }
            })

            .state('index.ajouter-serie', {
                url: "ajouter-serie",
                views: {
                    "main@": route.resolve('search')
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

        // Default route
        /* @TODO - Send to 404 ? */
        $urlRouterProvider.otherwise('/');

    };

    app.config([
        '$stateProvider',
        '$urlRouterProvider',
        '$locationProvider',
        '$controllerProvider',
        '$compileProvider',
        '$filterProvider',
        '$provide',
        '$logProvider',
        '$httpProvider',
        'commonsProvider',
        'routeResolverProvider',
        'cfpLoadingBarProvider',
        config]);
});

