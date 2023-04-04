<?php

namespace App\Service;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ConstraintValidationApiNormalizer
{
    public static function normalize(ConstraintViolationListInterface $violations): array
    {
        $indexedList = [];

        foreach ($violations as $violation) {
            $indexedList[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $indexedList;
    }
}
