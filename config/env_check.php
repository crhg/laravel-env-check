<?php
/**
 * Created by IntelliJ IDEA.
 * User: matsui
 * Date: 2018/04/03
 * Time: 9:49
 */
use Crhg\EnvCheck\EnvChecker;

return [
    // List of artisan commands which should not be checked
    'excluded_command' => [
        // List command is used for completion with the laravel5 plugin of oh-my-zsh.
        'list',

        // These commands should be usable regardless of cache state
        'config:cache',
        'config:clear',
    ],

    // This is to save the hash value of the current env file. Don't touch.
    'dot_env_hash' => with(
        app()->make(EnvChecker::class),
        function (EnvChecker $checker) {
            return $checker->dotEnvHash();
        }),
];