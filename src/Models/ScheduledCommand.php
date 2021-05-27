<?php


namespace DionTech\Scheduler\Models;


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
}
