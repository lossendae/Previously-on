Previously-on
=============

A stand alone responsive TV Show manager using :

* Laravel
* Angular
* RequireJs
* Thetvdb API (lien)
* PhpThumb (lien)

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

The first user will be added to the “Admin” group, to allow you an access to all features and the admin dashboard.

``` bash
$ php artisan pvon:admin username email password
```

You can access to the login page : `http://your-url/`
Or the admin page : `http://your-url/admin`

Both routes can be changed to your preference via the config file in `app/config/packages/lossendae/previously-on/config.php`.

### Update command

``` bash
$ php artisan pvon:update
```

## Requirements

This package require the Laravel framework and a MySQL database.

The following versions of PHP are supported by this version.

* PHP 5.3
* PHP 5.4
* PHP 5.5
* PHP 5.6

## Todo before release

- [ ] Auth implementation using Sentry
- [ ] Error message for API search
- [ ] Delete confirm modal
- [ ] Table refactor for multi user usage
- [ ] Auto update show schedule

## Todo

- [ ] Schedule page
- [ ] Improve interactions (buttons & responsive behaviours)
- [ ] Multi language support

## License

The MIT License (MIT). Please see [License File](https://github.com/thephpleague/fractal/blob/master/LICENSE) for more information.
