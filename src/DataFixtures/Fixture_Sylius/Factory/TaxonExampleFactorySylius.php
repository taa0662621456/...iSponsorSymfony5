<?php


namespace App\DataFixtures\Fixture_Sylius\Factory;

use Faker\Factory;
use Faker\Generator;






use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaxonExampleFactorySylius extends SyliusAbstractExampleFactory implements ExampleFactoryInterface
{
    private Generator $faker;

    private OptionsResolver $optionsResolver;

    public function __construct(
        private FactoryInterface $taxonFactory,
        private TaxonRepositoryInterface $taxonRepository,
        private RepositoryInterface $localeRepository,
        private TaxonSlugGeneratorInterface $taxonSlugGenerator,
    ) {
        $this->faker = Factory::create();
        $this->optionsResolver = new OptionsResolver();

        $this->configureOptions($this->optionsResolver);
    }

    public function create(array $options = []): TaxonInterface
    {
        return $this->createTaxon($options);
    }

    protected function createTaxon(array $options = [], ?TaxonInterface $parentTaxon = null): ?TaxonInterface
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var TaxonInterface|null $taxon */
        $taxon = $this->taxonRepository->findOneBy(['code' => $options['code']]);

        if (null === $taxon) {
            /** @var TaxonInterface $taxon */
            $taxon = $this->taxonFactory->createNew();
        }

        $taxon->setCode($options['code']);

        if (null !== $parentTaxon) {
            $taxon->setParent($parentTaxon);
        }

        // add translation for each defined locales
        foreach ($this->getLocales() as $localeCode) {
            $this->createTranslation($taxon, $localeCode, $options);
        }

        // create or replace with custom translations
        foreach ($options['translations'] as $localeCode => $translationOptions) {
            $this->createTranslation($taxon, $localeCode, $translationOptions);
        }

        foreach ($options['children'] as $childOptions) {
            $this->createTaxon($childOptions, $taxon);
        }

        return $taxon;
    }

    protected function createTranslation(TaxonInterface $taxon, string $localeCode, array $options = []): void
    {
        $options = $this->optionsResolver->resolve($options);

        $taxon->setCurrentLocale($localeCode);
        $taxon->setFallbackLocale($localeCode);

        $taxon->setName($options['name']);
        $taxon->setDescription($options['description']);
        $taxon->setSlug($options['slug'] ?: $this->taxonSlugGenerator->generate($taxon, $localeCode));
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('name', function (Options $options): string {
                /** @var string $words */
                $words = $this->faker->words(3, true);

                return $words;
            })
            ->setDefault('code', fn (Options $options): string => StringInflector::nameToCode($options['name']))
            ->setDefault('slug', null)
            ->setDefault('description', fn (Options $options): string => $this->faker->paragraph)
            ->setDefault('translations', [])
            ->setAllowedTypes('translations', ['array'])
            ->setDefault('children', [])
            ->setAllowedTypes('children', ['array'])
        ;
    }

    private function getLocales(): iterable
    {
        /** @var LocaleInterface[] $locales */
        $locales = $this->localeRepository->findAll();
        foreach ($locales as $locale) {
            yield $locale->getCode();
        }
    }
}