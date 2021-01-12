<?php

use VendingMachine\VendingMachine;

require __DIR__ . '/vendor/autoload.php';

$vendingMachine = new VendingMachine();

$vendingMachine->insertCoinTransaction(1.00);
$vendingMachine->insertCoinTransaction(0.05);
$vendingMachine->insertCoinTransaction(0.05);
$vendingMachine->insertCoinTransaction(0.05);
$vendingMachine->dispenseItemTransaction(VendingMachine::ITEM_CODE_JUICE);

echo implode(', ', $vendingMachine->getActions());
$vendingMachine->resetActions();

echo "\n";

$vendingMachine->insertCoinTransaction(0.05);
$vendingMachine->insertCoinTransaction(0.05);
$vendingMachine->cancelTransaction();

echo implode(', ', $vendingMachine->getActions());

echo "\n";
