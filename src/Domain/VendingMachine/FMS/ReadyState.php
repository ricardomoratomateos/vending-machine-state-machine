<?php

namespace VendingMachine\Domain\VendingMachine\FMS;

use VendingMachine\Domain\VendingMachine\Entities\ItemInterface;

class ReadyState extends AbstractVendingMachineState
{
    public function hasEnoughChangeTransaction(ItemInterface $item): void
    {
        $this->fms->setState(new HasChangeState($this->vendingMachine, $this->fms));

        if ($this->vendingMachine->hasEnoughChange($item)) {
            $this->fms->hasEnoughMoneyTransaction($item);
        } else {
            $this->fms->cancelTransaction($item);
        }
    }

    public function cancelTransaction(): void
    {
        $this->fms->setState(new TransactionCancelledState($this->vendingMachine, $this->fms));
        $this->fms->returnCoinsTransaction();
    }
}
