<?php

namespace VendingMachine;

class HasChangeState extends AbstractVendingMachineState
{
    public function hasEnoughMoneyTransaction(string $productCode): void
    {
        $this->vendingMachine->setState(new HasMoneyState($this->vendingMachine));

        if ($this->vendingMachine->hasEnoughMoney($productCode)) {
            $this->vendingMachine->dispenseItemTransaction($productCode);
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
