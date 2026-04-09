<?php

class PowerReading extends Model
{
    protected $primaryKey = 'power_id';
    protected $table = 'power_readings';
    protected $fillable = ['timestamp', 'battery_voltage', 'panel_voltage', 'log_id'];

    protected $casts = ['timestamp' => 'datetime'];

    public function sensorLog()
    {
        return $this->belongsTo(SensorLog::class, 'log_id', 'log_id');
    }
}