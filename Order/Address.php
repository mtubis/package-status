<?php

namespace MTubis\PackageStatus\Order;

class Address
{
    public function __construct(
        private string $street,
        private string $city,
        private string $postalCode,
        private string $country
    ) {}

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}