<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemConfig extends Model
{
    use HasFactory;
    protected $primaryKey = 'config_id';
    protected $table = 'system_configs';
    protected $fillable = [
        'shadow_threshold', 'servo_home_horizontal', 'servo_home_vertical',
        'ultrasonic_scan_min_angle', 'ultrasonic_scan_max_angle',
        'obstacle_distance_threshold', 'motor_speed', 'upload_interval_sec'
    ];

    // A config can be used by many sensor logs
    public function sensorLogs()
    {
        return $this->hasMany(SensorLog::class, 'config_id', 'config_id');
    }
}