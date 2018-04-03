<?php
/**
 * Created by IntelliJ IDEA.
 * User: matsui
 * Date: 2018/04/03
 * Time: 9:49
 */
use Crhg\EnvCheck\EnvChecker;

return [
    'verify_hash' => env('ENV_CHECK_VERIFY_HASH', true),
    'dot_env_hash' => app()->make(EnvChecker::class)->hashDotEnv(),
];