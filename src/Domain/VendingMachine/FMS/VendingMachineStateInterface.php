<?php

namespace VendingMachine\Domain\VendingMachine\FMS;

use VendingMachine\Domain\VendingMachine\Entities\ItemInterface;
use VendingMachine\Domain\VendingMachine\ValueObjects\Coin;

interface VendingMachineStateInterface
{
    public function insertCoinTransaction(Coin $coin): void;
    public function dispenseChangeTransaction(ItemInterface $item): void;
    public function dispenseItemTransaction(ItemInterface $item): void;
    public function cancelTransaction(): void;
    public function returnCoinsTransaction(?ItemInterface $item = null): void;
    public function hasEnoughMoneyTransaction(ItemInterface $item): void;
    public function hasEnoughChangeTransaction(ItemInterface $item): void;
}
