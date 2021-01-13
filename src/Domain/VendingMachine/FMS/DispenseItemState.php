<?php

namespace VendingMachine\Domain\VendingMachine\FMS;

use VendingMachine\Domain\VendingMachine\Entities\ItemInterface;

class DispenseItemState extends AbstractVendingMachineState
{
    public function dispenseChangeTransaction(ItemInterface $item): void
    {
        $this->fms->setState(new DispenseChangeState($this->vendingMachine, $this->fms));
        $this->fms->returnCoinsTransaction($item);
    }
}
