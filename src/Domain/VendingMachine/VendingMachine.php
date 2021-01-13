<?php

namespace VendingMachine\Domain\VendingMachine;

use VendingMachine\Domain\VendingMachine\Entities\ItemInterface;
use VendingMachine\Domain\VendingMachine\FMS\DispenseChangeState;
use VendingMachine\Domain\VendingMachine\FMS\VendingMachineStatesMachine;
use VendingMachine\Domain\VendingMachine\ValueObjects\Coin;
use VendingMachine\Domain\VendingMachine\ValueObjects\Price;
use VendingMachine\Domain\VendingMachine\ValueObjects\VendingMachineProduct;

class VendingMachine
{
    /** @var VendingMachineProduct[] */
    private array $products;

    /** @var Coin[] */
    private array $change;

    /** @var Coin[] */
    private array $insertedCoins;

    private VendingMachineStatesMachine $fms;

    /**
     * @param VendingMachineProduct[] $products
     * @param Coin[] $change
     */
    public function __construct(
        array $products,
        array $change
    ) {
        $this->fms = new VendingMachineStatesMachine($this);
        $this->products = $products;
        $this->change = $change;
        $this->insertedCoins = [];
    }

    /**
     * @param Coin[] $coins
     */
    public function addChange(array $coins): void
    {
        $this->change = array_merge($this->change, $coins);
    }

    public function insertCoin(Coin $coin): void
    {
        $this->insertedCoins[] = $coin;
        $this->fms->insertCoinTransaction($coin);
    }

    /**
     * @return Coin[]
     */
    public function returnCoins(): array
    {
        $this->fms->cancelTransaction();

        $insertedCoins = $this->insertedCoins;
        $this->insertedCoins = [];

        return $insertedCoins;
    }

    public function addItemsOf(ItemInterface $item, int $quantity): void
    {
        foreach ($this->products as $product) {
            if ($product->getItem() === $item) {
                $product->addItems($quantity);
            }
        }
    }

    public function vendItemOf(ItemInterface $item): array
    {
        $this->fms->hasEnoughChangeTransaction($item);

        $response = null;
        if ($this->fms->getLastState() instanceof DispenseChangeState) {
            $productToVend = $this->findProduct($item);
            $response = [
                'was-sold' => true,
                'change' => $this->calculateChange($productToVend->getPrice()),
            ];
        } else {
            $response = [
                'was-sold' => false,
                'change' => $this->insertedCoins,
            ];
        }

        return $response;
    }

    public function hasEnoughMoney(ItemInterface $item): bool
    {
        $productToVend = $this->findProduct($item);
        $valueOfInsertedCoins = $this->transformInsertedCoinsToFloat();

        return $valueOfInsertedCoins >= $productToVend->getPrice()->getValue();
    }

    public function hasEnoughChange(ItemInterface $item): bool
    {
        $productToVend = $this->findProduct($item);

        $valueOfInsertedCoins = $this->transformInsertedCoinsToFloat();
        $valueToReturn = $valueOfInsertedCoins - $productToVend->getPrice()->getValue();

        if ($valueToReturn <= 0) {
            return true;
        }
        if (empty($this->change)) {
            return false;
        }

        $changeCopy = $this->change;
        while ($valueToReturn > 0.00) {
            $coinToReturnIndex = array_keys($changeCopy)[0];
            $coinToReturn = $changeCopy[$coinToReturnIndex];
            for ($i = 1; $i < count($changeCopy); $i++) {
                if (
                    isset($changeCopy[$i]) &&
                    $changeCopy[$i]->getValue() <= $valueToReturn &&
                    $changeCopy[$i]->getValue() > $coinToReturn->getValue()
                ) {
                    $coinToReturnIndex = $i;
                    $coinToReturn = $changeCopy[$i];
                }
            }

            $coinsToReturn[] = $coinToReturn;
            $valueToReturn -= $coinToReturn->getValue();
            $valueToReturn = round($valueToReturn, 2);

            unset($changeCopy[$coinToReturnIndex]);
        }

        return $valueToReturn === 0.00;
    }

    private function calculateChange(Price $price): array
    {
        $valueOfInsertedCoins = $this->transformInsertedCoinsToFloat();

        $coinsToReturn = [];
        $valueToReturn = $valueOfInsertedCoins - $price->getValue();

        while ($valueToReturn > 0.00) {
            $coinToReturnIndex = array_keys($this->change)[0];
            $coinToReturn = $this->change[$coinToReturnIndex];

            for ($i = 1; $i < count($this->change); $i++) {
                if (
                    isset($this->change[$i]) &&
                    $this->change[$i]->getValue() <= $valueToReturn &&
                    $this->change[$i]->getValue() > $coinToReturn->getValue()
                ) {
                    $coinToReturnIndex = $i;
                    $coinToReturn = $this->change[$i];
                }
            }

            $coinsToReturn[] = $coinToReturn;
            $valueToReturn -= $coinToReturn->getValue();
            $valueToReturn = round($valueToReturn, 2);
            unset($this->change[$coinToReturnIndex]);
        }
        
        $this->insertedCoins = [];

        return $coinsToReturn;
    }

    private function findProduct(ItemInterface $item): ?VendingMachineProduct
    {
        $productToVend = null;
        foreach ($this->products as $product) {
            $productClass = get_class($product->getItem());
            if ($productClass === get_class($item)) {
                $productToVend = $product;
                break;
            }
        }

        return $productToVend;
    }

    private function transformInsertedCoinsToFloat(): float
    {
        $valueOfInsertedCoins = 0.0;
        foreach ($this->insertedCoins as $insertedCoin) {
            $valueOfInsertedCoins += $insertedCoin->getValue();
        }

        return $valueOfInsertedCoins;
    }
}
