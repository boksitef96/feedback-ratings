<?php

namespace App\Service;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ApiService
{
    public const INVALID_OR_MISSING_PARAMETER = 'INVALID_OR_MISSING_PARAMETER';

    public static function normalize(ConstraintViolationListInterface $violations): array
    {
        $indexedList = [];

        foreach ($violations as $violation) {
            $indexedList[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $indexedList;
    }

    public function validationErrorResponse(ConstraintViolationListInterface $constraintViolationList): array
    {
        $payload = $this->normalize($constraintViolationList);

        return $this->createResponse($payload, self::INVALID_OR_MISSING_PARAMETER);
    }

    public function createResponse($payload, ?string $errorCode = null): array
    {
        return [
            'payload'    => $payload,
            'error_code' => $errorCode,
        ];
    }
}