<?php


namespace App\CoreBundle\Fixture;

use Doctrine\Persistence\ObjectManager;


use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class LocaleFixture extends AbstractFixture
{
    /**
     * @param string $baseLocaleCode
     */
    public function __construct(private FactoryInterface $localeFactory, private ObjectManager $localeManager, private $baseLocaleCode)
    {
    }

    public function load(array $options): void
    {
        $localesCodes = $options['locales'];

        if ($options['load_default_locale']) {
            array_unshift($localesCodes, $this->baseLocaleCode);
        }

        $localesCodes = array_unique($localesCodes);

        foreach ($localesCodes as $localeCode) {
            /** @var LocaleInterface $locale */
            $locale = $this->localeFactory->createNew();

            $locale->setCode($localeCode);

            $this->localeManager->persist($locale);
        }

        $this->localeManager->flush();
    }

    public function getName(): string
    {
        return 'locale';
    }

    protected function configureOptionsNode(ArrayNodeDefinition $optionsNode): void
    {
        $optionsNode
            ->children()
                ->scalarNode('load_default_locale')->defaultTrue()->end()
                ->arrayNode('locales')->scalarPrototype()->end()
        ;
    }
}