<?php

namespace VendingMachine;

abstract class AbstractVendingMachineState implements VendingMachineStateInterface
{
    protected VendingMachine $vendingMachine;

    public function __construct(VendingMachine $vendingMachine)
    {
        $this->vendingMachine = $vendingMachine;
    }

    public function insertCoinTransaction(float $cash): void
    {
        // TODO: Thrown exception
    }

    public function dispenseChangeTransaction(string $productCode): void
    {
        // TODO: Thrown exception
    }

    public function dispenseItemTransaction(string $productCode): void
    {
        // TODO: Thrown exception
    }

    public function returnCoinsTransaction(?string $productCode = null): void
    {
        // TODO: Thrown exception
    }

    public function cancelTransaction(): void
    {
        // TODO: Thrown exception
    }
}
