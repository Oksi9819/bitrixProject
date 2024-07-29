<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;

Loc::loadMessages(__FILE__);

class mynew_shop extends CModule
{
    public function __construct()
    {

        if (file_exists(__DIR__ . "/version.php")) {

            $arModuleVersion = array();

            include_once(__DIR__ . "/version.php");

            $this->MODULE_ID = str_replace("_", ".", get_class($this));
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
            $this->MODULE_NAME = Loc::getMessage("MYNEW_SHOP_NAME");
            $this->MODULE_DESCRIPTION = Loc::getMessage("MYNEW_SHOP_DESCRIPTION");
            $this->PARTNER_NAME = Loc::getMessage("MYNEW_SHOP_PARTNER_NAME");
            $this->PARTNER_URI = Loc::getMessage("MYNEW_SHOP_PARTNER_URI");
        }

        return false;
    }

    function InstallFiles(): bool
    {
        CopyDirFiles(
            __DIR__ . "/components",
            Application::getDocumentRoot() . "/bitrix/components",
            true,
            true
        );
        return false;
    }

    public function UnInstallFiles(): bool
    {
        Directory::deleteDirectory(
            Application::getDocumentRoot() . "/local/components/" . $this->MODULE_ID
        );
        return false;
    }

    public function DoInstall(): bool
    {

        global $APPLICATION;

        if (CheckVersion(ModuleManager::getVersion("main"), "14.00.00")) {
            $this->InstallFiles();
            ModuleManager::registerModule($this->MODULE_ID);
        } else {

            $APPLICATION->ThrowException(
                Loc::getMessage("MYNEW_SHOP_INSTALL_ERROR_VERSION")
            );
        }

        $APPLICATION->IncludeAdminFile(
            Loc::getMessage("MYNEW_SHOP_INSTALL_TITLE") . " \"" . Loc::getMessage("MYNEW_SHOP_NAME") . "\"",
            __DIR__ . "/step.php"
        );

        return false;
    }

    public function DoUninstall(): bool
    {

        global $APPLICATION;

        $this->UnInstallFiles();

        ModuleManager::unRegisterModule($this->MODULE_ID);

        $APPLICATION->IncludeAdminFile(
            Loc::getMessage("MYNEW_SHOP_UNINSTALL_TITLE") . " \"" . Loc::getMessage("MYNEW_SHOP_NAME") . "\"",
            __DIR__ . "/unstep.php"
        );

        return false;
    }
}