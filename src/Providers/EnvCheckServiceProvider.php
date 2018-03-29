<?php
/**
 * Created by IntelliJ IDEA.
 * User: matsui
 * Date: 2018/03/29
 * Time: 9:06
 */

namespace Crhg\EnvCheck\Providers;


use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class EnvCheckServiceProvider extends ServiceProvider
{
    /**
     * @throws \Exception
     */
    public function boot()
    {
        $this->checkEnvironment($_SERVER['argv'], $_ENV);
    }

    /**
     * If --env option is specified, check specified environment is equals to app.env configuration.
     * If they are different, throws an exception.
     *
     * @param array $args
     * @param array $env
     * @throws \Exception
     */
    protected function checkEnvironment(array $args, array $env)
    {
        $specified_env = $this->detectConsoleEnvironment($args) ?? $this->detectAppEnvEnvironment($env);

        if (isset($specified_env) && $specified_env !== config('app.env')) {
            throw new \Exception(
                sprintf(
                    '--env option is specified but its different from current environment. (specified=%s, app.env=%s)',
                    $specified_env,
                    config('app.env')
                )
            );
        }
    }

    /**
     * Detect environment from environment variables
     *
     * @param array $env
     * @return mixed|null
     */
    protected function detectAppEnvEnvironment(array $env)
    {
        return $env['APP_ENV'] ?? null;
    }

    /**
     * Detect environment from command-line arguments.
     *
     * @param  array  $args
     * @return string|null
     */
    protected function detectConsoleEnvironment(array $args)
    {
        if (! is_null($value = $this->getEnvironmentArgument($args))) {
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
     * @param  array  $args
     * @return string|null
     */
    protected function getEnvironmentArgument(array $args)
    {
        return Arr::first($args, function ($value) {
            return Str::startsWith($value, '--env');
        });
    }
}