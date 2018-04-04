# 概要

設定がキャッシュされているとき、指定された環境と同じ環境のものであるかチェックします。

# 説明

## オプションや環境変数で指定された環境との整合性チェック

Laravelはartisanコマンドの`--env`オプションや`APP_ENV`環境変数で環境を指定できますが、
設定がキャッシュされているときはそれらの指定が無視されます。

これは潜在的に危険なので、--envオプションでやAPP_ENV環境変数で指定された環境とapp.envの設定値が
等しいかチェックする機能を追加します。異なっていた場合はエラーで終了させます。

## 生成時の.envファイルとの整合性チェック

キャッシュを生成するときに使用した.envファイルのチェックサムを保存し、現在の.envファイルのものと一致するか
チェックします。一致しない場合はエラーにします。

## 除外コマンド

artisanコマンドには環境に依存しないものもあるので、それらについてはチェック対象から除外します。

除外するコマンドを増やしたいときは、`config/env_check.php`の`excluded_command`に追加することが出来ます。

# インストール

```console
composer require crhg/laravel-env-check
php artisan vendor:publish --provider='Crhg\EnvCheck\Providers\EnvCheckServiceProvider'
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