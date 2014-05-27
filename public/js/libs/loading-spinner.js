/**
 * loading-spinner version 0.1.0
 * License: MIT.
 * Copyright (C) 2013, Stephane Boulard.
 */

(function (window, angular, undefined) {
    'use strict';

    angular.module('loadingSpinner', [])

        .config(['$httpProvider', function ($httpProvider) {
            var interceptor = ['$q', '$rootScope', function ($q, $rootScope) {

                return {
                    request: function (config) {
                        if (!angular.isDefined(config.loadingIndicator)) {
                            return config || $q.when(config);
                        }

                        if (angular.isString(config.loadingIndicator)) {
                            $rootScope[config.loadingIndicator] = true;
                        } else {
                            $rootScope.wait = true;
                        }

                        // Return the config or wrap it in a promise if blank.
                        return config || $q.when(config);
                    },
                    response: function (response) {
                        if (!angular.isDefined(response.config.loadingIndicator)) {
                            return response || $q.when(response);
                        }

                        if (angular.isString(response.config.loadingIndicator)) {
                            $rootScope[response.config.loadingIndicator] = false;
                        } else {
                            $rootScope.wait = false;
                        }

                        return response || $q.when(response);
                    },
                    responseError: function (rejection) {
                        if (!angular.isDefined(rejection.config.loadingIndicator)) {
                            return $q.reject(rejection);
                        }

                        if (angular.isString(rejection.config.loadingIndicator)) {
                            $rootScope[rejection.config.loadingIndicator] = false;
                        } else {
                            $rootScope.wait = false;
                        }

                        return $q.reject(rejection);
                    }
                };
            }];

            $httpProvider.interceptors.push(interceptor);
        }])

        .factory('loadingSpinnerService', ['$rootScope', function ($rootScope) {
            var config = {};

            config.spin = function (key) {
                $rootScope.$broadcast('loading-spinner:spin', key);
            };

            config.stop = function (key) {
                $rootScope.$broadcast('loading-spinner:stop', key);
            };

            return config;
        }])

        .directive('loadingSpinner', ['$window', function ($window) {
            return {
                scope: true,
                controller: function ($scope, $element, $attrs) {
                    $scope.spinner = null;
                    $scope.listen = angular.isDefined($attrs.wait) ?
                        $attrs.wait.length > 0 ? $attrs.wait :
                            'wait' : false;
                    $scope.key = angular.isDefined($attrs.key) ? $attrs.key : false;
                    $scope.defaults = {
                        lines: 15,
                        length: 0,
                        width: 5,
                        radius: 15,
                        corners: 0,
                        rotate: 25,
                        trail: 100,
                        speed: 2,
                        direction: 1,
                        position: 'absolute',
                        top: '50%'
                    };

                    $scope.spin = function () {
                        if ($scope.spinner) {
                            $scope.spinner.spin($element[0]);
                            $element.addClass('active');
                        }
                    };

                    $scope.stop = function () {
                        if ($scope.spinner) {
                            $scope.spinner.stop();
                            $element.removeClass('active');
                        }
                    };

                    $element.addClass('loading-container');
                },
                link: function (scope, element, attr) {

                    scope.$watch(attr.loadingSpinner, function (options) {
                        scope.stop();
                        var opts = angular.isObject(options) ? angular.extend(scope.defaults, options) : scope.defaults;
                        scope.spinner = new $window.Spinner(opts);
                    }, true);

                    if(scope.listen){
                        scope.$watch(scope.listen, function (value) {
                            value ? scope.spin() : scope.stop();
                        });
                    }

                    scope.$on('loading-spinner:spin', function (event, key) {
                        if (key === scope.key) {
                            scope.spin();
                        }
                    });

                    scope.$on('loading-spinner:stop', function (event, key) {
                        if (key === scope.key) {
                            scope.stop();
                        }
                    });

                    scope.$on('$destroy', function () {
                        scope.stop();
                        scope.spinner = null;
                    });
                }
            };
        }]);

})(window, window.angular);
