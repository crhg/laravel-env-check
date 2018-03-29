# 概要

artisanの`--env`オプションまたは`APP_ENV`環境変数で環境が指定されているとき、指定された環境と`app.env`の設定値が異なればエラーにします。

# 説明

Laravelはartisanコマンドの`--env`オプションや`APP_ENV`環境変数で環境を指定できますが、
設定がキャッシュされているときはそれらの指定が無視されます。

これは潜在的に危険なので、--envオプションでやAPP_ENV環境変数で指定された環境とapp.envの設定値が
等しいかチェックする機能を追加します。異なっていた場合はエラーで終了させます。

# インストール

```console
composer install crhg/laravel-env-check
```

# 例

`local`環境がキャッシュされているとき:

```console
% php artisan --env=testing migrate:status

In EnvCheckServiceProvider.php line 38:

  env is specified but its different from current environment. (specified=testing, app.env=local)


% APP_ENV=testing php artisan migrate:status

In EnvCheckServiceProvider.php line 38:

  env is specified but its different from current environment. (specified=testing, app.env=local)


```