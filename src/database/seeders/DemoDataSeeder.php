<?php

namespace Database\Seeders;
use App\Models\SystemConfig;
use App\Models\SensorLog;
use App\Models\PowerReading;
use Carbon\Carbon;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run()
{
    // Insert a default config
    $config = SystemConfig::create([
        'shadow_threshold' => 300,
        'servo_home_horizontal' => 90,
        'servo_home_vertical' => 90,
        'ultrasonic_scan_min_angle' => 0,
        'ultrasonic_scan_max_angle' => 180,
        'obstacle_distance_threshold' => 50,
        'motor_speed' => 200,
        'upload_interval_sec' => 30,
    ]);

    // Insert 100 sensor logs with random values
    for ($i = 0; $i < 100; $i++) {
        $timestamp = Carbon::now()->subMinutes(100 - $i);
        $sensor = SensorLog::create([
            'timestamp' => $timestamp,
            'config_id' => $config->config_id,
            'ldr1' => rand(200, 800),
            'ldr2' => rand(200, 800),
            'ldr3' => rand(200, 800),
            'ldr4' => rand(200, 800),
            'ldr5' => rand(200, 800),
            'ldr6' => rand(200, 800),
            'shadow_detected' => rand(0,1),
            'is_moving' => rand(0,1),
            'servo_horizontal_angle' => rand(0,180),
            'servo_vertical_angle' => rand(0,180),
            'ultrasonic_distance' => rand(20, 150),
            'ultrasonic_servo_angle' => rand(0,180),
            'mode' => ['AUTO','MANUAL'][rand(0,1)],
            'emergency_stop_active' => 0,
        ]);

        PowerReading::create([
            'timestamp' => $timestamp,
            'battery_voltage' => 11.5 + rand(0, 30)/10,
            'panel_voltage' => 12 + rand(0, 80)/10,
            'log_id' => $sensor->log_id,
        ]);

    }
}
}