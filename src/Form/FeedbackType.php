<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class FeedbackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'overallRating',
                NumberType::class,
                [
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\NotNull()
                    ],
                    'required'    => true,
                ]
            )
            ->add(
                'text',
                TextareaType::class,
                [
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\NotNull()
                    ],
                    'required'    => true,
                ]
            );
    }
}
