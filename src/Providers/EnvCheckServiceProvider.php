<?php
/**
 * Created by IntelliJ IDEA.
 * User: matsui
 * Date: 2018/03/29
 * Time: 9:06
 */

namespace Crhg\EnvCheck\Providers;


use Crhg\EnvCheck\EnvChecker;
use Illuminate\Support\ServiceProvider;

class EnvCheckServiceProvider extends ServiceProvider
{
    /**
     * @throws \Exception
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/env_check.php' => config_path('env_check.php'),
        ]);

        $this->check();
    }

    /**
     *
     */
    public function register()
    {
        $this->app->bind(EnvChecker::class);
    }

    /**
     *
     */
    protected function check()
    {
        $checker = $this->app->make(EnvChecker::class);

        $checker->checkEnvironment($_SERVER['argv']);
        $checker->checkDotEnvHash();
    }
}