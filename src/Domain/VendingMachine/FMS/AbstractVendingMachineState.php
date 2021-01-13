<?php

namespace VendingMachine\Domain\VendingMachine\FMS;

use VendingMachine\Domain\VendingMachine\Entities\ItemInterface;
use VendingMachine\Domain\VendingMachine\ValueObjects\Coin;
use VendingMachine\Domain\VendingMachine\VendingMachine;

abstract class AbstractVendingMachineState implements VendingMachineStateInterface
{
    protected VendingMachine $vendingMachine;

    protected VendingMachineStatesMachine $fms;

    public function __construct(
        VendingMachine $vendingMachine,
        VendingMachineStatesMachine $fms
    ) {
        $this->vendingMachine = $vendingMachine;
        $this->fms = $fms;
    }

    /**
     * {@inheritDoc}
     */
    public function insertCoinTransaction(Coin $coin): void
    {
        // TODO: Thrown exception
    }

    /**
     * {@inheritDoc}
     */
    public function dispenseChangeTransaction(ItemInterface $item): void
    {
        // TODO: Thrown exception
    }

    /**
     * {@inheritDoc}
     */
    public function dispenseItemTransaction(ItemInterface $item): void
    {
        // TODO: Thrown exception
    }

    /**
     * {@inheritDoc}
     */
    public function returnCoinsTransaction(?ItemInterface $item = null): void
    {
        // TODO: Thrown exception
    }

    /**
     * {@inheritDoc}
     */
    public function cancelTransaction(): void
    {
        // TODO: Thrown exception
    }

    /**
     * {@inheritDoc}
     */
    public function hasEnoughMoneyTransaction(ItemInterface $item): void
    {
        // TODO: Thrown exception
    }

    /**
     * {@inheritDoc}
     */
    public function hasEnoughChangeTransaction(ItemInterface $item): void
    {
        // TODO: Thrown exception
    }
}
