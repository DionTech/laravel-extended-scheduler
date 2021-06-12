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

class EventResourceTest extends TestCase
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
                'cron' => [
                    '* * * * *'
                ]
            ],
            'is_active' => true
        ]);

        $this->assertInstanceOf(\Illuminate\Console\Scheduling\Event::class, $model->event());

        $event = $model->event();
        $this->assertEquals('* * * * *', $event->expression);
    }

    public function test_foo_example()
    {
        $model = ScheduledCommand::create([
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

        $event = $model->event();
        $this->assertEquals('0 * * * 1-5', $event->expression);
        $this->assertTrue(Str::is('*foo', $event->command));
    }

    public function test_job_example()
    {
        $model = ScheduledCommand::create([
            'method' => 'job',
            'arguments' => [
                'new \App\Jobs\TestJob', 'sqs'
            ],
            'fluent' => [
                'everyFiveMinutes'
            ],
            'is_active' => true
        ]);

        //test job
        $event = $model->event();
        $this->assertEquals('*/5 * * * *', $event->expression);
        $this->assertTrue(Str::is('*new \App\Jobs\TestJob', $event->description));
    }

    public function test_command_with_argument_example()
    {
        $model = ScheduledCommand::create([
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

        $event = $model->event();
        $this->assertTrue(Str::is('*test:command*', $event->command));
        $this->assertEquals('0 0 * * *', $event->expression);
        $this->assertTrue(Str::is('*Taylor*', $event->command));
        $this->assertTrue(Str::is('*--force*', $event->command));
    }

    public function test_schedule_list_example()
    {
        $model = ScheduledCommand::create([
            'method' => 'command',
            'arguments' => [
                'schedule:list'
            ],
            'fluent' => [
                'cron' => ['* * * * *']
            ],
            'is_active' => true
        ]);

        $event = $model->event();
        $this->assertTrue(Str::is('*schedule:list', $event->command));
        $this->assertEquals('* * * * *', $event->expression);
    }

}
