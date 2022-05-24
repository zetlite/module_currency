<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\EventManager;

class custom_currency extends CModule
{
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_ID = "custom.currency";
    var $errors = false;

    public function __construct()
    {
        $arModuleVersion = [];

        include __DIR__ . '/version.php';

        if (is_array($arModuleVersion) && array_key_exists(' VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        $this->MODULE_ID = str_replace("_", ".", get_class($this));

        $this->MODULE_NAME = Loc::getMessage("CUSTOM_INSTALL_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("CUSTOM_INSTALL_NAME");
        $this->PARTNER_NAME = Loc::getMessage("CUSTOM_SPER_PARTNER");
        $this->PARTNER_URI = Loc::getMessage("CUSTOM_PARTNER_URI");
    }

    public function doInstall()
    {
        $this->installDB();
        ModuleManager::registerModule($this->MODULE_ID);
        $this->installFiles();
        $this->installAgents();
    }


    public function doUninstall()
    {
        $this->unInstallDB();
        ModuleManager::unregisterModule($this->MODULE_ID);
        $this->unInstallFiles();
        $this->unInstallAgents();
    }

    function installDB()
    {
        global $DB, $APPLICATION;

        $this->errors = false;

        if (!$DB->Query("SELECT COUNT(ID) FROM custom_currency", true)) {
            $this->errors = $DB->RunSQLBatch($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/custom.currency/install/db/" . mb_strtolower($DB->type) . "/install.sql");
        }

        if ($this->errors !== false) {
            $APPLICATION->ThrowException(implode("", $this->errors));
            return false;
        }

        return true;
    }

    function unInstallDB()
    {
        global $DB, $APPLICATION;
        $this->errors = false;

        if (!isset($arParams["savedata"]) || $arParams["savedata"] != "Y") {
            $this->errors = $DB->RunSQLBatch($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/custom.currency/install/db/" . mb_strtolower($DB->type) . "/uninstall.sql");
            if ($this->errors !== false) {
                $APPLICATION->ThrowException(implode('', $this->errors));
                return false;
            }
        }

        return true;
    }

    function installEvents()
    {

    }

    function unInstallEvents()
    {

    }

    function installFiles()
    {
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/custom.currency/install/components", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components", true, true);
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/custom.currency/install/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin", true, true);
    }

    function unInstallFiles()
    {
        DeleteDirFiles($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/custom.currency/install/components", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components");
        DeleteDirFilesEx('/bitrix/admin/custom.currency_admin.php');
    }

    function installAgents()
    {
        CAgent::AddAgent("Custom\CurrencyAgent::getInfo();", "custom.currency", "Y", 86400);
    }

    function unInstallAgents()
    {
        CAgent::RemoveAgent("Custom\CurrencyAgent::getInfo();", "custom.currency");
    }
}