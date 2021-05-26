<?php
namespace DionTech\Scheduler\Tests;

use DionTech\Scheduler\Models\ScheduledCommand;
use \Illuminate\Foundation\Testing\DatabaseMigrations;

class BaseModelTest extends \Tests\TestCase
{
    use DatabaseMigrations;

    public function test_base_insert()
    {
        $model = ScheduledCommand::create([
            'method' => 'command',
            'arguments' => [
                'schedule:list'
            ],
            'frequency' => [
                'cron' => '* * * * *'
            ]
        ]);

        $this->assertEquals(1, ScheduledCommand::count());
    }
}
