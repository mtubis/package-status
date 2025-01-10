<?php

namespace MTubis\PackageStatus;

use MTubis\PackageStatus\Order\Order;
use MTubis\PackageStatus\StatusMapping\StatusMapper;
use MTubis\PackageStatus\API\NotificationService;

class OrderProcessor
{
    public function __construct(
        private StatusMapper $statusMapper,
        private NotificationService $notificationService
    ) {}

    public function process(Order $order, string $carrierStatus): void
    {
        $newStatus = $this->statusMapper->mapStatus($order->getStatus(), $carrierStatus);

        // Checking if the new status is correct and if the list of phone numbers is not empty
        if ($newStatus !== 'UNKNOWN' && count($order->getPhoneNumbers()) > 0) {
            $this->notificationService->notify($order->getId(), $newStatus, $order->getPhoneNumbers());
        }
    }
}