<?php
/**
 * Created by IntelliJ IDEA.
 * User: matsui
 * Date: 2018/04/03
 * Time: 9:49
 */
use Crhg\EnvCheck\EnvChecker;

return [
    // Additional list of artisan commands which should not be checked
    // If specified string ends with ':', it is treated as a command group.
    'excluded_command' => [
    ],

    // This is to save the hash value of the current env file.
    // Skip hash check if this is set to null.
    'dot_env_hash' => with(
        app()->make(EnvChecker::class),
        function (EnvChecker $checker) {
            return $checker->dotEnvHash();
        }),
];