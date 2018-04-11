# 概要

設定のキャッシュと環境に関するチェックを強化します

# 説明

## 設定がキャッシュされているときの明示的な環境の指定の禁止

Laravelはartisanコマンドの`--env`オプションや`APP_ENV`環境変数で環境を指定できますが、
設定がキャッシュされているときはそれらの指定が無視されます。

これは潜在的に危険なので、設定がキャッシュされているときは`--env`オプションや`APP_ENV`環境変数による環境の指定を禁止します。

## config:cacheコマンドへの環境の指定の禁止

`config:cache`コマンドにより設定のキャッシュを生成するときに、`--env`オプションや`APP_ENV`環境変数による環境の指定を禁止します。
従って、キャッシュできるのは`.env`ファイルに記述されたデフォルトの環境のみとなります。

## 生成時の.envファイルとの整合性チェック

キャッシュを生成するときに使用した`.env`ファイルのチェックサムを保存し、現在の`.env`ファイルのものと一致するか
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