# Extend Laravel scheduler to can config tasks via model

This package allows you to configure the scheduled tasks of the app via (database) model. It was developed to avoid handling these
configurations via a config file only, cause then we cannot share the same repo to n server instances when running different tasks is needed at each server.

# installation

```shell
composer require diontech/laravel-extended-scheduler
```

```shell
php artisan migrate
```

# using

At the moment you can do something similar to the following:

```php
        \DionTech\Scheduler\Models\ScheduledCommand::create([
            'method' => 'command',
            'arguments' => [
                'schedule:list'
            ],
            'frequency' => [
                'cron' => ['* * * * *']
            ],
            'is_active' => true
        ]);

        \DionTech\Scheduler\Models\ScheduledCommand::create([
            'method' => 'command',
            'arguments' => [
                'foo'
            ],
            'frequency' => [
                'weekdays',
                'hourly',
                'timezone' => ['America/Chicago'],
                'between' => ['8:00', '17:00']
            ],
            'is_active' => true
        ]);
        
        \DionTech\Scheduler\Models\ScheduledCommand::create([
            'method' => 'command',
            'arguments' => [
                'test:command',
                ['Taylor', '--force']
            ],
            'frequency' => [
                'daily'
            ],
            'is_active' => true
        ]);

        \DionTech\Scheduler\Models\ScheduledCommand::create([
            'method' => 'job',
            'arguments' => [
                'new \App\Jobs\TestJob', 'sqs'
            ],
            'frequency' => [
                'everyFiveMinutes'
            ],
            'is_active' => true
        ]);
```

See https://laravel.com/docs/8.x/scheduling to get an idea of how it can be used.

# NextSteps

- writing an API class
- make getCommands() method cacheable
- build some configs
