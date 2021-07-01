<?php


namespace DionTech\Scheduler\Support\Helper;


use Illuminate\Contracts\Console\Kernel;
use Illuminate\Console\Events\ArtisanStarting;

/**
 * Class CommandLister
 * @package DionTech\Scheduler\Support\Helper
 */
class CommandLister
{
    /**
     * @var array
     */
    protected $struct = [];

    /**
     * @var
     */
    protected $commands;

    /**
     * @return array
     */
    public function all()
    {
        return $this->getCommands()
            ->mapToReadable()
            ->struct;
    }

    /**
     * @return $this
     */
    protected function mapToReadable()
    {
        foreach ($this->commands as $name => $command) {
            $this->struct[$name] = [
                'name' => $name,
                'description' => $command->getDescription(),
                'definition' => $command->getDefinition(),
                'help' => $command->getHelp()
            ];
        }
        return $this;
    }

    /**
     * @return $this
     */
    protected function getCommands()
    {
        $this->commands = app()->get(Kernel::class)->all();

        return $this;
    }
}
