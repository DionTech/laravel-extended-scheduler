<?php


namespace DionTech\Scheduler\Support\Helper;


use DionTech\Scheduler\Models\ScheduledCommand;
use Illuminate\Console\Scheduling\Event;

class ModelAbstractor
{
    protected $method = 'command';

    protected $arguments = [];

    protected $fluent = [];

    protected $isActive = false;

    public function create(): Event
    {
        $struct = [
            'method' => $this->method,
            'is_active' => $this->isActive
        ];

        if (!empty($this->arguments)) {
            $struct['arguments'] = $this->arguments;
        }

        if (!empty($this->fluent)) {
            $struct['fluent'] = $this->fluent;
        }

        return ScheduledCommand::create($struct)->event();
    }

    /**
     * @param string $method
     * @return ModelAbstractor
     */
    public function method(string $method): ModelAbstractor
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @param array $arguments
     * @return ModelAbstractor
     */
    public function arguments(array $arguments): ModelAbstractor
    {
        $this->arguments = $arguments;
        return $this;
    }

    /**
     * @param array $fluent
     * @return ModelAbstractor
     */
    public function fluent(array $fluent): ModelAbstractor
    {
        $this->fluent = $fluent;
        return $this;
    }

    /**
     * @param bool $isActive
     * @return ModelAbstractor
     */
    public function isActive(bool $isActive = true): ModelAbstractor
    {
        $this->isActive = $isActive;
        return $this;
    }


}
