Previously-on
=============

A stand alone responsive TV Show manager using :

* Laravel
* Angular
* RequireJs
* Thetvdb API (lien)
* PhpThumb (lien)

## Requirements

This package require the Laravel framework and a MySQL database.

The following versions of PHP are supported by this version.

* PHP 5.4
* PHP 5.5
* PHP 5.6

## Install

Via Composer

``` json
"repositories": [
    {
        "url": "https://github.com/lossendae/Previously-on",
        "type": "git"
    }
],
{
    "require": {
        "lossendae/previously-on": "dev-master"
    }
}
```

### Config providers

In `app/config/app.php`
Add `Lossendae\PreviouslyOn\PreviouslyOnServiceProvider` to the end of the $providers array.

### Install command

``` bash
$ php artisan pvon:install
```

### Create the application admin

For now the system use the native user driver bundled with the framework.
Create any user you need via the CLI with access to all features.

``` bash
$ php artisan pvon:admin username email password
```

You can access to the login page : `http://your-url/`

Routes can be changed to your preference via the config file in `app/config/packages/lossendae/previously-on/config.php`.
The default route for the app require you to remove the default route from Laravel setup in `app/routes.php`.

### Update command

``` bash
$ php artisan pvon:update
```

## Todos before release

- [ ] Error message for API search
- [ ] Auto update show schedule
- [ ] Make it maintainable

## Todos

- [ ] Schedule page
- [ ] Improve interactions (buttons...)
- [ ] Multi language support
- [ ] Delete confirm modal
- [ ] Make it pluggable to 3rd party user management

## License

The MIT License (MIT). Please see [License File](https://github.com/thephpleague/fractal/blob/master/LICENSE) for more information.
