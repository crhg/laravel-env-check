<?php
/**
 * Created by IntelliJ IDEA.
 * User: matsui
 * Date: 2018/04/03
 * Time: 13:08
 */

namespace Crhg\EnvCheck;


use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class EnvChecker
{
    // command name or command group(end with ':')
    protected static $default_exclude_command = [
        "clear-compiled",
        "help",
        "list",
        "optimize",
        "phpcs",
        "preset",
        "config:",
        "make:",
        "package:discover",
        "phpcs:fix",
        "route:",
        "vendor:publish",
    ];

    protected $is_app_env_specified = false;

    /**
     *
     * @param string|null $command artisan command name (if running in console)
     * @throws \Exception
     */
    public function check($command)
    {
        if ($command === 'config:cache') {
            if ($this->isEnvOptionSpecified()) {
                throw new \Exception("Don't use --env option with config:cache");
            }
            if ($this->is_app_env_specified) {
                throw new \Exception("Don't set APP_ENV environment variable with config:cache");
            }

            return;
        }

        if (!app()->configurationIsCached()) {
            return;
        }

        if (isset($command) && $this->isExcludedCommand($command)) {
            return;
        }

        if ($this->isEnvOptionSpecified()) {
            throw new \Exception("Don't use --env option when configuration is cached");
        }

        if ($this->is_app_env_specified) {
            throw new \Exception("Don't set APP_ENV environment variable when configuration is cached");
        }

        if (!$this->checkDotEnvHash()) {
            throw new \Exception('.env hash unmatch');
        }
    }

    /**
     * @param string $command
     * @return bool
     */
    protected function isExcludedCommand(string $command)
    {
        foreach (array_merge(config('env_check.excluded_command', []), self::$default_exclude_command) as $e) {
            if (ends_with(':', $e)) {
                if (starts_with($e, $command)) {
                    return true;
                }
            } else {
                if ($command === $e) {
                    return true;
                }
            }
        }
        return false;
    }

    protected function isEnvOptionSpecified()
    {
        return !is_null(
            Arr::first(
                $_SERVER['argv'],
                function ($value) {
                    return Str::startsWith($value, '--env');
                }
            )
        );
    }

    /**
     * Get the environment argument from the console.
     *
     * XXX: This function is a copy of Illuminate\Foundation\EnvironmentDetector::getEnvironmentArgument().
     *      It is undesirable to copy it, but it is inevitable because it is a protected function.
     *
     * @param  array $args
     * @return string|null
     */
    protected function getEnvironmentArgument(array $args = null)
    {
        $args = $args ?? $_SERVER['argv'];

        return Arr::first($args, function ($value) {
            return Str::startsWith($value, '--env');
        });
    }

    /**
     * Examine if APP_ENV environment variable is set and save result
     */
    public function examineEnvironmentVariables()
    {
        $this->is_app_env_specified = \getenv('APP_ENV') !== false;
    }

    /**
     * Check if check-sum of dot file is same as stored in config.
     *
     * Skip check if env.check.verify_env_hash is false.
     *
     * @return bool true if check is OK
     */
    protected function checkDotEnvHash()
    {
        $saved_hash = config('env_check.dot_env_hash');
        if (is_null($saved_hash)) {
            return true;
        }

        $hash = $this->dotEnvHash();
        if (is_null($hash)) {
            return true;
        }

        if ($hash === $saved_hash) {
            return true;
        }

        return false;
    }

    /**
     * Calculate hash of env-file.
     *
     * If env-file is not exists, return null.
     *
     * @return null|string
     */
    public function dotEnvHash()
    {
        $dot_env_path = $this->dotEnvPath();
        if (!(isset($dot_env_path) && file_exists($dot_env_path))) {
            return null;
        }

        return md5_file($dot_env_path);
    }

    protected function dotEnvPath()
    {
        return app()->environmentPath() . '/.env';
    }
}