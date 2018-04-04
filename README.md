# SUMMARY

When the configuration is cached, this package checks whether it is in the same environment as the specified environment.

# DESCRIPTION

## Check consistency with the environment specified by `--env` option or `APP_ENV` environment variable

Laravel's environment can be sepcified with the `--env` option of artisan command or `APP_ENV` environment variable, but specified environment is ignored when the configuration is cached.

Since this is potentially dangerous, this package adds the ability to check if the environment specified by the `--env` option or `APP_ENV` envonvariable is equal to the setting of `app.env`.

## Check consistency with .env file

Saves the checksum of the .env file when the config cache is generated.
It is checked whether it matches that of the current .env file.
If they do not match, an error will occur.

## Commands to be excluded from check

Some artisan commands do not depend on the environment, so exclude them from checking.

If you want to add some commands to exclude, use the `excluded_command` setting in `config/env_check.php`.

# INSTALL

```console
composer require crhg/laravel-env-check
php artisan vendor:publish --provider='Crhg\EnvCheck\Providers\EnvCheckServiceProvider'
```

# EXAMPLE

If `local` environment is cached:

```console
% php artisan --env=testing migrate:status

In EnvCheckServiceProvider.php line 38:

  env is specified but its different from current environment. (specified=testing, app.env=local)


% APP_ENV=testing php artisan migrate:status

In EnvCheckServiceProvider.php line 38:

  env is specified but its different from current environment. (specified=testing, app.env=local)

```