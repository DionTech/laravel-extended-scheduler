<?php


namespace DionTech\Scheduler\Support\Helper;


use Illuminate\Contracts\Console\Kernel;
use Illuminate\Console\Events\ArtisanStarting;

class CommandLister
{
    protected $struct = [];

    protected $commands;

    public function all()
    {
        return $this->getCommands()
            ->mapToReadable()
            ->struct;
    }

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

    protected function getCommands()
    {
        $this->commands = app()->get(Kernel::class)->all();
        //dd($this->commands);
        return $this;
    }
}
