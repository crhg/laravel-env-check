# SUMMARY

If the environment is specified with the `--env` option or `APP_ENV` environment variable, it will cause an error if it is different from the setting value of `app.env`.

# DESCRIPTION

Laravel's environment can be sepcified with the `--env` option of artisan command or `APP_ENV` environment variable, but specified environment is ignored when the configuration is cached.

Since this is potentially dangerous, this package adds the ability to check if the environment specified by the `--env` option or `APP_ENV` envonvariable is equal to the setting of `app.env`.

# INSTALL

```console
composer install crhg/laravel-env-check
```

# EXAMPLE

If `local` environment is cached:

```console
% php artisan --env=testing migrate:status

In EnvCheckServiceProvider.php line 38:

  --env option is specified but its different from current environment. (opt=testing, app.env=local)
```