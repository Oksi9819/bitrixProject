<?php

use Bitrix\Main\Loader;
use General\System\Data\Iblock;

Loader::includeModule("general.system");

//Sections of catalog
$ibObj = new Iblock(4, false, 'ID');
$arFilt = array();
$arSel = array('SECTION_PAGE_URL');
$arSections = $ibObj->GetSection($arFilt, $arSel);
//PR($arSections);
$arResult['SECTIONS_DETAILS'] = $arSections;
