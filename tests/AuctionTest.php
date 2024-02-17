<?php
declare(strict_types=1);

use Pg\Auction;
use Pg\Buyer;
use Pg\ItemForSale;
use Pg\Result;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Auction::class)]
#[CoversClass(Buyer::class)]
#[CoversClass(ItemForSale::class)]
#[CoversClass(Result::class)]
final class AuctionTest extends TestCase
{
    private ItemForSale $itemForSale;

    protected function setUp(): void
    {
        $this->itemForSale = new ItemForSale(100);
    }

    public function testAuctionWithoutBuyers(): void
    {
        $auction = new Auction($this->itemForSale);
        $result = $auction->getResult();

        $this->assertNull($result->getBuyer());
        $this->assertNull($result->getPrice());
    }

    public function testAuctionWithWinner(): void
    {
        $auction = new Auction($this->itemForSale);
        $buyersData = [
            'a' => [110, 130],
            'b' => [],
            'c' => [125],
            'd' => [105, 115, 90],
            'e' => [132, 135, 140],
        ];
        $this->addBuyers($auction, $buyersData);

        $result = $auction->getResult();

        $this->assertEquals('e', $result->getBuyer()->getName());
        $this->assertEquals(130, $result->getPrice());
        $this->assertEquals('Buyer e, price to pay: 130', (string) $result);
    }

    public function testAuctionWithoutWinner(): void
    {
        $auction = new Auction($this->itemForSale);
        $buyersData = [
            'a' => [11, 13],
            'b' => [],
            'c' => [12],
            'd' => [10, 14, 9],
            'e' => [1, 15, 16],
        ];
        $this->addBuyers($auction, $buyersData);

        $result = $auction->getResult();

        $this->assertNull($result->getBuyer());
        $this->assertNull($result->getPrice());
    }

    public function testAuctionWithoutBids(): void
    {
        $auction = new Auction($this->itemForSale);
        $buyersData = [
            'a' => [],
            'b' => [],
            'c' => [],
            'd' => [],
            'e' => [],
        ];
        $this->addBuyers($auction, $buyersData);

        $result = $auction->getResult();

        $this->assertNull($result->getBuyer());
        $this->assertNull($result->getPrice());
    }

    public function testAuctionWithReservePrice(): void
    {
        $auction = new Auction($this->itemForSale);
        $buyersData = [
            'a' => [110],
            'b' => [90],
            'c' => [91, 92],
            'd' => [93, 94, 95],
            'e' => [99],
        ];
        $this->addBuyers($auction, $buyersData);

        $result = $auction->getResult();

        $this->assertEquals('a', $result->getBuyer()->getName());
        $this->assertEquals(100, $result->getPrice());
    }

    private function addBuyers(Auction $auction, array $buyersData): void
    {
        foreach ($buyersData as $name => $bids) {
            $buyer = new Buyer($name);
            foreach ($bids as $bid) {
                $auction->addBid($buyer, $bid);
            }
        }
    }
}
