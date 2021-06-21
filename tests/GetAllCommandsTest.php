<?php


namespace DionTech\Scheduler\Tests;


use DionTech\Scheduler\Support\Helper\CommandLister;

class GetAllCommandsTest extends TestCase
{
    public function test_list_all_commands()
    {
        $list = (new CommandLister())->all();

        $this->assertArrayHasKey("make:migration", $list);
        $this->assertArrayHasKey("migrate:reset", $list);
        $this->assertArrayHasKey("migrate:refresh", $list);
    }
}
