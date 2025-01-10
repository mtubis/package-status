# PackageStatus Library

## Overview
The `PackageStatus` library is a PHP library designed to facilitate order status management and notification handling. It allows for the processing of order status updates and communicates with external services to notify users about order changes. The library leverages Symfony components and Guzzle for robust functionality and extensibility.

## Features
- Map carrier statuses to system statuses.
- Validate and deduplicate phone numbers (currently supports Polish mobile numbers).
- Notify external services (via REST or SOAP API) about status changes.
- Flexible architecture designed with clean code principles and the SOLID design philosophy.

## Installation
To install the library, use Composer:

```bash
composer require mtubis/package-status
```

Make sure you have Composer installed. If not, follow the instructions [here](https://getcomposer.org/).

## Usage

### 1. **Initialize the Library**
Import the required classes and initialize the components:

```php
use MTubis\PackageStatus\Order\Order;
use MTubis\PackageStatus\Order\Address;
use MTubis\PackageStatus\StatusMapping\StatusMapper;
use MTubis\PackageStatus\API\NotificationService;
use MTubis\PackageStatus\Validation\PhoneNumberValidator;
use MTubis\PackageStatus\OrderProcessor;
use GuzzleHttp\Client;

// Configure the status mapping
$statusMap = [
    'S_PENDING' => [
        'Y_PENDING' => 'S_PENDING',
        'Y_SHIPPED' => 'S_IN_TRANSIT',
        'Y_DELIVERED' => 'S_DELIVERED',
    ],
];
$statusMapper = new StatusMapper($statusMap);

// Initialize the Guzzle client for notifications
$httpClient = new Client(['base_uri' => 'https://example.com/api/']);
$notificationService = new NotificationService($httpClient);

// Initialize the phone number validator
$validator = new PhoneNumberValidator();

// Initialize the order processor
$orderProcessor = new OrderProcessor($statusMapper, $notificationService, $validator);
```

### 2. **Create an Order**
Create an `Order` object with the necessary details:

```php
$order = new Order(
    '123',
    'S_PENDING',
    new Address('Street A', 'City B', '12345', 'PL'),
    new Address('Street C', 'City D', '54321', 'PL'),
    new Address('Street E', 'City F', '98765', 'PL'),
    ['+48512345678', '+48598765432']
);
```

### 3. **Process the Order**
Process the order to map the status and send notifications:

```php
$orderProcessor->process($order, 'Y_SHIPPED');
```

### 4. **Handle Errors**
Ensure that exceptions are caught and handled appropriately:

```php
try {
    $orderProcessor->process($order, 'Y_SHIPPED');
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
```

## Future Improvements
While the library is functional, the following improvements can be made:

1. **Enhanced Validation**: Extend phone number validation to support international formats.
2. **Logging**: Integrate a logging system (e.g., Monolog) to log all notifications and errors.
3. **Customization**: Allow dynamic configuration of status mapping via configuration files (e.g., YAML or JSON).
4. **Support for More Protocols**: Extend the API client to support SOAP in addition to REST.
5. **Test Coverage**: Add more edge cases and integration tests.
6. **Asynchronous Notifications**: Implement asynchronous processing for notification delivery.
7. **OpenAPI Integration**: Use OpenAPI to generate API clients and documentation dynamically.

## Contributions
Contributions are welcome! Please fork the repository, create a new branch, and submit a pull request. Make sure to follow PSR standards and include tests for any new features or bug fixes.

## License
This library is licensed under the MIT License. See the LICENSE file for details.

---

For questions or support, feel free to reach out to the maintainer via [GitHub Issues](https://github.com/mtubis/package-status/issues).

