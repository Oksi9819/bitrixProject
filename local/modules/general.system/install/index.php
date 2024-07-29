<?php
//подключаем основные классы для работы с модулем

use Bitrix\Main\SiteTable;
use Bitrix\Main\Entity\Base;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\UrlRewriter;
use Bitrix\Main\EventManager;
use Bitrix\Crm\Conversion\LeadConversionConfig;

Loc::loadMessages(__FILE__);

if (class_exists("general_system"))
    return;

class general_system extends CModule
{
    var $MODULE_ID = 'general.system';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_GROUP_RIGHTS = 'N'; //используем ли индивидуальную схему распределения прав доступа, мы ставим N, так как не используем ее
    var $SITE_ID;

    var $MODULE_EVENTS;
    var $ERRORS;

    function __construct()
    {
        $arModuleVersion = [];
        include __DIR__ . '/version.php'; //подключаем версию модуля (файл будет следующим в списке)
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = Loc::getMessage("RGI_MODULE_NAME"); // название модуля
        $this->MODULE_DESCRIPTION = Loc::getMessage("RGI_MODULE_DESCR"); //описание модуля
        $this->PARTNER_NAME = Loc::getMessage("RGI_MODULE_NAME"); //название компании партнера предоставляющей модуль
        $this->PARTNER_URI = Loc::getMessage("RGI_PARTNER_URI"); //адрес сайта компании партнера


        $site = SiteTable::getList(['filter' => ['DEF' => 'Y']])->fetch();
        $this->SITE_ID = $site['LID'];

        $this->MODULE_EVENTS = [
            [
                'fromModule' => 'crm',
                'formEvent' => 'OnAfterCrmDealUpdate', //после изменения сделки.
                'toModule' => $this->MODULE_ID,
                'toClass' => 'General\System\Event\Deal',
                'toMethod' => 'onAfterUpdate'
            ],
            [
                'fromModule' => 'crm',
                'formEvent' => 'OnBeforeCrmDealUpdate', //перед изменением сделки.
                'toModule' => $this->MODULE_ID,
                'toClass' => 'General\System\Event\Deal',
                'toMethod' => 'onBeforeUpdate'
            ],
        ];
    }


    function installEvent()
    {
        if (!empty($this->MODULE_EVENTS)) {
            $eventManager = EventManager::getInstance();
            foreach ($this->MODULE_EVENTS as $ev) {
                 $eventManager->registerEventHandlerCompatible(
                    $ev['fromModule'],
                    $ev['formEvent'],
                    $ev['toModule'],
                    $ev['toClass'],
                    $ev['toMethod']
                );
            }
        }
    }

    function uninstallEvent()
    {
        if (!empty($this->MODULE_EVENTS)) {
            $eventManager = EventManager::getInstance();
            foreach ($this->MODULE_EVENTS as $ev) {
                $eventManager->unRegisterEventHandler(
                    $ev['fromModule'],
                    $ev['formEvent'],
                    $ev['toModule'],
                    $ev['toClass'],
                    $ev['toMethod']
                );
            }
        }
    }

    //здесь мы описываем все, что делаем до инсталляции модуля, мы добавляем наш модуль в регистр и вызываем метод создания таблицы
    function doInstall()
    {

        global $USER, $APPLICATION;

        if ($USER->IsAdmin()) {

            //$this->installEvent();

            if (!empty($this->ERRORS)) {
                $APPLICATION->ThrowException(implode("<br>", $this->ERRORS));
                return false;
            }

            ModuleManager::registerModule($this->MODULE_ID);
        }
    }

    //вызываем метод удаления таблицы и удаляем модуль из регистра
    public function doUninstall()
    {
        global $USER, $APPLICATION;

        if ($USER->IsAdmin()) {

            //$this->uninstallEvent();

            if (!empty($this->ERRORS)) {
                $APPLICATION->ThrowException(implode("<br>", $this->ERRORS));
                return false;
            }

            ModuleManager::unRegisterModule($this->MODULE_ID);

        }
    }
}