<?php

namespace Functional;

use PHPUnit\Framework\Assert;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use HWI\Bundle\OAuthBundle\OAuth\Response\AbstractUserResponse;
use HWI\Bundle\OAuthBundle\OAuth\ResourceOwner\AbstractResourceOwner;

final class UpdatingUserPasswordEncoderTest extends WebTestCase
{
    /** @var Client */
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->followRedirects(true);

        /** @var LoaderInterface $fixtureLoader */
        $fixtureLoader = $this->client->getContainer()->get('fidry_alice_data_fixtures.loader.doctrine');
        $fixtureLoader->load(
            [
                __DIR__.'/../DataFixtures/ORM/resources/channels.yml',
                __DIR__.'/../DataFixtures/ORM/resources/customers.yml',
                __DIR__.'/../DataFixtures/ORM/resources/admin_users.yml',
            ],
            [],
            [],
            PurgeMode::createDeleteMode()
        );
    }

    public function testItUpdatesTheEncoderWhenTheShopUserLogsIn(): void
    {
        /** @var UserRepositoryInterface $shopUserRepository */
        $shopUserRepository = $this->client->getContainer()->get('repository.shop_user');

        /** @var ObjectManager $shopUserManager */
        $shopUserManager = $this->client->getContainer()->get('manager.shop_user');

        $shopUser = $shopUserRepository->findOneByEmail('oliver@doe.com');

        Assert::assertNotNull($shopUser, 'Could not find Shop User with oliver@doe.com email address');

        $shopUser->setPlainPassword('testpassword');
        $shopUser->setEncoderName('argon2i');

        $shopUserManager->persist($shopUser);
        $shopUserManager->flush();

        $this->client->request('GET', '/en_US/login');

        $this->submitForm('Login', [
            '_username' => 'Oliver@doe.com',
            '_password' => 'testpassword',
        ]);

        Assert::assertSame(200, $this->client->getResponse()->getStatusCode());
        Assert::assertSame('/en_US/', parse_url($this->client->getCrawler()->getUri(), \PHP_URL_PATH));
        Assert::assertSame('argon2i', $shopUserRepository->findOneByEmail('oliver@doe.com')->getEncoderName());
    }

    public function testItUpdatesTheEncoderWhenTheAdminUserLogsIn(): void
    {
        /** @var UserRepositoryInterface $adminUserRepository */
        $adminUserRepository = $this->client->getContainer()->get('repository.admin_user');

        /** @var ObjectManager $adminUserManager */
        $adminUserManager = $this->client->getContainer()->get('manager.admin_user');

        $adminUser = $adminUserRepository->findOneByEmail('user@example.com');
        $adminUser->setPlainPassword('testpassword');
        $adminUser->setEncoderName('argon2i');

        $adminUserManager->persist($adminUser);
        $adminUserManager->flush();

        $this->client->request('GET', '/admin/login');

        $this->submitForm('Login', [
            '_username' => 'user@example.com',
            '_password' => 'testpassword',
        ]);

        Assert::assertSame(200, $this->client->getResponse()->getStatusCode());
        Assert::assertSame('/admin/', parse_url($this->client->getCrawler()->getUri(), \PHP_URL_PATH));
        Assert::assertSame('argon2i', $adminUserRepository->findOneByEmail('user@example.com')->getEncoderName());
    }

    public function testOauthUserFactoryIsNotOverridden(): void
    {
        if (!$this->client->getContainer()->has('oauth.user_provider')) {
            $this->markTestSkipped('HWIOAuthBundle not installed');

            return;
        }

        $oAuthUserProvider = $this->client->getContainer()->get('oauth.user_provider');
        $shopUserRepository = $this->client->getContainer()->get('repository.shop_user');
        $shopUser = $shopUserRepository->findOneByEmail('Oliver@doe.com');
        $initialOAuthAccounts = $shopUser->getOAuthAccounts()->count();

        $resourceOwnerMock = $this->createConfiguredMock(
            AbstractResourceOwner::class,
            [
                'getName' => 'resourceProviderName',
            ]
        );

        $responseMock = $this->createConfiguredMock(
            AbstractUserResponse::class,
            [
                'getUsername' => 'someUserName',
                'getResourceOwner' => $resourceOwnerMock,
                'getAccessToken' => 'LongAccessToken',
                'getRefreshToken' => 'LongRefreshToken',
            ]
        );

        $oAuthUserProvider->connect($shopUser, $responseMock);

        Assert::assertSame($initialOAuthAccounts + 1, $shopUser->getOAuthAccounts()->count());
    }

    private function submitForm(string $button, array $fieldValues = []): void
    {
        $buttonNode = $this->client->getCrawler()->selectButton($button);

        $form = $buttonNode->form($fieldValues);

        $this->client->submit($form);
    }
}