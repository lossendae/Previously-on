<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="A website to manage your TVShows">

    <title>Previously On - Gestionnaire de séries</title>

    <link rel="shortcut icon" href="images/favicon.ico">
    {{ HTML::style($cssPath . 'normalize.css') }}
    {{ HTML::style($cssPath . 'ui-kit.css') }}
    {{ HTML::style($cssPath . 'style.css') }}
    {{ HTML::style($cssPath . 'font-awesome.min.css') }}

    <!--[if lt IE 9]>
    {{ HTML::script($libsPath . 'html5.js') }}
    <![endif]-->
</head>
<body class="no-js @{{ bodyClass }}">
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

            <a href="" class="logout top-action" data-log-out>
                <i class="fa fa-power-off"></i>
            </a>

            <h1><a data-ui-sref="index">Previously On</a></h1>

            <div class="splash" ui-view="auth"></div>
        </div>
    </header>

     <section id="main" ui-view="main"></section>
    <!-- /#main -->

    <!-- 3rd party libraries -->
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/angular.js/1.2.16/angular.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/angular.js/1.2.16/angular-animate.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/angular-ui-router/0.2.10/angular-ui-router.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/angular.js/1.2.16/angular-resource.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/spin.js/2.0.0/spin.min.js') }}

    {{ HTML::script($libsPath . 'loading-bar.js') }}
    {{ HTML::script($libsPath . 'loading-spinner.js') }}


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
    <script src="//cdnjs.cloudflare.com/ajax/libs/require.js/2.1.11/require.min.js" data-main="{{ asset($appPath . 'main.js') }}"></script>
</body>
</html>




