/**
 * Notifier for AngularJS
 * @version v0.1.0
 * @link http://
 * @license MIT License, http://www.opensource.org/licenses/MIT
 */

(function (window, angular, undefined) {
    'use strict';

    var $el = angular.element,
        $isDefined = angular.isDefined,
        $isObject = angular.isObject,
        $isString = angular.isString,
        $extend = angular.extend,
        $copy = angular.copy;

    angular.module('adminNotifier', [])

        .service('adminNotifications', ['$injector', '$document', '$templateCache', '$rootScope', '$timeout',
            function ($injector, $document, $templateCache, $rootScope, $timeout) {

                var $compile, $q, $http,
                    $body = $document.find('body'),
                    self = this,
                    scope,
                    options,
                    incrementedIndex = 0,
                    defaults = {
                        template: '<div class="notif-container">' +
                            '<section ng-repeat="msg in messages" id="{{ msg.id }}" class="notif notif-{{ msg.flag }} notif-opening" ng-click="msg.remove()" ng-mouseover="msg.stopTimer()" ng-mouseleave="msg.startTimer()">' +
                            '<h6 ng-if="msg.title" class="notif-title">{{ msg.title }}</h6>' +
                            '<span ng-if="!msg.title" class="notif-title"></span>' +
                            '{{ msg.content }}' +
                            '</section>' +
                            '</div>',
                        newestOnTop: true,
                        timeOut: 5000,
                        delayAfterOpening: 500,
                        delayAfterClosing: 500,
                        idPrefix: 'notif-'
                    };

                this.init = function (opts) {
                    // Inject those dependencies here from $injector to avoid circular reference in httpProvider -> Interceptor
                    $compile = $compile || $injector.get("$compile"),
                        $q = $q || $injector.get("$q"),
                        $http = $http || $injector.get("$http");

                    if ($isObject(scope)) {
                        options.message = [];
                        return options;
                    }

                    options = $copy(defaults);

                    opts = opts || {};
                    $extend(options, opts);

                    scope = $isObject(options.scope) ? options.scope : $rootScope.$new();
                    scope.messages = [];
                    var $notifications;

                    $q.when(this.loadContainer(options.template)).then(function (template) {
                        $notifications = $el(template);

                        if (options.controller && angular.isString(options.controller)) {
                            $el.attr('ng-controller', options.controller);
                        }

                        scope.$on('$destroy', function () {
                            $notifications.remove();
                        });

                        $body.append($notifications);

                        scope.$watch('messages', function () {
                            $compile($notifications)(scope);
                        });
                    });

                    return options;
                };

                this.loadContainer = function (tmpl) {
                    if (!tmpl) {
                        return 'Empty template';
                    }

                    if ($isString(tmpl)) {
                        return tmpl;
                    }

                    return $templateCache.get(tmpl) || $http.get(tmpl, { cache: true });
                };

                this.add = function (message) {
                    incrementedIndex += 1;

                    message.idx = incrementedIndex;
                    message.id = options.idPrefix + incrementedIndex;
                    message.remove = function () {
                        self.remove(message);
                    };
                    this.setTimeout(message);

                    options.newestOnTop ? scope.messages.unshift(message) : scope.messages.push(message);

                    $timeout(function () {
                        var addedMessages = $el(document.getElementsByClassName('notif-opening'));
                        addedMessages.addClass('notif-opened').removeClass('notif-opening');
                    }, options.delayAfterOpening);
                };

                this.setTimeout = function (message) {
                    message.startTimer = function () {
                        message.timeout = $timeout(function () {
                            self.remove(message);
                        }, options.timeOut);
                    };

                    message.stopTimer = function () {
                        $timeout.cancel(message.timeout);
                    };

                    message.startTimer();
                };

                this.remove = function (msg) {
                    var $message = $el(document.getElementById(msg.id));
                    $message.addClass('notif-closing');

                    this.cleanup(msg.idx, $message);
                };

                this.cleanup = function (index, $message) {
                    $timeout(function () {
                        var idx = 0;
                        for (idx; idx < scope.messages.length; idx++) {
                            if (scope.messages[idx].idx === index)
                                break;
                        }
                        scope.messages.splice(idx, 1);
                        $message.remove();
                    }, options.delayAfterClosing);
                };
            }
        ])

        .service('adminNotifier', ['adminNotifications', function (adminNotifications) {
            this.notify = function (response) {
                var opts = {}, cfg = {};

                cfg.flag = $isDefined(response.success) && typeof(response.success) === "boolean" ?
                    response.success ? 'succeed' : 'alert' :
                    'warn';

                cfg.content = $isDefined(response.message) && $isString(response.message) ?
                    response.message : 'No message to show!';

                cfg.title = $isDefined(response.title) && $isString(response.title) ?
                    response.title : '';

                opts.message = cfg;

                this.make(opts);
            };

            this.make = function (opts) {
                var options = adminNotifications.init(opts);
                $extend(options, opts);

                adminNotifications.add(options.message);
            };

            this.remove = function (msg) {
                adminNotifications.remove(msg);
            };
        }])

        .config(['$httpProvider', 'commonsProvider', function ($httpProvider, commonsProvider) {
            var interceptor = ['$q', 'adminNotifier', function ($q, adminNotifier) {
                var debugMode = commonsProvider.debug;

                return {
                    responseError: function (rejection) {
                        if (debugMode) {
                            // Format the output for notifier
                            var notifyResponse = {
                                success: false,
                                title: 'Request Rejected : ' + rejection.status,
                                message: rejection.data.error.message
                            };
                            adminNotifier.notify(notifyResponse);
                        }

                        // Return the promise rejection.
                        return $q.reject(rejection);
                    }
                };
            }];

            $httpProvider.interceptors.push(interceptor);
        }]);

})(window, window.angular);
