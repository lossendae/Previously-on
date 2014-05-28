/**
 * admin-form-validation version 0.1.0
 * License: MIT.
 * Copyright (C) 2013, Stephane Boulard.
 */

(function (window, angular, undefined) {
    'use strict';

    angular.module('FormValidation', [])

        .factory('ValidationService', ['$rootScope', '$timeout',
            function ($rootScope, $timeout) {
                var service = {};
                service.fields = {};

                service.setErrorMessage = function (field, key, message) {
                    if (!angular.isDefined(this.fields[field])) {
                        this.fields[field] = {};
                    }
                    this.fields[field][key] = message;
                };

                service.setErrorMessages = function (field, object) {
                    if (!angular.isDefined(this.fields[field])) {
                        this.fields[field] = {};
                    }
                    angular.extend(this.fields[field], object);
                };

                service.getErrorMessages = function (field) {
                    return this.fields[field];
                };

                service.reset = function (form, scope) {
                    form.$setPristine();
                    var name = this.getValidationDisplayName(form.$name);
                    scope[name] = false;
                };

                service.getValidationDisplayName = function (name) {
                    return 'show' + name.charAt(0).toUpperCase() + name.slice(1) + 'Validation';
                };

                service.ready = function () {
                    $timeout(function () {
                        $rootScope.$broadcast('service-validation:ready');
                    }, 0);
                };

                service.notify = function (form, response) {
                    if (!response.success) {
                        $rootScope.$broadcast('service-validation:server-error', form.$name, response);
                    }
                };

                return service;
            }
        ])

        .directive('validateSubmit', ['$rootScope', '$parse', 'ValidationService',
            function ($rootScope, $parse, ValidationService) {
                var link = function (scope, element, attrs, form) {
                    var name = ValidationService.getValidationDisplayName(form.$name);

                    element.bind('submit', function (event) {
                        if (!scope.$$phase) scope.$apply();

                        var fn = $parse(attrs.validateSubmit);

                        // Create the form controller on the parent scope if it does not exists
                        if (!angular.isDefined(scope.$parent[form.$name])) {
                            scope.$parent[form.$name] = form;
                        }
                        if (form.$valid) {
                            scope.$apply(function () {
                                fn(scope, {$event: event});
                            });
                            scope.$broadcast('form-validation:submit');
                            scope.$apply(name + ' = false');
                        } else {
                            scope.$apply(name + ' = true');
                        }
                    });
                };

                return {
                    restrict: 'A',
                    require: 'form',
                    link: link
                };
            }
        ])

        .directive('validationMessages', ['ValidationService', function (ValidationService) {
            var link = function (scope, element, attrs) {
                scope.form = element.parent().controller('form');
                scope.show = ValidationService.getValidationDisplayName(scope.form.$name);
                scope.field = attrs.validationMessages;
                scope.errorClass = 'input-error';

                // Useful when form has not yet been rendered
                scope.$emit('form-validation:ready');

                scope.$on('service-validation:ready', function () {
                    scope.messages = ValidationService.getErrorMessages(scope.field);
                });
            };

            return {
                restrict: 'A',
                replace: true,
                scope: true,
                template: '<div class="{{ errorClass }}" ng-repeat="(key, value) in messages" data-ng-show="{{ show }} && {{ form.$name }}.{{ field }}.$error.{{ key }}">{{ value }}</div>',
                link: link
            };
        }])

        .directive('validationServerMessages', ['ValidationService', function (ValidationService) {
            return {
                restrict: 'A',
                replace: true,
                scope: true,
                template: '<div class="{{ errorClass }}" ng-repeat="error in errors" data-ng-show="{{ show }}">' +
                    '<h5 data-ng-if="error.title">{{ error.title }}</h5>' +
                    '<p ng-repeat="message in error.messages">{{ message }}</p>' +
                    '</div>',
                controller: function ($scope, $element, $attrs) {
                    $scope.errors = [];
                    $scope.form = $element.parent().controller('form');
                    $scope.show = ValidationService.getValidationDisplayName($scope.form.$name);
                    $scope.errorClass = $attrs.errorClass || 'submit-error';
                    $scope.hasError = false;

                    $scope.addError = function (msg) {
                        var error = { messages: [msg]};
                        $scope.errors.push(error);
                    };

                    $scope.addErrors = function (errors) {
                        $scope.errors = errors;
                    };
                },
                link: function (scope) {
                    scope.$on('service-validation:server-error', function (e, formName, response) {
                        if(formName === scope.form.$name){
                            console.log(response)
                            if (angular.isDefined(response.messages)) {
                                scope.addErrors(response.messages);
                            }
                            if (angular.isDefined(response.message)) {
                                scope.addError(response.message);
                            }
                            scope.show = true;
                        }
                    });

                    scope.$on('form-validation:submit', function () {
                        scope.errors = [];
                    });
                }
            };
        }]);
})(window, window.angular);
