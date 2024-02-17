<?php

namespace Pg;

class Auction
{
    private array $buyers = [];
    /*
     * $bids => [
     *      'buyerName1' => [
     *          'max' => 123,
     *          //historical data can be added if needed
     *          'historical' => [100, 110, 123],
     *      ],
     *      ...
     * ]
     */
    private array $bids = [];

    public function __construct(
        private ItemForSale $itemForSale
    ) {}

    public function addBuyer(Buyer $buyer): void
    {
        if (!array_key_exists($buyer->getName(), $this->buyers)) {
            $this->buyers[$buyer->getName()] = $buyer;
        }
    }

    public function addBid(Buyer $buyer, int $bid): void
    {
        $this->addBuyer($buyer);

        if (array_key_exists($buyer->getName(), $this->bids)) {
            //$this->bids[$buyer->getName()]['historical'] = [$bid];
            if ($bid > $this->bids[$buyer->getName()]['max']) {
                $this->bids[$buyer->getName()]['max'] = $bid;
            }
        } else {
            $this->bids[$buyer->getName()] = [
                'max' => $bid,
                //'historical' => [$bid],
            ];
        }
    }

    public function getResult(): Result
    {
        $reservePrice = $this->itemForSale->getReservePrice();

        $highestBids = [];
        foreach ($this->bids as $buyerName => $bidsData) {
            if (empty($bidsData)) continue;

            $maxBid = $bidsData['max'];
            if ($maxBid >= $reservePrice) {
                $highestBids[] = ['maxBid' => $maxBid, 'buyer' => $buyerName];
            }
        }

        if (empty($highestBids)) {
            return new Result(null, null);
        }

        usort($highestBids, function ($a, $b) {
            return $a['maxBid'] <=> $b['maxBid'];
        });

        $priceToPay = count($highestBids) > 1 ? $highestBids[count($highestBids) - 2]['maxBid'] : $reservePrice;
        $highestBidder = $this->buyers[$highestBids[count($highestBids) - 1]['buyer']];

        return new Result($highestBidder, $priceToPay);
    }
}