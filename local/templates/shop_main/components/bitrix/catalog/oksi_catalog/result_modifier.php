<?php

use Bitrix\Main\Loader;
use General\System\Data\IblockApi;


$arSort = array();

$arSelect = array(
    "SECTION_PAGE_URL"
);
//PR($arSelect);
$arFilter = array(
    "IBLOCK_ID" => $arParams["IBLOCK_ID"]
);
//PR($arFilter);
$rsSections = CIBlockSection::GetList($arSort, $arFilter, false, $arSelect);



/*if (Loader::includeModule("general.system")) {
    echo "МОДУЛЬ ПОДЛКЮЧЕН <br>";
    $res = CModule::IncludeModule('iblock');
    echo $res;
    $ib = new IblockApi(4, false, 'ID');
    //print_f($ib);
}*/
//$ess = $fg->GetSection('', $arSelect);
//PR($ess);

$GLOBALS['ALL_SECTIONS'] = $rsSections;
//PR($arParams);
//PR($GLOBALS['ALL_SECTIONS']);