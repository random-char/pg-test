<?php

use Pg\Auction;
use Pg\Buyer;
use Pg\ItemForSale;

require __DIR__ . '/../vendor/autoload.php';

$itemForSale = new ItemForSale(100);
$auction = new Auction($itemForSale);

$buyersData = [
    'a' => [110, 130],
    'b' => [],
    'c' => [125],
    'd' => [105, 115, 90],
    'e' => [132, 135, 140],
];
foreach ($buyersData as $name => $bids) {
    $buyer = new Buyer($name);
    foreach ($bids as $bid) {
        $auction->addBid($buyer, $bid);
    }
}

$result = $auction->getResult();
echo $result . "\n";