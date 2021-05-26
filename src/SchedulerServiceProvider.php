<?php


namespace DionTech\Scheduler;


use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

class SchedulerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            //$schedule->command('some:command')->everyMinute();
            dump('hey ho');
        });
    }
}
