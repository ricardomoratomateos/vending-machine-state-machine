<?php

namespace VendingMachine\Domain\VendingMachine\FMS;

use VendingMachine\Domain\VendingMachine\Entities\ItemInterface;

class DispenseChangeState extends AbstractVendingMachineState
{
    public function returnCoinsTransaction(?ItemInterface $item = null): void
    {
        $this->fms->setState(new ReadyState($this->vendingMachine, $this->fms));
    }
}
