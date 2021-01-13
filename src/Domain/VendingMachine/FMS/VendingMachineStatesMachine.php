<?php

namespace VendingMachine\Domain\VendingMachine\FMS;

use VendingMachine\Domain\VendingMachine\Entities\ItemInterface;
use VendingMachine\Domain\VendingMachine\ValueObjects\Coin;
use VendingMachine\Domain\VendingMachine\VendingMachine;

class VendingMachineStatesMachine implements VendingMachineStateInterface
{
    private VendingMachineStateInterface $state;

    private VendingMachineStateInterface $lastState;

    public function __construct(VendingMachine $vendingMachine)
    {
        $this->state = new ReadyState($vendingMachine, $this);
        $this->lastState = $this->state;
    }

    public function getActualState(): VendingMachineStateInterface
    {
        return $this->state;
    }

    public function getLastState(): VendingMachineStateInterface
    {
        return $this->lastState;
    }

    public function setState(VendingMachineStateInterface $state): void
    {
        $this->lastState = $this->state;
        $this->state = $state;
    }

    /**
     * {@inheritDoc}
     */
    public function insertCoinTransaction(Coin $coin): void
    {
        $this->state->insertCoinTransaction($coin);
    }

    /**
     * {@inheritDoc}
     */
    public function dispenseChangeTransaction(ItemInterface $item): void
    {
        $this->state->dispenseChangeTransaction($item);
    }

    /**
     * {@inheritDoc}
     */
    public function dispenseItemTransaction(ItemInterface $item): void
    {
        // Remove item from vending machine
        $this->state->dispenseItemTransaction($item);
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
    public function returnCoinsTransaction(?ItemInterface $item = null): void
    {
        /*$actions = $item
            ? $this->calculateChange($item)
            : $this->insertedCoins;
        $this->actions = array_merge($this->actions, $actions);
        $this->insertedCoins = [];*/

        $this->state->returnCoinsTransaction($item);
    }

    /**
     * {@inheritDoc}
     */
    public function hasEnoughMoneyTransaction(ItemInterface $item): void
    {
        $this->state->hasEnoughMoneyTransaction($item);
    }

    /**
     * {@inheritDoc}
     */
    public function hasEnoughChangeTransaction(ItemInterface $item): void
    {
        $this->state->hasEnoughChangeTransaction($item);
    }
}
