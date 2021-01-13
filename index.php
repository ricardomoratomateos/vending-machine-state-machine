<?php

use VendingMachine\VendingMachine;

require __DIR__ . '/vendor/autoload.php';

$vendingMachine = new VendingMachine();

$vendingMachine->insertCoin(1.00);
$vendingMachine->insertCoin(0.05);
$vendingMachine->insertCoin(0.05);
$vendingMachine->insertCoin(0.05);
$vendingMachine->dispenseItem(VendingMachine::ITEM_CODE_JUICE);

echo implode(', ', $vendingMachine->getActions());
$vendingMachine->resetActions();

echo "\n";

$vendingMachine->insertCoin(0.05);
$vendingMachine->insertCoin(0.05);
$vendingMachine->cancelTransaction();

echo implode(', ', $vendingMachine->getActions());
$vendingMachine->resetActions();

echo "\n";
$vendingMachine->dispenseItem(VendingMachine::ITEM_CODE_JUICE);

echo "\n";
