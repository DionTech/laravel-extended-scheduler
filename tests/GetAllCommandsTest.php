<?php


namespace DionTech\Scheduler\Tests;


use DionTech\Scheduler\Http\Responses\ListAllCommandsResponse;
use DionTech\Scheduler\Support\Helper\CommandLister;
use Illuminate\Http\Request;

class GetAllCommandsTest extends TestCase
{
    public function test_list_all_commands()
    {
        $list = (new CommandLister())->all();

        $this->assertArrayHasKey("make:migration", $list);
        $this->assertArrayHasKey("migrate:reset", $list);
        $this->assertArrayHasKey("migrate:refresh", $list);
    }

    public function test_list_all_commands_response()
    {
        $request = new Request();

        $response = (new ListAllCommandsResponse())->toResponse($request);

        $this->assertArrayHasKey("make:migration", $response);
        $this->assertArrayHasKey("migrate:reset", $response);
        $this->assertArrayHasKey("migrate:refresh", $response);
    }
}
