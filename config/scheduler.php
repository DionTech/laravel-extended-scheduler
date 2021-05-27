<?php

return [
    /**
     * The repository class you will choose have to implement the interface DionTech\Scheduler\Contracts\ScheduledCommandRepositoryContract.
     * At the moment there will be no need to choose another driver
     */
    'driver' => \DionTech\Scheduler\Repositories\ScheduledCommandRepository::class
];
