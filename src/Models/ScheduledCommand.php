<?php


namespace DionTech\Scheduler\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledCommand extends Model
{
    use HasFactory;

    protected $fillable = ['method', 'arguments', 'frequency'];

    protected $casts = [
        'arguments' => 'array',
        'frequency' => 'array'
    ];

    protected $attributes = [
        'arguments' => '{}',
        'frequency' => '{}'
    ];
}
