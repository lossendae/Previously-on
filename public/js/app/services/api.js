'use strict';

define(['app'], function (app) {

    var ApiService = function ($resource, $cacheFactory) {

        var ApiTVShowsSearchCache = $cacheFactory('ApiTVShowsSearchCache', { capacity: 20 });
        var clearCache = function(response){
            if (response.status = 200 && response.data.success) {
                ApiTVShowsSearchCache.removeAll();
                $cacheFactory.get('TVShowsCache').removeAll();
            }
            return response.data;
        };

        return $resource('/api/remote/:id/:action',{},
            {
                'search': {
                    method: 'GET',
                    isArray : false,
                    cache: ApiTVShowsSearchCache,
                    params: {
                        action: 'search'
                    },
                    loadingIndicator : true
                },
                'put': {
                    method: 'PUT',
                    interceptor: {
                        response: function (response) {
                            return clearCache(response);
                        }
                    },
                    loadingIndicator : true
                }
            });
    };

    app.register.factory('ApiService', ['$resource','$cacheFactory', ApiService]);
});
