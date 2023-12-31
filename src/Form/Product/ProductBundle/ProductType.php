<?php

namespace App\Form\Product\ProductBundle;

use Symfony\Component\Form\AbstractType;
use App\EventSubscriber\AddCodeFormSubscriber;
use App\EntityInterface\Locale\LocaleInterface;
use Symfony\Component\Form\FormBuilderInterface;
use App\EventSubscriber\Product\SimpleProductSubscriber;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use App\EventSubscriber\Product\ProductOptionFieldSubscriber;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\ServiceInterface\Product\ProductVariantResolverServiceInterface;

final class ProductType extends AbstractType
{
    /**
     * @param array|string[] $validationGroups
     */
    public function __construct(
        private readonly ProductVariantResolverServiceInterface $variantResolver,
        private readonly LocaleInterface $locale,
        string $dataClass = 'data_class',
        array $validationGroups = [],
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventSubscriber(new AddCodeFormSubscriber())
            ->addEventSubscriber(new ProductOptionFieldSubscriber($this->variantResolver))
            ->addEventSubscriber(new SimpleProductSubscriber())
            ->addEventSubscriber(new BuildAttributesFormSubscriber($this->attributeValueFactory, $this->locale))
            ->add('enabled', CheckboxType::class, [
                'required' => false,
                'label' => 'form.product.enabled',
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => ProductTranslationType::class,
                'label' => 'form.product.translations',
            ])
            ->add('attributes', CollectionType::class, [
                'entry_type' => ProductAttributeValueType::class,
                'required' => false,
                'prototype' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
            ])
            ->add('associations', ProductAssociationsType::class, [
                'label' => false,
            ]);
    }

    public function getBlockPrefix(): string
    {
        return 'product';
    }
}