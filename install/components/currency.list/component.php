<?

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

use Bitrix\Main\Loader;


IncludeModuleLangFile(__FILE__);
Loader::includeModule("custom.currency");

$sTableID = "custom_currency";
$oSort = new CAdminSorting($sTableID, "ID", "desc");
$lAdmin = new CAdminList($sTableID, $oSort);


$by = $_REQUEST["by"] ?: "ID";
$order = $_REQUEST["order"] ?: "asc";

$resLinks = \Custom\LinkedTable::getList([
    'order' => [$by => $order],
]);

$rsData = new CAdminResult($resLinks, $sTableID);

$rsData->NavStart();

$lAdmin->NavText($rsData->GetNavPrint(GetMessage("rub_nav")));

$lAdmin->AddHeaders([
    ["id" => "ID",
        "content" => "ID",
        "sort" => "ID",
        "align" => "right",
        "default" => true,
    ],
    ["id" => "DATE_CREATE",
        "content" => "DATE_CREATE",
        "sort" => "DATE_CREATE",
        "default" => true,
    ],
    ["id" => "CODE",
        "content" => "CODE",
        "sort" => "CODE",
        "default" => true,
    ],
    ["id" => "COURSE",
        "content" => "COURSE",
        "sort" => "COURSE",
        "default" => true,
    ],
]);


while ($arRes = $rsData->NavNext(true, "f_")) {
    $row =& $lAdmin->AddRow($f_ID, $arRes);
}


$lAdmin->AddFooter(
    [
        ["title" => GetMessage("MAIN_ADMIN_LIST_SELECTED"), "value" => $rsData->SelectedRowsCount()],
        ["counter" => true, "title" => GetMessage("MAIN_ADMIN_LIST_CHECKED"), "value" => "0"],
    ]
);

$lAdmin->CheckListMode();

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");


$lAdmin->DisplayList();

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
