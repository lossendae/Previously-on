'use strict';

define(['app'], function (app) {

    var start = function ($rootScope, $state, $stateParams) {
        $rootScope.$state = $state;
        $rootScope.$stateParams = $stateParams;

        $rootScope.$on('$stateChangeStart',
            function (ev, to, toParams, from, fromParams) {
            });

        $rootScope.$on('$stateNotFound',
            function (event, unfoundState, fromState, fromParams) {
            });

        $rootScope.$on('$stateChangeError',
            function (event, toState, toParams, fromState, fromParams, error) {
            });
    };

    app.run([
        '$rootScope',
        '$state',
        '$stateParams',
        start
    ]);
});

