<?php

namespace Pg;

class Buyer
{
    public function __construct(
        private string $name,
    ) {}

    public function getName(): string
    {
        return $this->name;
    }
}