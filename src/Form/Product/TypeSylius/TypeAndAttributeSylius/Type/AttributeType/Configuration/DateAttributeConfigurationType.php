<?php


namespace App\AttributeBundle\Form\Type\AttributeType\Configuration;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class DateAttributeConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('format', TextType::class, [
                'label' => 'form.attribute_type_configuration.date.format',
                'required' => false,
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'attribute_type_configuration_date';
    }
}