<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
global $APPLICATION;
$APPLICATION->IncludeComponent(
    "currency.list",
    "",
    [
    ],
    false
);