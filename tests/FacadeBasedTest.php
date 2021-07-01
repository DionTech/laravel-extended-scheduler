<?php
namespace DionTech\Scheduler\Tests;


use DionTech\Scheduler\Support\Facades\ScheduledCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class FacadeBasedTest extends TestCase
{
    use DatabaseMigrations;


    public function test_base_insert_inactive()
    {
        ScheduledCommand::arguments([
            'foo'
        ])->fluent([
            'cron' => [
                '* * * * *'
            ]
        ])->create();

        $this->assertEquals(1, \DionTech\Scheduler\Models\ScheduledCommand::count());
        $schedule = app()->get(Schedule::class);

        $event = Arr::where($schedule->events(), function($item) {
           return Str::is('*foo', $item->command);
        });

        $this->assertEquals(0, count($event));
    }

    public function test_base_insert_active()
    {
        ScheduledCommand::arguments([
                'foo'
            ])->fluent([
                'cron' => [
                    '* * * * *'
                ]
            ])->isActive()
            ->create();

        $this->assertEquals(1, \DionTech\Scheduler\Models\ScheduledCommand::count());
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
        $event  = ScheduledCommand::create();

        $this->assertStringContainsString('artisan', $event->command);
        $this->assertEquals('* * * * *', $event->expression);
    }
}
