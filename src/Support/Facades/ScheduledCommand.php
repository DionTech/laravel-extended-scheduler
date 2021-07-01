<?php


namespace DionTech\Scheduler\Support\Facades;


use Illuminate\Support\Facades\Facade;

class ScheduledCommand extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'scheduledCommand';
    }
}
