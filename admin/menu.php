<?
$bxEventManager = \Bitrix\Main\EventManager::getInstance();

$bxEventManager->addEventHandler(
    "main",
    "OnBuildGlobalMenu",
    "onBuildMenuCustomCurrency"
);

function onBuildMenuCustomCurrency(&$arGlobalMenu, &$arModuleMenu)
{
    if (!defined('CUSTOM_CURRENCY_MENU_INCLUDED')) {
        define('CUSTOM_CURRENCY_MENU_INCLUDED', true);
        IncludeModuleLangFile(__FILE__);

        $moduleID = 'custom.currency';
        $GLOBALS['APPLICATION']->SetAdditionalCss("/bitrix/css/" . $moduleID . "/menu.css");
        $GLOBALS['APPLICATION']->SetAdditionalCss("/bitrix/css/" . $moduleID . "/admin.css");

        if ($GLOBALS['APPLICATION']->GetGroupRight($moduleID) >= 'R') {
            $arMenu = [
                'menu_id' => 'customcurrency',
                'text' => 'меню курсов',
                'title' => 'меню курсов',
                'sort' => 1000,
                'items_id' => 'global_menu_customcurrency',
                'items' => [
                    [
                        'text' => 'меню курсов',
                        'title' => 'меню курсов',
                        'sort' => 30,
                        'icon' => 'imi_control_center',
                        'page_icon' => 'pi_control_center',
                        'items_id' => 'control_center',
                        'url' => "custom.currency_admin.php",
                    ],
                ],
            ];

            $arGlobalMenu[] = $arMenu;
        }
    }
}
