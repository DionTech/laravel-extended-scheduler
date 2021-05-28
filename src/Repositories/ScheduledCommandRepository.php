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
        ScheduledCommand::whereIsActive(true)->cursor()->each(
            fn($cmd) => $cmd->setSchedule($schedule)->event()
        );

    }
}
