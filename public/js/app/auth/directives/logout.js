'use strict';

define(['app', 'app/common/directives/mainMenu'], function (app) {

    var logOut = function(authService) {
        return function (scope, element) {
            element.bind('click', function(){
                authService.logOut();
            });
        }
    };

    app.directive('logOut', ['authService', logOut]);
});
