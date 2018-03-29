SUMMARY

If the environment is specified with the `--env` option, it will cause an error if it is different from the setting value of `app.env`.

DESCRIPTION

Artisan command can specify the environment with the `--env` option, but it is a specification that ignores `--env` option when the configuration is cached.
Since this is potentially dangerous, this package adds the ability to check if the environment specified by the `--env` option is equal to the setting of `app.env`.

INSTALL

```console
composer install crhg/laravel-env-check
```
