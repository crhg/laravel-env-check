# SUMMARY

Enhance checking of configuration cache and environment.

# DESCRIPTION

## Prohibit designation of an explicit environment when configuration is cached

Laravel's environment can be sepcified with the `--env` option of artisan command or `APP_ENV` environment variable, but specified environment is ignored when the configuration is cached.

Since this is potentially dangerous, when configuration is cached, it is prohibited to specify the environment with `--env` option or` APP_ENV` environment variable.

## Prohibit designation of environment for `config:cache` command

When generating the configuration cache with the `config: cache` command, prohibit specification of the environment with the` --env` option or the `APP_ENV` environment variable.
Therefore, only the default environment described in the `.env` file can be cached.

## Check consistency with `.env` file

Saves the checksum of the `.env` file when the config cache is generated.
It is checked whether it matches that of the current `.env` file.
If they do not match, an error will occur.

## Commands to be excluded from check

Some artisan commands do not depend on the environment, so exclude them from checking.

If you want to add some commands to exclude, use the `excluded_command` setting in `config/env_check.php`.

# INSTALL

```console
composer require crhg/laravel-env-check
php artisan vendor:publish --provider='Crhg\EnvCheck\Providers\EnvCheckServiceProvider'
```

Add the following code before `return $app` in `bootstrap/app.php`;

```php
        $app->singleton(\Crhg\EnvCheck\EnvChecker::class);

        $app->beforeBootstrapping(
            \Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables::class,
            function ($app) {
                $checker = $app->make(\Crhg\EnvCheck\EnvChecker::class);
                $checker->examineEnvironmentVariables();
            }
        );
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