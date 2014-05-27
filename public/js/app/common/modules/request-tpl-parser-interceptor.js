/**
 * Admin url interpolate for AngularJS
 * @version v0.1.0
 * @link http://
 * @license MIT License, http://www.opensource.org/licenses/MIT
 */

(function (window, angular, undefined) {
    'use strict';

    var adminRequestInterceptor = function ($httpProvider, commonsProvider) {
        var interceptor = ['$q', '$interpolate', function ($q, $interpolate) {

            return {
                request: function (config) {
                    // Contains the data about the request before it is sent.
                    // console.log(config);

                    // Use a dynamic base url for xhr request if needed
                    var strEnd = config.url.split('.').pop();
                    if (strEnd !== 'html') {
                        config.url = $interpolate(config.url)(commonsProvider.config());
                    }

                    // Return the config or wrap it in a promise if blank.
                    return config || $q.when(config);
                }
            };
        }];

        $httpProvider.interceptors.push(interceptor);
    };

    angular.module('adminRequestInterceptor', [])
        .config(['$httpProvider', 'commonsProvider', adminRequestInterceptor]);

})(window, window.angular);
