<?php

namespace VendingMachine;

interface VendingMachineStateInterface
{
    public function insertCoinTransaction(float $cash): void;
    public function dispenseChangeTransaction(string $productCode): void;
    public function dispenseItemTransaction(string $productCode): void;
    public function cancelTransaction(): void;
    public function returnCoinsTransaction(?string $productCode = null): void;
    public function hasEnoughMoneyTransaction(string $productCode): void;
    public function hasEnoughChangeTransaction(string $productCode): void;
}
