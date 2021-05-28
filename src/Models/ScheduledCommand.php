<?php


namespace DionTech\Scheduler\Models;


use Illuminate\Console\Application;
use Illuminate\Console\Scheduling\CacheEventMutex;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\EventMutex;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ProcessUtils;

class ScheduledCommand extends Model
{
    use HasFactory;

    protected $fillable = ['method', 'arguments', 'fluent', 'is_active', 'description'];

    protected $casts = [
        'arguments' => 'array',
        'fluent' => 'array',
        'is_active' => 'boolean'
    ];

    protected $attributes = [
        'arguments' => '{}',
        'fluent' => '{}'
    ];

    public function event(): Event
    {
        $container = Container::getInstance();
        $arguments = $this->arguments;
        $method = array_pop($arguments);

        if (count($arguments)) {
            $method .= ' '.$this->compileParameters($arguments);
        }

        $eventMutex =  $container->bound(EventMutex::class)
            ? $container->make(EventMutex::class)
            : $container->make(CacheEventMutex::class);


        $event = new Event($eventMutex, $method);

        foreach ($this->fluent as $fluentKey => $fluentValue) {
            if (is_array($fluentValue)) {
                $event->{$fluentKey}(...$fluentValue);
                continue;
            }

            $event->{$fluentValue}();
        }

        return $event;
    }

    protected function compileParameters(array $parameters)
    {
        return collect($parameters)->map(function ($value, $key) {
            if (is_array($value)) {
                return $this->compileArrayInput($key, $value);
            }

            if (! is_numeric($value) && ! preg_match('/^(-.$|--.*)/i', $value)) {
                $value = ProcessUtils::escapeArgument($value);
            }

            return is_numeric($key) ? $value : "{$key}={$value}";
        })->implode(' ');
    }
}
