<?php


namespace DionTech\Scheduler\Models;


use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    /**
     * @return Event
     */
    public function event(): Event
    {
        $schedule = new Schedule();

        $event = $schedule->{$this->method}(...$this->arguments);

        $this->fluently($event);

        return $event;
    }

    /**
     * @param Event $event
     * @return $this
     */
    public function fluently(Event &$event): ScheduledCommand
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
