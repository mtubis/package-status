<?php

namespace MTubis\PackageStatus\API;

use GuzzleHttp\ClientInterface;

class NotificationService
{
    public function __construct(private ClientInterface $client) {}

    public function notify(string $orderId, string $newStatus, array $phoneNumbers): void
    {
        foreach ($phoneNumbers as $phoneNumber) {
            $this->client->post('/api/notifications', [
                'json' => [
                    'order_id' => $orderId,
                    'new_status' => $newStatus,
                    'phone' => $phoneNumber,
                ],
            ]);
        }
    }
}