<?php

namespace DionTech\Scheduler\Contracts;

use Illuminate\Console\Scheduling\Schedule;

interface ScheduledCommandRepositoryContract
{
    public function getCommands(Schedule $schedule);
}
