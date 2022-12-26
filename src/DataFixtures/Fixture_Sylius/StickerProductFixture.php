<?php


namespace App\DataFixtures\Fixture_Sylius;

use App\CoreBundle\Fixture\AbstractResourceFixture;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StickerProductFixture extends AbstractFixture
{
    private Generator $faker;

    private OptionsResolver $optionsResolver;

    public function __construct(
        private readonly AbstractResourceFixture $taxonFixture,
        private readonly AbstractResourceFixture $productAttributeFixture,
        private readonly AbstractResourceFixture $productOptionFixture,
        private readonly AbstractResourceFixture $productFixture,
    ) {
        $this->faker = Factory::create();
        $this->optionsResolver =
            (new OptionsResolver())
                ->setRequired('amount')
                ->setAllowedTypes('amount', 'int')
        ;
    }

    public function getName(): string
    {
        return 'sticker_product';
    }

    public function load(array|ObjectManager $manager): void
    {
        $manager = $this->optionsResolver->resolve($manager);

        $this->taxonFixture->load(['custom' => [[
            'code' => 'category',
            'name' => 'Category',
            'children' => [
                [
                    'code' => 'stickers',
                    'translations' => [
                        'en_US' => [
                            'name' => 'Stickers',
                        ],
                        'fr_FR' => [
                            'name' => 'Étiquettes',
                        ],
                    ],
                ],
            ],
        ]]]);

        $this->productAttributeFixture->load(['custom' => [
            ['name' => 'Sticker paper', 'code' => 'sticker_paper', 'type' => TextAttributeType::TYPE],
            ['name' => 'Sticker resolution', 'code' => 'sticker_resolution', 'type' => TextAttributeType::TYPE],
        ]]);

        $this->productOptionFixture->load(['custom' => [
            [
                'name' => 'Sticker size',
                'code' => 'sticker_size',
                'values' => [
                    'sticker_size_3' => '3"',
                    'sticker_size_5' => '5"',
                    'sticker_size_7' => '7"',
                ],
            ],
        ]]);

        $products = [];
        $productsNames = $this->getUniqueNames($manager['amount']);
        for ($i = 0; $i < $manager['amount']; ++$i) {
            $products[] = [
                'name' => sprintf('Sticker "%s"', $productsNames[$i]),
                'code' => $this->faker->uuid,
                'main_taxon' => 'stickers',
                'taxons' => ['stickers'],
                'variant_selection_method' => ProductInterface::VARIANT_SELECTION_CHOICE,
                'product_attributes' => [
                    'sticker_paper' => sprintf('Paper from tree %s', $this->faker->randomElement(['Wung', 'Tanajno', 'Lemon-San', 'Me-Gusta'])),
                    'sticker_resolution' => $this->faker->randomElement(['JKM XD', '476DPI', 'FULL HD', '200DPI']),
                ],
                'product_options' => ['sticker_size'],
                'images' => [
                    [
                        'path' => sprintf('%s/../Resources/fixtures/%s', __DIR__, 'stickers.jpg'),
                        'type' => 'main',
                    ],
                    [
                        'path' => sprintf('%s/../Resources/fixtures/%s', __DIR__, 'stickers.jpg'),
                        'type' => 'thumbnail',
                    ],
                ],
            ];
        }

        $this->productFixture->load(['custom' => $products]);
    }

    protected function configureOptionsNode(ArrayNodeDefinition $optionsNode): void
    {
        $optionsNode
            ->children()
            ->integerNode('amount')->isRequired()->min(0)->end()
        ;
    }

    private function getUniqueNames(int $amount): array
    {
        $productsNames = [];

        for ($i = 0; $i < $amount; ++$i) {
            $name = $this->faker->word;
            while (in_array($name, $productsNames)) {
                $name = $this->faker->word;
            }
            $productsNames[] = $name;
        }

        return $productsNames;
    }
}