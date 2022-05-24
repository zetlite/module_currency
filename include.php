<?php
use Bitrix\Main\Loader;
Loader::registerAutoLoadClasses(
    'custom.currency',
    [
        'Custom\CurrencyAgent' => 'lib/Currency.php',
        'Custom\LinkedTable' => 'lib/linked.php',
    ]
);
