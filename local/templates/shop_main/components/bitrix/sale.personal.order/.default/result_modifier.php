<?php
use Bitrix\Main\Localization\Loc;


// Подключение файла с языковыми сообщениями
/* На какой переключаем */
$arResult['LANG'] = isset($_SESSION['LANG']) ? $_SESSION['LANG'] : LANGUAGE_ID;
Loc::setCurrentLang($arResult['LANG']);