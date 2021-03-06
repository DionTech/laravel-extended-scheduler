[![Latest Version](https://img.shields.io/packagist/v/diontech/laravel-extended-scheduler?label=version)](https://packagist.org/packages/diontech/laravel-extended-scheduler/)
[![run-tests](https://github.com/DionTech/laravel-extended-scheduler/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/DionTech/laravel-extended-scheduler/actions/workflows/run-tests.yml)
![GitHub last commit](https://img.shields.io/github/last-commit/diontech/laravel-extended-scheduler)
![GitHub issues](https://img.shields.io/github/issues-raw/diontech/laravel-extended-scheduler)
[![Packagist Downloads](https://img.shields.io/packagist/dm/diontech/laravel-extended-scheduler.svg?label=packagist%20downloads)](https://packagist.org/packages/diontech/laravel-extended-scheduler)
[![License](https://img.shields.io/badge/license-mit-blue.svg)](https://github.com/diontech/laravel-extended-scheduler/blob/main/LICENSE.md)
![Twitter Follow](https://img.shields.io/twitter/follow/dion_tech?style=social)

# Extend Laravel scheduler to can config tasks via model

This package allows you to configure the scheduled tasks of the app via (database) model. It was developed to avoid handling these
configurations via a config file only, cause then we cannot share the same repo to n server instances when running different tasks is needed at each server.

This package will extend the laravel scheduler, so all coded scheduled tasks will still be available.

# releases / laravel support

- laravel 8: v1.2.x
- laravel 9: v1.3.x

# installation

```shell
composer require diontech/laravel-extended-scheduler
```

```shell
php artisan migrate
```

# using

## Facade based handling

Instead of used model based handling mentioned below, you can also do Facade based handling, like:

```php 
\DionTech\Scheduler\Support\Facades\ScheduledCommand\ScheduledCommand::arguments([
    'foo'
])->fluent([
    'cron' => [
        '* * * * *'
    ]
])->isActive()
->create();
```

## Model based handling
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

### normalizing inserted model to readable structure 

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
    $expression = $event->expression; //something like "0 * * * 1-5"
    $description = $event->description; //something like "new \App\Jobs\TestJob"
```

### make a command active / inactive

Each ScheduledCommand can be set to inactive / active by its property 'is_active'.
the default value is false, so you must explicitly activate the command to be recognized 
in the laravel scheduler.

### add description / notices to the command

Each ScheduledCommand have a property 'description', where you can save additional notices if needed.

## get all registered / available commands of your application

### model based

```php 
$commands = (new \DionTech\Scheduler\Support\Helper\CommandLister)->all();
```

### request response based

insert this in your controller, that's it:

```php
return (new \DionTech\Scheduler\Http\Responses\ListAllCommandsResponse())
    ->toResponse($request);
```

# NextSteps

- writing an API class
- make getCommands() method cacheable
- build some configs
