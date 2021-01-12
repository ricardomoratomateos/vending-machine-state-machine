<?php

namespace VendingMachine;

class DispenseItemState extends AbstractVendingMachineState
{
    public function dispenseChangeTransaction(string $productCode): void
    {
        $this->vendingMachine->setState(new DispenseChangeState($this->vendingMachine));
        $this->vendingMachine->returnCoinsTransaction($productCode);
    }
}
