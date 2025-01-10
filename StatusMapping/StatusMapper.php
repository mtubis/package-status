<?php

namespace MTubis\PackageStatus\StatusMapping;

class StatusMapper
{
    private array $statusMap;

    public function __construct(array $statusMap)
    {
        $this->statusMap = $statusMap;
    }

    public function mapStatus(string $currentStatus, string $carrierStatus): string
    {
        return $this->statusMap[$currentStatus][$carrierStatus] ?? 'UNKNOWN';
    }
}