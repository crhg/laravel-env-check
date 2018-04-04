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

    /**
     *
     * @param string|null $command artisan command name (if running in console)
     * @throws \Exception
     */
    public function check($command)
    {
        if (!app()->configurationIsCached()) {
            return;
        }

        if (isset($commnad) && $this->isExcludedCommand($command)) {
            return;
        }

        $this->checkEnvironment();
        $this->checkDotEnvHash();
    }

    /**
     * @param string $command
     * @return bool
     */
    protected function isExcludedCommand(string $command)
    {
        return in_array($command, config('excluded_command', []));
    }

    /**
     * If --env option is specified, check specified environment is equals to app.env configuration.
     * If they are different, throws an exception
     *
     * @throws \Exception
     */
    protected function checkEnvironment()
    {
        $specified_env = $this->detectEnvironment();

        if (isset($specified_env) && $specified_env !== config('app.env')) {
            throw new \Exception(
                sprintf(
                    'env is specified but its different from current environment. (specified=%s, app.env=%s)',
                    $specified_env,
                    config('app.env')
                )
            );
        }
    }

    /**
     * Return environment string if it is specified by --env option or APP_ENV environment variable.
     * If it is not specified, return null.
     *
     * @return null|string
     */
    protected function detectEnvironment()
    {
        return $this->detectConsoleEnvironment() ?? $this->detectAppEnvEnvironment();
    }

    /**
     * Detect environment from environment variables
     *
     * If APP_ENV environment variable is set, return its value.
     * Otherwise, return null.
     *
     * @return string|null
     */
    protected function detectAppEnvEnvironment()
    {
        $env = \getenv('APP_ENV');
        return ($env !== false) ? $env : null;
    }

    /**
     * Detect environment from command-line arguments.
     *
     * If environment is specified by --env option, return it.
     * Otherwise, return null.
     *
     * @return string|null
     */
    protected function detectConsoleEnvironment()
    {
        if (!is_null($value = $this->getEnvironmentArgument())) {
            return head(array_slice(explode('=', $value), 1));
        }

        return null;
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
     * Check if check-sum of dot file is same as stored in config.
     *
     * Skip check if env.check.verify_env_hash is false.
     *
     * @throws \Exception
     */
    protected function checkDotEnvHash()
    {
        $saved_hash = config('env_check.dot_env_hash');
        if (is_null($saved_hash)) {
            return;
        }

        $hash = $this->dotEnvHash();
        if (is_null($hash)) {
            return;
        }

        if ($hash === $saved_hash) {
            return;
        }

        throw new \Exception(sprintf('dot env hash not match: file=%s', $this->dotEnvPath()));
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
        $env = $this->detectEnvironment();
        $ext = isset($env) ? ".$env" : "";
        return app()->environmentPath() . '/.env' . $ext;
    }
}