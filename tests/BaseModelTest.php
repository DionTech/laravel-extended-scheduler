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

class BaseModelTest extends TestCase
{
    use DatabaseMigrations;


    public function test_base_insert_inactive()
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
            ]
        ]);

        $this->assertEquals(1, ScheduledCommand::count());
        $schedule = app()->get(Schedule::class);

        $event = Arr::where($schedule->events(), function($item) {
           return Str::is('*foo', $item->command);
        });

        $this->assertEquals(0, count($event));
    }

    public function test_base_insert_active()
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

        $this->assertEquals(1, ScheduledCommand::count());
        $schedule = app()->get(Schedule::class);

        $event = Arr::where($schedule->events(), function($item) {
            return Str::is('*foo', $item->command);
        });

        $this->assertEquals(1, count($event));
        $event = $event[0];
        $this->assertEquals('* * * * *', $event->expression);

    }

    public function test_avoid_exception_on_empty_object()
    {
        $command = new ScheduledCommand();

        $this->assertStringContainsString('artisan', $command->event()->command);
        $this->assertEquals('* * * * *', $command->event()->expression);
    }

    public function test_schedule_list_examples()
    {
        $this->setUpScheduler();
        $schedule = app()->get(Schedule::class);

        //test schedule:list
        $event = Arr::where($schedule->events(), function($item) {
            return Str::is('*schedule:list', $item->command);
        });

        $this->assertEquals(1, count($event));
        $event = Arr::first($event);
        $this->assertEquals('* * * * *', $event->expression);

        //test foo
        $event = Arr::where($schedule->events(), function($item) {
            return Str::is('*foo', $item->command);
        });

        $this->assertEquals(1, count($event));
        $event = Arr::first($event);
        $this->assertEquals('0 * * * 1-5', $event->expression);

        //test job
        $event = Arr::where($schedule->events(), function($item) {
            return Str::is('*new \App\Jobs\TestJob', $item->description);
        });

        $this->assertEquals(1, count($event));
        $event = Arr::first($event);
        $this->assertEquals('*/5 * * * *', $event->expression);

        //test command with arguments
        $event = Arr::where($schedule->events(), function($item) {
            return Str::is('* test:command*', $item->command);
        });

        $this->assertEquals(1, count($event));
        $event = Arr::first($event);
        $this->assertEquals('0 0 * * *', $event->expression);
        $this->assertTrue(Str::is('*Taylor*', $event->command));
        $this->assertTrue(Str::is('*--force*', $event->command));
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
