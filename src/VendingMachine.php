<?php

namespace VendingMachine;

class VendingMachine implements VendingMachineStateInterface
{
    const ITEM_CODE_JUICE = 'juice';
    const ITEM_CODE_SODA = 'soda';
    const ITEM_CODE_WATER = 'water';

    /** @var float[] */
    private array $insertedCoins;

    /** @var float[] */
    private array $change;

    private VendingMachineStateInterface $state;

    private array $products = [
        self::ITEM_CODE_JUICE => [
            'price' => 1.00,
            'items' => 10,
        ],
        self::ITEM_CODE_SODA => [
            'price' => 1.50,
            'items' => 10,
        ],
        self::ITEM_CODE_WATER => [
            'price' => 0.65,
            'items' => 10,
        ],
    ];

    private array $actions;

    public function __construct()
    {
        $this->state = new ReadyState($this);
        $this->insertedCoins = [];

        foreach ([0.05, 0.10, 0.25, 1] as $coinValue) {
            foreach (range(1, 10) as $i) {
                $this->change[] = $coinValue;
            }
        }
    }

    public function getActions(): array
    {
        return $this->actions;
    }

    public function resetActions()
    {
        $this->actions = [];
    }

    public function setState(VendingMachineStateInterface $state): void
    {
        $this->state = $state;
    }

    /**
     * {@inheritDoc}
     */
    public function insertCoinTransaction(float $cash): void
    {
        $this->insertedCoins[] = $cash;
    }

    /**
     * {@inheritDoc}
     */
    public function dispenseChangeTransaction(string $productCode): void
    {
        $this->state->dispenseChangeTransaction($productCode);
    }

    /**
     * {@inheritDoc}
     */
    public function dispenseItemTransaction(string $productCode): void
    {
        if (!$this->hasEnoughMoney($productCode)) {
            // TODO: Thrown exception
        }
        if (!$this->hasEnoughChange($productCode)) {
            // TODO: Thrown exception
        }
        $this->actions[] = $productCode;
        $this->products[$productCode]['items'] -= 1;
        $this->state->dispenseItemTransaction($productCode);
    }

    /**
     * {@inheritDoc}
     */
    public function cancelTransaction(): void
    {
        $this->state->cancelTransaction();
    }

    /**
     * {@inheritDoc}
     */
    public function returnCoinsTransaction(?string $productCode = null): void
    {
        $actions = $productCode
            ? $this->calculateChange($productCode)
            : $this->insertedCoins;
        $this->actions = array_merge($this->actions, $actions);
        $this->insertedCoins = [];

        $this->state->returnCoinsTransaction($productCode);
    }

    private function calculateChange(string $productCode): array
    {
        $price = $this->products[$productCode]['price'];
        $valueOfInsertedCoins = $this->getInsertedCoinsValue();
        $valueToReturn = $valueOfInsertedCoins - $price;

        $coins = [];
        while ($valueToReturn > 0.00) {
            $coinToReturnIndex = array_keys($this->change)[0];
            $coinToReturn = $this->change[$coinToReturnIndex];

            for ($i = 1; $i < count($this->change); $i++) {
                if (
                    isset($this->change[$i]) &&
                    $this->change[$i] <= $valueToReturn &&
                    $this->change[$i] > $coinToReturn
                ) {
                    $coinToReturnIndex = $i;
                    $coinToReturn = $this->change[$i];
                }
            }

            $coins[] = $coinToReturn;

            $valueToReturn -= $coinToReturn;
            $valueToReturn = round($valueToReturn, 2);

            unset($this->change[$coinToReturnIndex]);
        }
        
        return $coins;
    }

    private function hasEnoughMoney(string $productCode): bool
    {
        $price = $this->products[$productCode]['price'];
        $valueOfInsertedCoins = $this->getInsertedCoinsValue();

        return $valueOfInsertedCoins >= $price;
    }

    private function hasEnoughChange(string $productCode): bool
    {
        $price = $this->products[$productCode]['price'];
        $valueOfInsertedCoins = $this->getInsertedCoinsValue();
        $valueToReturn = $valueOfInsertedCoins - $price;

        if ($valueToReturn == 0) {
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
                    $changeCopy[$i] <= $valueToReturn &&
                    $changeCopy[$i] > $coinToReturn
                ) {
                    $coinToReturnIndex = $i;
                    $coinToReturn = $changeCopy[$i];
                }
            }

            $coinsToReturn[] = $coinToReturn;
            $valueToReturn -= $coinToReturn;
            $valueToReturn = round($valueToReturn, 2);
            unset($changeCopy[$coinToReturnIndex]);
        }

        return $valueToReturn === 0.00;
    }

    private function getInsertedCoinsValue(): float
    {
        return array_sum($this->insertedCoins);
    }
}
