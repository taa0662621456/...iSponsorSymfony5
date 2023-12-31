<?php

namespace App\Form\Product\ProductBundle;

use Symfony\Component\Form\AbstractType;
use App\EventSubscriber\AddCodeFormSubscriber;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\EventSubscriber\Product\BuildProductVariantFormSubscriber;

final class ProductVariantGenerationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => false,
                'label' => 'form.variant.name',
            ])
            ->addEventSubscriber(new AddCodeFormSubscriber());

        $builder->addEventSubscriber(new BuildProductVariantFormSubscriber($builder->getFormFactory(), true));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefault('constraints', [new Valid()]);
    }

    public function getBlockPrefix(): string
    {
        return 'product_variant_generation';
    }
}