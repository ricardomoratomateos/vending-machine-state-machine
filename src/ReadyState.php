<?php

namespace VendingMachine;

class ReadyState extends AbstractVendingMachineState
{
    public function hasEnoughChangeTransaction(string $productCode): void
    {
        $this->vendingMachine->setState(new HasChangeState($this->vendingMachine));

        if ($this->vendingMachine->hasEnoughChange($productCode)) {
            $this->vendingMachine->hasEnoughMoneyTransaction($productCode);
        } else {
            $this->vendingMachine->cancelTransaction($productCode);
        }
    }

    public function cancelTransaction(): void
    {
        $this->vendingMachine->setState(new TransactionCancelled($this->vendingMachine));
        $this->vendingMachine->returnCoinsTransaction();
    }
}
