<?php


namespace DionTech\Scheduler\Models;


use Illuminate\Console\Application;
use Illuminate\Console\Scheduling\CacheEventMutex;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\EventMutex;
use Illuminate\Console\Scheduling\Schedule;
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
        $schedule = app()->get(Schedule::class);

        $arguments = $this->arguments;
        $method = array_pop($arguments);

        if (count($arguments)) {
            $method .= ' '.$schedule->compileParameters($arguments);
        }

        $eventMutex =  $container->bound(EventMutex::class)
            ? $container->make(EventMutex::class)
            : $container->make(CacheEventMutex::class);


        $event = new Event($eventMutex, $method);

        $this->fluently($event);

        return $event;
    }

    public function fluently(Event &$event)
    {
        foreach ($this->fluent as $fluentKey => $fluentValue) {
            if (is_array($fluentValue)) {
                $event->{$fluentKey}(...$fluentValue);
                continue;
            }

            $event->{$fluentValue}();
        }

        return $this;
    }
}
