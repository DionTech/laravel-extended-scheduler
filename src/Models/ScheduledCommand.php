<?php


namespace DionTech\Scheduler\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledCommand extends Model
{
    use HasFactory;

    protected $fillable = ['method', 'arguments', 'frequency', 'is_active', 'description'];

    protected $casts = [
        'arguments' => 'array',
        'frequency' => 'array',
        'is_active' => 'boolean'
    ];

    protected $attributes = [
        'arguments' => '{}',
        'frequency' => '{}'
    ];
}
