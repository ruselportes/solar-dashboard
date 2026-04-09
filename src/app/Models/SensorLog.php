<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorLog extends Model
{
    protected $primaryKey = 'log_id';
    protected $table = 'sensor_logs';
    protected $fillable = [
        'timestamp', 'config_id', 'ldr1', 'ldr2', 'ldr3', 'ldr4', 'ldr5', 'ldr6',
        'shadow_detected', 'is_moving', 'servo_horizontal_angle', 'servo_vertical_angle',
        'ultrasonic_distance', 'ultrasonic_servo_angle', 'mode', 'emergency_stop_active'
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'shadow_detected' => 'boolean',
        'is_moving' => 'boolean',
        'emergency_stop_active' => 'boolean',
    ];

    // Relationships
    public function config()
    {
        return $this->belongsTo(SystemConfig::class, 'config_id', 'config_id');
    }

    public function powerReading()
    {
        return $this->hasOne(PowerReading::class, 'log_id', 'log_id');
    }

    public function manualCommands()
    {
        return $this->hasMany(ManualCommand::class, 'related_log_id', 'log_id');
    }

    public function systemEvents()
    {
        return $this->hasMany(SystemEvent::class, 'trigger_log_id', 'log_id');
    }

    // Accessor: average LDR value
    public function getAverageLdrAttribute()
    {
        return ($this->ldr1 + $this->ldr2 + $this->ldr3 + $this->ldr4 + $this->ldr5 + $this->ldr6) / 6;
    }
}
