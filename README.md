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

## model based handling
At the moment you can do something similar to the following:

```php
        \DionTech\Scheduler\Models\ScheduledCommand::create([
            'method' => 'command',
            'arguments' => [
                'schedule:list'
            ],
            'fluent' => [
                'cron' => ['* * * * *']
            ],
            'is_active' => true
        ]);

        \DionTech\Scheduler\Models\ScheduledCommand::create([
            'method' => 'command',
            'arguments' => [
                'foo'
            ],
            'fluent' => [
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
            'fluent' => [
                'daily'
            ],
            'is_active' => true
        ]);

        \DionTech\Scheduler\Models\ScheduledCommand::create([
            'method' => 'job',
            'arguments' => [
                'new \App\Jobs\TestJob', 'sqs'
            ],
            'fluent' => [
                'everyFiveMinutes'
            ],
            'is_active' => true
        ]);
```

See https://laravel.com/docs/8.x/scheduling to get an idea of how it can be used.

## solve normilize inserted model to readable structure 

```php

        $model = \DionTech\Scheduler\Models\ScheduledCommand::create([
            'method' => 'command',
            'arguments' => [
                'foo'
            ],
            'fluent' => [
                'weekdays',
                'hourly',
                'timezone' => ['America/Chicago'],
                'between' => ['8:00', '17:00']
            ],
            'is_active' => true
        ]);
       
    $event = $model->event(); //returns \Illuminate\Console\Scheduling\Event
    $command = $event->command; //something like "/usr/local/Cellar/php@7.4/7.4.16/bin/php' 'artisan' foo"
    $expression = $event->rexpression; //something like "0 * * * 1-5"
    $description = $event->description; //something like "new \App\Jobs\TestJob"
```

# NextSteps

- writing an API class
- make getCommands() method cacheable
- build some configs
