SUMMARY

--envオプションで環境が指定されているとき、それがapp.envの設定値と異なればエラーにします。

DESCRIPTION

Laravelのartisanコマンドは--envオプションで環境を指定できますが、
設定がキャッシュされているときは--envオプションの指定が無視される仕様です。

これは潜在的に危険なので、--envオプションで指定された環境とapp.envの設定値が
等しいかチェックする機能を追加します。

INSTALL

```console
composer install crhg/laravel-env-check
```

