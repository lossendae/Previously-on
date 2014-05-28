/**
 * @ngdoc directive
 * @name ng.directive:rcVerifySet
 * @source https://github.com/realcrowd/angularjs-utilities/blob/master/src/directives/rcVerifySet.js
 *
 * @description
 * Used to verify the scope is updated from the view. This need arose out
 * of some browser plugins (namely Password Managers), manipulate the DOM
 * and do not necessarily fire the events that angular list to by default.
 * Using this method the values are pushed to the scope before submission.
 * before submit.
 *
 * @element ANY
 */

'use strict';

define(['app'], function (app) {

    var autofill = function ($timeout) {
        return {
            restrict: 'A',
            require: 'ngModel',
            link: function (scope, element, attrs, ngModel) {
                scope.$on('autofill-submit', function(e) {
                    ngModel.$setViewValue(element.val());
                });

                // Timeout is evil but this is much more user friendly
                angular.element(document).ready(function () {
                    $timeout(function() {
                        ngModel.$setViewValue(element.val());
                        // We tell the current controller that validation can be used (for required fields)
                        scope.validationReady = true;
                    }, 500);
                });
            }
        };
    };

    var autofillSubmit = function($parse) {
        return {
            link: function (scope, element) {
                var fn = $parse(element.attr('autofill-submit'));
                element.on('submit', function(event) {
                    scope.$broadcast('autofill-submit');
                    scope.$apply(function() {
                        fn(scope, {$event:event});
                    });
                });
            }
        }
    };

    app.register.directive('autofill', ['$timeout', autofill]);
    app.register.directive('autofillSubmit', ['$parse', autofillSubmit]);

});
