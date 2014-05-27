'use strict';

define(['app'], function (app) {

    var onBlurChange = function ($parse) {
        return function (scope, element, attr) {
            var fn = $parse(attr['onBlurChange']);
            var hasChanged = false;
            element.on('change', function (event) {
                hasChanged = true;
            });

            element.on('blur', function (event) {
                if (hasChanged) {
                    scope.$apply(function () {
                        fn(scope, {$event: event});
                    });
                    hasChanged = false;
                }
            });
        };
    };

    var onEnterBlur = function () {
        return function (scope, element, attrs) {
            element.bind("keydown keypress", function (event) {
                element.triggerHandler("change");

                if (event.which === 13) {
                    element.triggerHandler("blur"); // Not using jQuery
                    event.preventDefault();
                }
            });
        };
    };

    app.register.directive('onBlurChange', ['$parse', onBlurChange]);
    app.register.directive('onEnterBlur', onEnterBlur);

});
