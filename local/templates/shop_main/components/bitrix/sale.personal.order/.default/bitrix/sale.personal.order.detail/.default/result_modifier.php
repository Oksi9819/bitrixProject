<?php

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;

$arResult['LANG'] = isset($_SESSION['LANG']) ? $_SESSION['LANG'] : LANGUAGE_ID;
PR($arResult['LANG']);
/* На какой переключаем */
Loc::setCurrentLang($arResult['LANG']);