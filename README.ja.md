# 概要

--envオプションで環境が指定されているとき、それがapp.envの設定値と異なればエラーにします。

# 説明

Laravelのartisanコマンドは--envオプションで環境を指定できますが、
設定がキャッシュされているときは--envオプションの指定が無視される仕様です。

これは潜在的に危険なので、--envオプションで指定された環境とapp.envの設定値が
等しいかチェックする機能を追加します。

# インストール

```console
composer install crhg/laravel-env-check
```

# 例

`local`環境がキャッシュされているとき:

```console
% php artisan --env=testing migrate:status

In EnvCheckServiceProvider.php line 38:

  --env option is specified but its different from current environment. (opt=testing, app.env=local)
```