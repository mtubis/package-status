<?php

namespace MTubis\PackageStatus\Validation;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

class PhoneNumberValidator
{
    public function validatePhoneNumber(string $phoneNumber): bool
    {
        $validator = Validation::createValidator();

        $constraint = new Assert\Regex([
            'pattern' => '/^\+48[5-9]\d{8}$/',
            'message' => 'Invalid Polish mobile phone number.',
        ]);

        $violations = $validator->validate($phoneNumber, $constraint);

        return count($violations) === 0;
    }
}