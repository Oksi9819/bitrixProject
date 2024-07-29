<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CDatabase $DB */

$arResult["STAR_TEMPLATE"] = (
	!empty($arParams["STAR_TEMPLATE"])
		? $arParams["STAR_TEMPLATE"]
		: 'light'
);

$arResult['COMMENT'] = (
	!empty($arParams["COMMENT"])
		? $arParams["COMMENT"]
		: 'N'
);
?>