<?php

namespace VendingMachine;

class DispenseChangeState extends AbstractVendingMachineState
{
    public function returnCoinsTransaction(?string $productCode = null): void
    {
        $this->vendingMachine->setState(new ReadyState($this->vendingMachine));
    }
}
