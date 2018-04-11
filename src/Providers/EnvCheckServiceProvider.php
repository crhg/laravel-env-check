<?php
/**
 * Created by IntelliJ IDEA.
 * User: matsui
 * Date: 2018/03/29
 * Time: 9:06
 */

namespace Crhg\EnvCheck\Providers;


use Crhg\EnvCheck\EnvChecker;
use Crhg\EnvCheck\Exceptions\EnvCheckErrorException;
use Illuminate\Console\Events\CommandStarting;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;

class EnvCheckServiceProvider extends ServiceProvider
{
    /**
     * @throws \Exception
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/env_check.php' => config_path('env_check.php'),
        ]);

        if (app()->runningInConsole()) {
            $dispatcher = app()->make(Dispatcher::class);
            $dispatcher->listen(CommandStarting::class,
                function (CommandStarting $event) {
                    try {
                        $this->check($event->command);
                    } catch (EnvCheckErrorException $e) {
                        $event->output->writeln($e->getMessage());
                        exit(1);
                    }
                });
        } else {
            $this->check();
        }
    }

    /**
     * @param string|null $command artisan command name (if running in console)
     * @throws EnvCheckErrorException
     */
    protected function check($command = null)
    {
        /** @var EnvChecker $checker */
        $checker = $this->app->make(EnvChecker::class);
        $checker->check($command);
    }
}