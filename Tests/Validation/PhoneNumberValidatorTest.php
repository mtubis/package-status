<?php

namespace MTubis\PackageStatus\Tests\Validation;

use PHPUnit\Framework\TestCase;
use MTubis\PackageStatus\Validation\PhoneNumberValidator;

class PhoneNumberValidatorTest extends TestCase
{
    public function testValidPhoneNumber(): void
    {
        $validator = new PhoneNumberValidator();

        // Assertion: Valid phone number
        $this->assertTrue($validator->validatePhoneNumber('+48512345678'));
    }

    public function testInvalidPhoneNumber(): void
    {
        $validator = new PhoneNumberValidator();

        // Assertion: invalid phone number
        $this->assertFalse($validator->validatePhoneNumber('12345'));
        $this->assertFalse($validator->validatePhoneNumber('+1234567890')); // Non-Polish number
    }

    public function testProcessWithInvalidPhoneNumber(): void
    {
        $validator = new PhoneNumberValidator();

        // Incorrect phone number - should not pass validation
        $this->assertFalse($validator->validatePhoneNumber('12345'));
        $this->assertFalse($validator->validatePhoneNumber('+1234567890')); // Non-Polish number
        $this->assertFalse($validator->validatePhoneNumber('+485123456789')); // Too long number
    }
}
