<?

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

use Bitrix\Main\Loader;


IncludeModuleLangFile(__FILE__);
Loader::includeModule("custom.currency");

$POST_RIGHT = $APPLICATION->GetGroupRight("subscribe");

if ($POST_RIGHT == "D")
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
?>
<?
$sTableID = "custom_currency";
$oSort = new CAdminSorting($sTableID, "ID", "desc");
$lAdmin = new CAdminList($sTableID, $oSort);


// обработка одиночных и групповых действий
$by = $_REQUEST["by"] ?: "id";
$order = $_REQUEST["order"] ?: "asc";


// выберем список рассылок
echo ('<pre>');
    var_dump($by, $order);
echo('</pre>');
$resLinks = \Custom\LinkedTable::getList([
    'order' => [$by => $order],
]);

// преобразуем список в экземпляр класса CAdminResult
$rsData = new CAdminResult($resLinks, $sTableID);

// аналогично CDBResult инициализируем постраничную навигацию.
$rsData->NavStart();

// отправим вывод переключателя страниц в основной объект $lAdmin
$lAdmin->NavText($rsData->GetNavPrint(GetMessage("rub_nav")));

// ******************************************************************** //
//                ПОДГОТОВКА СПИСКА К ВЫВОДУ                            //
// ******************************************************************** //

$lAdmin->AddHeaders([
    ["id" => "ID",
        "content" => "ID",
        "sort" => "id",
        "align" => "right",
        "default" => true,
    ],
    ["id" => "DATE_CREATE",
        "content" => "DATE_CREATE",
        "sort" => "date",
        "default" => true,
    ],
    ["id" => "CODE",
        "content" => "CODE",
        "sort" => "right",
        "default" => true,
    ],
    ["id" => "COURSE",
        "content" => "COURSE",
        "sort" => "course",
        "default" => true,
    ],
]);


while ($arRes = $rsData->NavNext(true, "f_")):

    // создаем строку. результат - экземпляр класса CAdminListRow
    $row =& $lAdmin->AddRow($f_ID, $arRes);

    // далее настроим отображение значений при просмотре и редаткировании списка

    // параметр NAME будет редактироваться как текст, а отображаться ссылкой
    $row->AddInputField("NAME", ["size" => 20]);
    $row->AddViewField("NAME", '<a href="custom.currency_admin.php?ID=' . $f_ID . '&lang=' . LANG . '">' . $f_NAME . '</a>');

    // параметр LID будет редактироваться в виде выпадающего списка языков
    $row->AddEditField("LID", CLang::SelectBox("LID", $f_LID));

    // параметр SORT будет редактироваться текстом
    $row->AddInputField("SORT", ["size" => 20]);

    // флаги ACTIVE и VISIBLE будут редактироваться чекбоксами
    $row->AddCheckField("ACTIVE");
    $row->AddCheckField("VISIBLE");

    // параметр AUTO будет отображаться в виде "Да" или "Нет", полужирным при редактировании
    $row->AddViewField("AUTO", $f_AUTO == "Y" ? GetMessage("POST_U_YES") : GetMessage("POST_U_NO"));
    $row->AddEditField("AUTO", "<b>" . ($f_AUTO == "Y" ? GetMessage("POST_U_YES") : GetMessage("POST_U_NO")) . "</b>");

    // сформируем контекстное меню
    $arActions = [];

    // редактирование элемента
    $arActions[] = [
        "ICON" => "edit",
        "DEFAULT" => true,
        "TEXT" => GetMessage("rub_edit"),
        "ACTION" => $lAdmin->ActionRedirect("custom.currency_admin.php?ID=" . $f_ID),
    ];

    // удаление элемента
    if ($POST_RIGHT >= "W")
        $arActions[] = [
            "ICON" => "delete",
            "TEXT" => GetMessage("rub_del"),
            "ACTION" => "if(confirm('" . GetMessage('rub_del_conf') . "')) " . $lAdmin->ActionDoGroup($f_ID, "delete"),
        ];

    // вставим разделитель
    $arActions[] = ["SEPARATOR" => true];

    // проверка шаблона для автогенерируемых рассылок
    if (strlen($f_TEMPLATE) > 0 && $f_AUTO == "Y")
        $arActions[] = [
            "ICON" => "",
            "TEXT" => GetMessage("rub_check"),
            "ACTION" => $lAdmin->ActionRedirect("template_test.php?ID=" . $f_ID),
        ];

    // если последний элемент - разделитель, почистим мусор.
    if (is_set($arActions[count($arActions) - 1], "SEPARATOR"))
        unset($arActions[count($arActions) - 1]);

    // применим контекстное меню к строке
    $row->AddActions($arActions);

endwhile;

// резюме таблицы
$lAdmin->AddFooter(
    [
        ["title" => GetMessage("MAIN_ADMIN_LIST_SELECTED"), "value" => $rsData->SelectedRowsCount()], // кол-во элементов
        ["counter" => true, "title" => GetMessage("MAIN_ADMIN_LIST_CHECKED"), "value" => "0"], // счетчик выбранных элементов
    ]
);

// групповые действия
$lAdmin->AddGroupActionTable([
    "delete" => GetMessage("MAIN_ADMIN_LIST_DELETE"), // удалить выбранные элементы
    "activate" => GetMessage("MAIN_ADMIN_LIST_ACTIVATE"), // активировать выбранные элементы
    "deactivate" => GetMessage("MAIN_ADMIN_LIST_DEACTIVATE"), // деактивировать выбранные элементы
]);

// ******************************************************************** //
//                АДМИНИСТРАТИВНОЕ МЕНЮ                                 //
// ******************************************************************** //

// сформируем меню из одного пункта - добавление рассылки


// ******************************************************************** //
//                ВЫВОД                                                 //
// ******************************************************************** //

// альтернативный вывод
$lAdmin->CheckListMode();

?>
<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php"); // второй общий пролог

// выведем таблицу списка элементов
$lAdmin->DisplayList();
?>

<?
// завершение страницы
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
?>