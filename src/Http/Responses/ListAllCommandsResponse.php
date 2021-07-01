<?php


namespace DionTech\Scheduler\Http\Responses;


use DionTech\Scheduler\Support\Helper\CommandLister;
use Illuminate\Contracts\Support\Responsable;

class ListAllCommandsResponse implements Responsable
{
    public function toResponse($request)
    {
        return (new CommandLister())->all();
    }
}
