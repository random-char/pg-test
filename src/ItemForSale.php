<?php

namespace Pg;

class ItemForSale
{
    public function __construct(
        private int $reservePrice,
    ) {}

    public function getReservePrice(): int
    {
        return $this->reservePrice;
    }
}