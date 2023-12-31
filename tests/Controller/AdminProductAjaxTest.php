<?php

namespace Controller;

use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

final class AdminProductAjaxTest extends SessionAwareAjaxTest
{
    /**
     * @throws \Exception
     */
    public function testItDeniesAccessToAProductsListForNotAuthenticatedUser()
    {
        $this->client->request('GET', '/admin/ajax/products/');

        $response = $this->client->getResponse();
        $this->assertTrue($response->isRedirection());
    }

    /**
     * @throws \Exception
     */
    public function testItAllowsToGetAProductsList()
    {
        $this->loadFixturesFromFile('authentication/administrator.yml');
        $this->loadFixturesFromFiles([
            'resources/product_association_types.yml',
            'resources/products.yml',
            'resources/many_products.yml',
            'resources/product_associations.yml',
        ]);

        $this->authenticateAdminUser();

        $this->client->request('GET', '/admin/ajax/products/');

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'ajax/product/index_response', Response::HTTP_OK);
    }

    private function authenticateAdminUser(): void
    {
        $adminUserRepository = self::$kernel->getContainer()->get('repository.admin_user');
        $user = $adminUserRepository->findOneByEmail('admin@sylius.com');
        $session = self::$kernel->getContainer()->get('request_stack')->getSession();

        $firewallName = 'admin';
        $firewallContext = 'admin';

        /* @deprecated parameter credential was deprecated in Symfony 5.4, so in Sylius 1.11 too, in Sylius 2.0 providing 4 arguments will be prohibited. */
        if (3 === (new \ReflectionClass(UsernamePasswordToken::class))->getConstructor()->getNumberOfParameters()) {
            $token = new UsernamePasswordToken($user, $firewallName, $user->getRoles());
        } else {
            $token = new UsernamePasswordToken($user, null, $firewallName, $user->getRoles());
        }

        $session->set(sprintf('_security_%s', $firewallContext), serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}