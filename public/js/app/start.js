'use strict';

define(['app'], function (app) {

    var start = function ($rootScope, $state, $stateParams, $window, authInterceptor, authService) {
        $rootScope.$state = $state;
        $rootScope.$stateParams = $stateParams;

        var attemptedState;
        var initialLoad = true;
        var checkSession = true;

        $rootScope.$on('$stateChangeStart',
            function (ev, to, toParams, from, fromParams) {
                if (to.name !== 'index.login' && initialLoad) {
                    attemptedState = { name: to.name, params: toParams };
                    initialLoad = false;

                    // Remove current session token
                    delete($window.sessionStorage['_token']);
                    $rootScope.$broadcast('event:auth-loginRequired');
                }

                if (to.name === 'index.login' && !angular.isDefined(attemptedState)) {
                    $rootScope.showSplash = false;
                }

                // Handle body css class if necessary
                if (angular.isDefined(to.data) && angular.isDefined(to.data.bodyClass)) {
                    $rootScope.bodyClass = to.data.bodyClass;
                } else {
                    $rootScope.bodyClass = '';
                }
            });

        $rootScope.$on('$stateNotFound',
            function (event, unfoundState, fromState, fromParams) {
            });

        $rootScope.$on('$stateChangeError',
            function (event, toState, toParams, fromState, fromParams, error) {
            });

        // Checking the user session does not use a token
        $rootScope.$on('event:auth-loginRequired', function (e, rejection) {
            // Request session only once per cycle
            if(checkSession){
                authService.check(function (check) {
                    if (!angular.isDefined(check.logged) || !check.logged) {

                        // Cancel pending request if any
                        authInterceptor.loginCancelled();

                        // Redirect to login...
                        $state.transitionTo('index.login');
                    } else {
                        updateToken();
                    }
                    checkSession = true;
                });
                checkSession = false;
            }
        });

        // Update the session token
        $rootScope.$on('event:auth-loginSuccess', function (e, response) {
            $rootScope.loggedUser = response.user;
            updateToken(false);
        });

        // Update the session token
        var updateToken = function(flag) {
            flag = angular.isDefined(flag) ? flag :  true;
            authService.token(function (response) {
                $window.sessionStorage['_token'] = response['_token'];

                // Resume pending request if any
                authInterceptor.loginConfirmed();

                sendForward();
                if (flag) {
                    updateSession();
                }
            });
        };

        // Resume user action
        var sendForward = function () {
            if (angular.isDefined(attemptedState)) {
                $state.transitionTo(attemptedState.name, attemptedState.params);
            } else {
                $state.transitionTo('index');
            }
        };

        // Update user session
        var updateSession = function () {
            authService.session(function (session) {
                $rootScope.loggedUser = session.user;
                $rootScope.showSplash = false;
            });
        };
    };

    app.run([
        '$rootScope',
        '$state',
        '$stateParams',
        '$window',
        'authInterceptor',
        'authService',
        start
    ]);
});

