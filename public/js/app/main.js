require.config({
    urlArgs: 'v=1.0'
});
require(
    dependencies.files,
    function () {
        angular.bootstrap(document, ['app']);
    });
