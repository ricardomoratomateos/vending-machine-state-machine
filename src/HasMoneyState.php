<?php

namespace VendingMachine;

class HasMoneyState extends AbstractVendingMachineState
{
    public function dispenseItemTransaction(string $productCode): void
    {
        $this->vendingMachine->setState(new DispenseItemState($this->vendingMachine));
        $this->vendingMachine->dispenseChangeTransaction($productCode);
    }

    public function cancelTransaction(): void
    {
        $this->vendingMachine->setState(new TransactionCancelled($this->vendingMachine));
        $this->vendingMachine->returnCoinsTransaction();
    }
}
