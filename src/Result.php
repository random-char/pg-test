<?php

namespace Pg;

class Result
{
    public function __construct(
        private ?Buyer $buyer,
        private ?int $price,
    ) {}

    public function getBuyer(): ?Buyer
    {
        return $this->buyer;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function __toString(): string
    {
        return sprintf("Buyer %s, price to pay: %s", $this->getBuyer()?->getName() ?? '-', $this->price ?? '-');
    }
}