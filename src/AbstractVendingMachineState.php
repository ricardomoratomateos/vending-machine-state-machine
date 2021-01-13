<?php

namespace VendingMachine;

abstract class AbstractVendingMachineState implements VendingMachineStateInterface
{
    protected VendingMachine $vendingMachine;

    public function __construct(VendingMachine $vendingMachine)
    {
        $this->vendingMachine = $vendingMachine;
    }

    /**
     * {@inheritDoc}
     */
    public function insertCoinTransaction(float $cash): void
    {
        // TODO: Thrown exception
    }

    /**
     * {@inheritDoc}
     */
    public function dispenseChangeTransaction(string $productCode): void
    {
        // TODO: Thrown exception
    }

    /**
     * {@inheritDoc}
     */
    public function dispenseItemTransaction(string $productCode): void
    {
        // TODO: Thrown exception
    }

    /**
     * {@inheritDoc}
     */
    public function returnCoinsTransaction(?string $productCode = null): void
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
    public function hasEnoughMoneyTransaction(string $productCode): void
    {
        // TODO: Thrown exception
    }

    /**
     * {@inheritDoc}
     */
    public function hasEnoughChangeTransaction(string $productCode): void
    {
        // TODO: Thrown exception
    }
}
