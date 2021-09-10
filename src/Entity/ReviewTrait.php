<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

trait ReviewTrait
{
    /**
     * @var string|null
     * @ORM\Column(name="review", type="string", nullable=true)
     */
    private ?string $review;
    /**
     * @var string|null
     * @ORM\Column(name="review_lang", type="string", nullable=true)
     */
    private ?string $reviewLang;

    /**
     * @var string|null
     * @ORM\Column(name="review_type", type="string", nullable=true)
     */
    private ?string $reviewType;

    /**
     * @var int
     *
     * @ORM\Column(name="favorite", type="integer", nullable=false, options={"default" : 0})
     */
    private int $favourite = 0;

    /**
     * @return string|null
     */
    public function getReview(): ?string
    {
        return $this->review;
    }

    /**
     * @param string|null $review
     */
    public function setReview(?string $review): void
    {
        $this->review = $review;
    }


    /**
     * @return string|null
     */
    public function getReviewLang(): ?string
    {
        return $this->reviewLang;
    }

    /**
     * @param string|null $reviewLang
     */
    public function setReviewLang(?string $reviewLang): void
    {
        $this->reviewLang = $reviewLang;
    }

    /**
     * @return string|null
     */
    public function getReviewType(): ?string
    {
        return $this->reviewType;
    }

    /**
     * @param string|null $reviewType
     */
    public function setReviewType(?string $reviewType): void
    {
        $this->reviewType = $reviewType;
    }

    /**
     * @return int
     */
    public function getFavourite(): int
    {
        return $this->favourite;
    }

    /**
     * @param int $favourite
     */
    public function setFavourites(int $favourite): void
    {
        $this->favourite = $favourite;
    }
}
