<?php

namespace VendingMachine\Domain\VendingMachine\FMS;

use VendingMachine\Domain\VendingMachine\Entities\ItemInterface;

class TransactionCancelledState extends AbstractVendingMachineState
{
    public function cancelTransaction(): void
    {
        $this->fms->setState(new ReadyState($this->vendingMachine, $this->fms));
    }

    public function returnCoinsTransaction(?ItemInterface $item = null): void
    {
        $this->fms->setState(new ReadyState($this->vendingMachine, $this->fms));
    }
}
