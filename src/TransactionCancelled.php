<?php

namespace VendingMachine;

class TransactionCancelled extends AbstractVendingMachineState
{
    public function cancelTransaction(): void
    {
        $this->vendingMachine->insertCoinTransaction(0);
        $this->vendingMachine->setState(new ReadyState($this->vendingMachine));
    }
}
