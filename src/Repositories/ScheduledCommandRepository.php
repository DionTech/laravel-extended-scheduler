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
        foreach (ScheduledCommand::where('is_active', 1)->cursor() as $cmd) {
            $base = $this->call($schedule, $cmd->method, $cmd->arguments);

            foreach ($cmd->frequency as $frequencyKey => $frequencyValue) {
                if (is_array($frequencyValue)) {
                    $base->{$frequencyKey}(...$frequencyValue);
                    continue;
                }

                $base->{$frequencyValue}();
            }
        }
    }

    protected function call(Schedule $schedule, $method, $arguments)
    {
        return $schedule->{$method}(...$arguments);
    }
}
