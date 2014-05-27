<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="A website to manage your TVShows">

    <title>Previously.io - TV Shows manager</title>

    <link rel="shortcut icon" href="images/favicon.ico">
    {{ HTML::style('css/normalize.css') }}
    {{ HTML::style('css/ui-kit.css') }}
    {{ HTML::style('css/style.css') }}
    {{ HTML::style('css/font-awesome.min.css') }}

    <!--[if lt IE 9]>
    <script src="js/html5.js"></script>
    <![endif]-->
</head>
<body class="no-js">
    <header>
        <div class="wrapper">
            <a data-ui-sref="index.ajouter-serie" class="top-action add-series" data-ng-class="{ active : $state.is('index') }">
                Ajouter série<i class="fa fa-plus"></i>
            </a>

            <a data-ui-sref="index" class="top-action cancel-add-series" data-ng-class="{ active : $state.is('index.ajouter-serie') }">
                <i class="fa fa-times"></i> Pas maintenant
            </a>

            <a data-ui-sref="index" class="top-action" data-ng-class="{ active : $state.is('index.gestion-serie') }">
                <i class="fa fa-times"></i> Retour liste
            </a>

            <h1><a data-ui-sref="index">Previously.io</a></h1>
        </div>
    </header>

     <section id="main" ui-view="main"></section>
    <!-- /#main -->

    <!-- 3rd party libraries -->
    {{ HTML::script('js/bower_components/angular/angular.js') }}
    {{ HTML::script('//ajax.googleapis.com/ajax/libs/angularjs/1.2.8/angular-animate.js') }}
    {{ HTML::script('js/bower_components/angular-ui-router/release/angular-ui-router.js') }}
    {{ HTML::script('js/bower_components/angular-resource/angular-resource.js') }}
    {{ HTML::script($libsPath . 'loading-bar.js') }}
    {{ HTML::script('js/app/libs/spin.js') }}
    {{ HTML::script('js/app/libs/loading-spinner.js') }}

    <!-- Commons - Inline until a better solution has been found -->
    <script type="text/javascript">
        'use strict';

        var commons = function () {
            this.$get = function () {
                return this;
            };

            this.path = function (key) {
                var paths = {{ json_encode($config['paths']) }};
                return paths[key];
            };
        };

        var appCommons = angular.module('commons', [])
            .provider('commons', commons);
    </script>

    <script type="text/javascript">
        var dependencies = {{ json_encode($dependencies) }};
    </script>

    <!-- Load custom scripts via RequireJS -->
    <script src="{{ asset('js/bower_components/requirejs/require.js') }}" data-main="{{ asset('js/app/main.js') }}"></script>
</body>
</html>




