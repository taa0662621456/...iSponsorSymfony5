<?php


namespace App\Entity\Product;

use App\Entity\BaseTrait;

use App\Repository\Product\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'product_price')]
#[ORM\Index(columns: ['slug'], name: 'product_price_idx')]
#[ORM\Entity(repositoryClass: ProductRepository::class)]
class ProductPrice
{
	use BaseTrait;

	#[ORM\Column(name: 'product_id', type: 'integer', nullable: false, options: ['default' => 0])]
	private int $productId = 0;

	#[ORM\Column(name: 'shopper_group_id', type: 'integer', nullable: false, options: ['default' => 0])]
	private int $shopperGroupId = 0;
	#[ORM\OneToOne(inversedBy: 'productPrice', targetEntity: Product::class)]
	#[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
	private Product|int $productPrice = 0;

	#[ORM\Column(name: 'override')]
	private ?bool $override = null;

	#[ORM\Column(name: 'product_override_price', type: 'decimal', precision: 7, scale: 2)]
	private ?int $productOverridePrice = null;

	#[ORM\Column(name: 'product_tax_id')]
	private ?int $productTaxId = null;

	#[ORM\Column(name: 'product_discount_id')]
	private ?int $productDiscountId = null;

	#[ORM\Column(name: 'product_currency')]
	private ?int $productCurrency = null;

	#[ORM\Column(name: 'product_price_publish_up', type: 'string', nullable: false)]
	private string $productPricePublishUp;

	#[ORM\Column(name: 'product_price_publish_down', type: 'string', nullable: false)]
	private string $productPricePublishDown;

	#[ORM\Column(name: 'price_quantity_start', type: 'integer', nullable: false, options: ['default' => 0])]
	private int $priceQuantityStart = 0;

	#[ORM\Column(name: 'price_quantity_end', type: 'integer', nullable: false, options: ['default' => 0])]
	private int $priceQuantityEnd = 0;
	public function getShopperGroupId(): int
	{
		return $this->shopperGroupId;
	}
	public function setShopperGroupId(int $shopperGroupId): void
	{
		$this->shopperGroupId = $shopperGroupId;
	}
	public function getProductPrice(): int
    {
		return $this->productPrice;
	}
	/**
	 * @param $productPrice
	 */
	public function setProductPrice($productPrice): void
	{
		$this->productPrice = $productPrice;
	}
	public function getOverride(): ?bool
	{
		return $this->override;
	}
	public function setOverride(?bool $override): void
	{
		$this->override = $override;
	}
	public function getProductOverridePrice(): ?int
    {
		return $this->productOverridePrice;
	}
	/**
	 * @param $productOverridePrice
	 */
	public function setProductOverridePrice($productOverridePrice): void
	{
		$this->productOverridePrice = $productOverridePrice;
	}
	public function getProductTaxId(): ?int
	{
		return $this->productTaxId;
	}
	public function setProductTaxId(?int $productTaxId): void
	{
		$this->productTaxId = $productTaxId;
	}
	public function getProductDiscountId(): ?int
	{
		return $this->productDiscountId;
	}
	public function setProductDiscountId(?int $productDiscountId): void
	{
		$this->productDiscountId = $productDiscountId;
	}
	public function getProductCurrency(): ?int
	{
		return $this->productCurrency;
	}
	public function setProductCurrency(?int $productCurrency): void
	{
		$this->productCurrency = $productCurrency;
	}
	public function getProductPricePublishUp(): string
	{
		return $this->productPricePublishUp;
	}
	/**
	 * @param $productPricePublishUp
	 */
	public function setProductPricePublishUp($productPricePublishUp): void
	{
		$this->productPricePublishUp = $productPricePublishUp;
	}
	public function getProductPricePublishDown(): string
	{
		return $this->productPricePublishDown;
	}
	/**
	 * @param $productPricePublishDown
	 */
	public function setProductPricePublishDown($productPricePublishDown): void
	{
		$this->productPricePublishDown = $productPricePublishDown;
	}
	public function getPriceQuantityStart(): int
	{
		return $this->priceQuantityStart;
	}
	public function setPriceQuantityStart(int $priceQuantityStart): void
	{
		$this->priceQuantityStart = $priceQuantityStart;
	}
	public function getPriceQuantityEnd(): int
	{
		return $this->priceQuantityEnd;
	}
	public function setPriceQuantityEnd(int $priceQuantityEnd): void
	{
		$this->priceQuantityEnd = $priceQuantityEnd;
	}
}





