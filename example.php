<?php

require('Blocknado.php');

$blocknado = new Blocknado();

$cancel = $blocknado->cancel('fea236fd-72d1-4118-8bc5-05f3d7fe7bef');
print_r($cancel);

$order = $blocknado->order("fea236fd-72d1-4118-8bc5-05f3d7fe7bef");
print_r($order);

$buy = $blocknado->buy('BTC-LTC', '10', '0.00010000');
print_r($buy);

$sell = $blocknado->sell('BTC-LTC','10', '0.00020000');
print_r($sell);

$orderbook = $blocknado->orderbook("BTC-LTC");
print_r($orderbook);

$open = $blocknado->open("BTC-LTC");
print_r($open);

$open = $blocknado->open();
print_r($open);

$markets = $blocknado->markets();
print_r($markets);

$balances = $blocknado->balances();
print_r($balances);

?>
