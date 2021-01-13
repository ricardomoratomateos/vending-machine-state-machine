<?php

namespace VendingMachine\Domain\VendingMachine\FMS;

use VendingMachine\Domain\VendingMachine\Entities\ItemInterface;

class HasChangeState extends AbstractVendingMachineState
{
    public function hasEnoughMoneyTransaction(ItemInterface $item): void
    {
        $this->fms->setState(new HasMoneyState($this->vendingMachine, $this->fms));

        if ($this->vendingMachine->hasEnoughMoney($item)) {
            $this->fms->dispenseItemTransaction($item);
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
