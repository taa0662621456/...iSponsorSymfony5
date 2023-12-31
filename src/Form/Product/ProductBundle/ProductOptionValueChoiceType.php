<?php

namespace App\Form\Product\ProductBundle;

use Symfony\Component\Form\AbstractType;
use App\Interface\Product\ProductInterface;
use Symfony\Component\OptionsResolver\Options;
use App\Interface\Product\ProductOptionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Interface\Product\ProductAvailableOptionValuesResolverInterface;

final class ProductOptionValueChoiceType extends AbstractType
{
    private ?ProductAvailableOptionValuesResolverInterface $availableProductOptionValuesResolver;

    public function __construct(ProductAvailableOptionValuesResolverInterface $availableProductOptionValuesResolver = null)
    {
        if (null === $availableProductOptionValuesResolver) {
            @trigger_error(
                'Not passing availableProductOptionValuesResolver thru constructor is deprecated in Sylius 1.8 and '.
                'it will be removed in Sylius 2.0',
            );
        }

        $this->availableProductOptionValuesResolver = $availableProductOptionValuesResolver;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'choices' => function (Options $options): iterable {
                    $productOption = $options['option'];
                    if (true === $options['only_available_values']) {
                        if (null === $options['product']) {
                            throw new \RuntimeException('You must specify the "product" option when "only_available_values" is true.');
                        }

                        if (null === $this->availableProductOptionValuesResolver) {
                            throw new \RuntimeException(sprintf('Cannot provide only available values in "%s" because a "%s" is required but none has been set.', __CLASS__, ProductAvailableOptionValuesResolverInterface::class));
                        }

                        return $this->availableProductOptionValuesResolver->resolve(
                            $options['product'],
                            $productOption,
                        );
                    }

                    return $productOption->getValues();
                },
                'choice_value' => 'code',
                'choice_label' => 'value',
                'choice_translation_domain' => false,
                'only_available_values' => false,
                'product' => null,
            ])
            ->setRequired([
                'option',
            ])
            ->addAllowedTypes('option', [ProductOptionInterface::class])
            ->addAllowedTypes('product', ['null', ProductInterface::class]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_product_option_value_choice';
    }
}