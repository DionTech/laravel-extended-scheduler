<?php

namespace DionTech\Scheduler\Repositories;

use DionTech\Scheduler\Contracts\ScheduledCommandRepositoryContract;
use DionTech\Scheduler\Models\ScheduledCommand;
use Illuminate\Console\Scheduling\Schedule;

/**
 * Class ScheduledCommandRepository
 * @package DionTech\Scheduler\Repositories
 */
class ScheduledCommandRepository implements ScheduledCommandRepositoryContract
{
    /**
     * @param Schedule $schedule
     */
    public function getCommands(Schedule $schedule)
    {
        foreach (ScheduledCommand::whereIsActive(true)->cursor() as $cmd) {
            $cmd->setSchedule($schedule)->event();
        }
    }

    protected function call(Schedule $schedule, $method, $arguments)
    {
        return $schedule->{$method}(...$arguments);
    }
}
