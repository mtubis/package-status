<?php

namespace MTubis\PackageStatus\Order;

use MTubis\PackageStatus\Validation\PhoneNumberValidator;

class Order
{
    private array $phoneNumbers;

    public function __construct(
        private string $id,
        private string $status,
        private Address $senderAddress,
        private Address $receiverAddress,
        private Address $clientAddress,
        array $phoneNumbers,
        PhoneNumberValidator $phoneValidator
    ) {
        // Phone number validation and deduplication
        $this->phoneNumbers = array_unique(
            array_filter($phoneNumbers, [$phoneValidator, 'validatePhoneNumber'])
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getAddresses(): array
    {
        return [
            'sender' => $this->senderAddress,
            'receiver' => $this->receiverAddress,
            'client' => $this->clientAddress,
        ];
    }

    public function getPhoneNumbers(): array
    {
        return $this->phoneNumbers;
    }
}