<?php
namespace App\Repositories\Contracts;

interface SensorLogRepositoryInterface
{
    public function getLatest();
    public function getPaginated(int $perPage = 20);
    public function getChartData(string $groupBy = 'hour', int $limit = 24);
}