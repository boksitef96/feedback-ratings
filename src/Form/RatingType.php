<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

class RatingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('ratings', CollectionType::class, [
            'entry_type'   => RatingItemType::class,
            'allow_add'    => true,
            'allow_delete' => true,
        ]);
    }
}

