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
        $this->checkEnvironmentArgument($_SERVER['argv']);
    }

    /**
     * If --env option is specified, check specified environment is equals to that of application.
     * If they are different, throws an exception.
     *
     * @param array $args
     * @throws \Exception
     */
    protected function checkEnvironmentArgument(array $args)
    {
        $env_arg = $this->detectConsoleEnvironment($args);

        if (isset($env_arg) && $env_arg !== $this->app->environment()) {
            throw new \Exception(
                sprintf(
                    '--env option is specified but its different from current environment. (opt=%s, cur=%s)',
                    $env_arg,
                    $this->app->environment()
                )
            );
        }
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