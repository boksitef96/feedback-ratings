<?php

namespace App\Exception;

use App\Service\ApiService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FormException extends HttpException
{
    private $form;

    public function __construct(FormInterface $form, $statusCode = Response::HTTP_BAD_REQUEST, $code = ApiService::INVALID_OR_MISSING_PARAMETER)
    {
        $this->form = $form;

        parent::__construct($statusCode, $code);
    }


    public static function formError(FormInterface $form): self
    {
        return new self(
            $form
        );
    }

    public function getFormError()
    {
        $errors = [];

        foreach ($this->form->getErrors(true) as $error) {
            $errors[] = [
                "code"    => ApiService::INVALID_OR_MISSING_PARAMETER,
                "field"   => $error->getOrigin()->getName(),
                "message" => $error->getMessage()
            ];
        }

        return $errors;
    }
}