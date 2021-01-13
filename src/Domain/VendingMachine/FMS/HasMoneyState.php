<?php

namespace VendingMachine\Domain\VendingMachine\FMS;

use VendingMachine\Domain\VendingMachine\Entities\ItemInterface;

class HasMoneyState extends AbstractVendingMachineState
{
    public function dispenseItemTransaction(ItemInterface $item): void
    {
        $this->fms->setState(new DispenseItemState($this->vendingMachine, $this->fms));
        $this->fms->dispenseChangeTransaction($item);
    }

    public function cancelTransaction(): void
    {
        $this->fms->setState(new TransactionCancelledState($this->vendingMachine, $this->fms));
        $this->fms->returnCoinsTransaction();
    }
}
