'use strict';

define([], function () {

    var routeResolver = function (commonsProvider) {

        this.$get = function () {
            return this;
        };

        this.routeConfig = function () {
            var viewsDirectory = commonsProvider.path('views'),
                controllersDirectory = commonsProvider.path('app'),

                setBaseDirectories = function (viewsDir, controllersDir) {
                    viewsDirectory = viewsDir;
                    controllersDirectory = controllersDir;
                },

                getViewsDirectory = function () {
                    return viewsDirectory;
                },

                getControllersDirectory = function () {
                    return controllersDirectory;
                };

            return {
                setBaseDirectories: setBaseDirectories,
                getControllersDirectory: getControllersDirectory,
                getViewsDirectory: getViewsDirectory
            };
        }();

        this.route = function (routeConfig) {

            var resolve = function (baseName) {
                var routeDef = {};
                routeDef.templateUrl = routeConfig.getViewsDirectory() + baseName.hyphenize() + '.html';
                routeDef.controller = baseName.toCamelCase() + 'Controller';
                routeDef.resolve = {
                    load: ['$q', '$rootScope', function ($q, $rootScope) {
                        var dependencies = [routeConfig.getControllersDirectory() + 'controllers/' + baseName + '.js'];
                        return resolveDependencies($q, $rootScope, dependencies);
                    }]
                };

                return routeDef;
            },

            resolveDependencies = function ($q, $rootScope, dependencies) {
                var defer = $q.defer();
                require(dependencies, function () {
                    defer.resolve();
                    $rootScope.$apply()
                });

                return defer.promise;
            };

            return {
                resolve: resolve
            }
        }(this.routeConfig);

    };

    var servicesApp = angular.module('routeResolver', []);

    //Must be a provider since it will be injected into module.config()
    servicesApp.provider('routeResolver', ['commonsProvider', routeResolver]);
});

String.prototype.ucFirst = function () {
    return this.charAt(0).toUpperCase() + this.slice(1);
};
String.prototype.toCamelCase = function () {
    return this.replace(/-([a-z])/g,function (g) {
        return g[1].toUpperCase();
    }).ucFirst();
};
String.prototype.hyphenize = function () {
    return this.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase();
};
