<?php
namespace App\Repositories\Contracts;
use App\Models\SensorLog;
use Illuminate\Pagination\LengthAwarePaginator;


interface SensorLogRepositoryInterface
{
    public function getLatest(): ?SensorLog;

    public function getPaginated(array $filters = []): LengthAwarePaginator;

    public function getChartData(string $range = '24hour', int $point = 60): \Illuminate\Support\Collection;

    public function getSummaryStats(): array;

    public function getDistinctModes(): array;
}