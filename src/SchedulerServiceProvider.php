<?php


namespace DionTech\Scheduler;


use DionTech\Scheduler\Contracts\ScheduledCommandRepositoryContract;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use DionTech\Scheduler\Repositories\ScheduledCommandRepository;

class SchedulerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/scheduler.php', 'scheduler');

        $this->app->bind(ScheduledCommandRepositoryContract::class, function() {
            $class = config('scheduler.driver');
            return new $class;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/scheduler.php' => config_path('scheduler.php'),
        ], 'config');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            app()->get(ScheduledCommandRepositoryContract::class)->getCommands($schedule);
        });
    }
}
