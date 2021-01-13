<?php

namespace VendingMachine\Application\VendingMachine\VendItem;

use VendingMachine\Application\VendingMachine\AbstractService;

class VendItemService extends AbstractService
{
    public function __invoke(
        VendItemRequest $request
    ): VendItemResponse {
        $vendingMachine = $this->repository->getVendingMachine();

        $item = $request->getItem();
        $response = $vendingMachine->vendItemOf($item);

        $this->repository->save($vendingMachine);

        return new VendItemResponse(
            $request->getItemAsString(),
            $response['change'],
            $response['was-sold']
        );
    }
}
