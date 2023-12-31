<?php

namespace App\Tests\Api\Shop;

use App\Tests\Api\tests\Api\JsonApiTestCase;
use Symfony\Component\HttpFoundation\Response;

final class ProductVariantsTest extends JsonApiTestCase
{
    public function testItGetsProductsWithOriginalPrice(): void
    {
        $this->loadFixturesFromFile('product/product_variant_with_original_price.yaml');

        $this->client->request('GET', '/api/v2/shop/product-variants/MUG_BLUE', [], [], self::CONTENT_TYPE_HEADER);
        $response = $this->client->getResponse();

        $this->assertResponse($response, 'shop/product/get_product_variant_with_original_price_response', Response::HTTP_OK);
    }

    public function testItGetsProductsWithoutOriginalPrice(): void
    {
        $this->loadFixturesFromFile('product/product_variant_with_original_price.yaml');

        $this->client->request('GET', '/api/v2/shop/product-variants/MUG_RED', [], [], self::CONTENT_TYPE_HEADER);
        $response = $this->client->getResponse();

        $this->assertResponse($response, 'shop/product/get_product_variant_with_price_response', Response::HTTP_OK);
    }

    public function testItReturnsProductVariantWithTranslations(): void
    {
        $fixtures = $this->loadFixturesFromFile('product/product_with_many_locales.yaml');

        /** @var ProductInterface $product */
        $product = $fixtures['product_variant_mug_blue'];
        $this->client->request(
            'GET',
            sprintf('/api/v2/shop/product-variants/%s', $product->getCode()),
            [],
            [],
            self::CONTENT_TYPE_HEADER
        );

        $this->assertResponse(
            $this->client->getResponse(),
            'shop/product/get_product_variant_with_default_locale_translation',
            Response::HTTP_OK
        );
    }

    public function testItReturnsNothingIfVariantNotFound(): void
    {
        $this->loadFixturesFromFile('product/product_with_many_locales.yaml');

        $this->client->request(
            'GET',
            '/api/v2/shop/product-variants/NON_EXISTING_VARIANT',
            [],
            [],
            self::CONTENT_TYPE_HEADER
        );
        $response = $this->client->getResponse();

        $this->assertSame(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}