'use strict';

define(['app'], function (app) {

    var DataService = function ($resource, $cacheFactory) {

        var TVShowsCache = $cacheFactory('TVShowsCache', { capacity: 1 });
        var ManageCache = $cacheFactory('ManageCache', { capacity: 20 });
        var clearCache = function(response){
            if (response.status = 200 && response.data.success) {
                TVShowsCache.removeAll();
                ManageCache.removeAll();
            }
            return response.data;
        };

        return $resource('/api/manage/:id/:action',{},
            {
                'listTvShows': {
                    method: 'GET',
                    isArray : false,
                    cache: TVShowsCache,
                    params: {
                        action: 'list'
                    },
                    loadingIndicator : true
                },
                'listEpisodes': {
                    method: 'GET',
                    isArray : false,
                    cache: ManageCache,
                    loadingIndicator : true
                },
                'updateEpisodeStatus': {
                    method: 'PUT',
                    interceptor: {
                        response: function (response) {
                            return clearCache(response);
                        }
                    }
                },
                'removeTvShow': {
                    method: 'DELETE',
                    interceptor: {
                        response: function (response) {
                            return clearCache(response);
                        }
                    },
                    loadingIndicator : true
                }
            });
    };

    app.register.factory('DataService', ['$resource','$cacheFactory', DataService]);
});
