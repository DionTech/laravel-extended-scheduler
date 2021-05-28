<?php
namespace DionTech\Scheduler\Tests;

use Carbon\Carbon;
use DionTech\Scheduler\Models\ScheduledCommand;
use Illuminate\Console\Scheduling\CacheEventMutex;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\EventMutex;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Mockery as m;
use \Illuminate\Foundation\Testing\DatabaseMigrations;

class EventResourceTest extends \Tests\TestCase
{
    use DatabaseMigrations;


    public function test_base_get_event()
    {
        $model = ScheduledCommand::create([
            'method' => 'command',
            'arguments' => [
                'foo'
            ],
            'fluent' => [
                'weekdays',
                'hourly',
            ]
        ]);

        $this->assertInstanceOf(\Illuminate\Console\Scheduling\Event::class, $model->event());

        $event = $model->event();

        dump($event->command, $event->expression);
    }

    protected function setUpScheduler()
    {
        ScheduledCommand::create([
            'method' => 'command',
            'arguments' => [
                'schedule:list'
            ],
            'fluent' => [
                'cron' => ['* * * * *']
            ],
            'is_active' => true
        ]);

        ScheduledCommand::create([
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

        ScheduledCommand::create([
            'method' => 'job',
            'arguments' => [
                'new \App\Jobs\TestJob', 'sqs'
            ],
            'fluent' => [
                'everyFiveMinutes'
            ],
            'is_active' => true
        ]);

        ScheduledCommand::create([
            'method' => 'command',
            'arguments' => [
                'test:command',
                ['Taylor', '--force']
            ],
            'fluent' => [
                'daily',
                'evenInMaintenanceMode',
                'appendOutputTo' => ['/path/to/log'],
                'emailOutputTo' => ['daniel.koch@diontech.de']
            ],
            'is_active' => true
        ]);
    }
}
