<?php

namespace MTubis\PackageStatus\Tests;

namespace MTubis\PackageStatus\Tests;

use MTubis\PackageStatus\API\NotificationService;
use MTubis\PackageStatus\Order\Order;
use MTubis\PackageStatus\Order\Address;
use MTubis\PackageStatus\StatusMapping\StatusMapper;
use MTubis\PackageStatus\OrderProcessor;
use MTubis\PackageStatus\Validation\PhoneNumberValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

class OrderProcessorTest extends TestCase
{
    private StatusMapper $mapper;
    private NotificationService $notificationService;
    private PhoneNumberValidator $validator;

    protected function setUp(): void
    {
        // Status Mapping Configuration
        $statusMap = Yaml::parseFile(__DIR__ . '/Config/status_mapping.yaml');
        $this->mapper = new StatusMapper($statusMap);

        // Mock NotificationService
        $this->notificationService = $this->createMock(NotificationService::class);

        // Phone number validator instance
        $this->validator = new PhoneNumberValidator();
    }

    public function testProcessWithValidData(): void
    {
        $this->notificationService
            ->expects($this->once())
            ->method('notify')
            ->with('123', 'S_IN_TRANSIT', ['+48512345678']);

        $processor = new OrderProcessor($this->mapper, $this->notificationService);

        $order = new Order(
            '123',
            'S_PENDING',
            new Address('Street A', 'City B', '12345', 'PL'),
            new Address('Street C', 'City D', '54321', 'PL'),
            new Address('Street E', 'City F', '98765', 'PL'),
            ['+48512345678'],
            $this->validator
        );

        $processor->process($order, 'Y_SHIPPED');
    }

    public function testProcessWithDuplicatePhoneNumbers(): void
    {
        $this->notificationService
            ->expects($this->once())
            ->method('notify')
            ->with('123', 'S_IN_TRANSIT', ['+48512345678']);

        $processor = new OrderProcessor($this->mapper, $this->notificationService);

        $order = new Order(
            '123',
            'S_PENDING',
            new Address('Street A', 'City B', '12345', 'PL'),
            new Address('Street C', 'City D', '54321', 'PL'),
            new Address('Street E', 'City F', '98765', 'PL'),
            ['+48512345678', '+48512345678'], // Duplicated numbers
            $this->validator
        );

        $processor->process($order, 'Y_SHIPPED');
    }

    public function testProcessWithInvalidPhoneNumber(): void
    {
        $this->notificationService
            ->expects($this->never())
            ->method('notify');

        $processor = new OrderProcessor($this->mapper, $this->notificationService);

        $order = new Order(
            '123',
            'S_PENDING',
            new Address('Street A', 'City B', '12345', 'PL'),
            new Address('Street C', 'City D', '54321', 'PL'),
            new Address('Street E', 'City F', '98765', 'PL'),
            ['12345'], // Błędny numer
            $this->validator
        );

        // Assertion: no numbers after validation
        $this->assertCount(0, $order->getPhoneNumbers(), 'Invalid phone numbers should be filtered out.');

        $processor->process($order, 'Y_SHIPPED');
    }

    public function testProcessWithUnknownCarrierStatus(): void
    {
        $this->notificationService
            ->expects($this->never())
            ->method('notify');

        $processor = new OrderProcessor($this->mapper, $this->notificationService);

        $order = new Order(
            '123',
            'S_PENDING',
            new Address('Street A', 'City B', '12345', 'PL'),
            new Address('Street C', 'City D', '54321', 'PL'),
            new Address('Street E', 'City F', '98765', 'PL'),
            ['+48512345678'],
            $this->validator
        );

        $processor->process($order, 'Y_UNKNOWN'); // Carrier status unknown in mapping
    }
}