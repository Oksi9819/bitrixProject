<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CDatabase $DB */
/** @var CBitrixComponent $component */

$this->setFrameMode(true);
$APPLICATION->AddHeadScript('/local/templates/shop_main/components/bitrix/catalog/oksi_catalog/bitrix/catalog.section.list/.default/script.js');
?>
<?php //PR($arResult['SECTIONS']); ?>
<div class="left-sidebar">
    <h2><?= GetMessage('SECTIONLIST_HEADER') ?></h2>
    <div class="panel-group category-products" id="accordian"><!--products categories-->
        <?
        $arSections = $arResult['SECTIONS'];
        $sectionsAmount = count($arSections);
        if ($sectionsAmount > 0) {
        for ($i = 0;
        $i < $sectionsAmount;
        $i++) {
        if ($arSections[$i]['RELATIVE_DEPTH_LEVEL'] == 1) {
        if ($i !== 0 && $arSections[$i - 1]['RELATIVE_DEPTH_LEVEL'] !== 1) {
        ?></ul>
    </div>
</div>
    </div>
<?
}
?>
<div class="panel panel-default">
<div class="panel-heading">
<h4 class="panel-title">
<? if ($i === ($sectionsAmount - 1) || $arSections[$i + 1]['RELATIVE_DEPTH_LEVEL'] === 1) {
    ?><a href="<?= $arSections[$i]['SECTION_PAGE_URL'] ?>">
    <? if (strtoupper(LANGUAGE_ID) == 'RU') {
        echo $arSections[$i]['NAME'];
    } else {
        echo $arResult['SECTIONS_DETAILS'][$arSections[$i]['ID']]['UF_SECTION_NAME_' . strtoupper(LANGUAGE_ID) . ''];
    } ?>
    </a></h4>
    </div>
    </div>
<? } else {
?><a data-toggle="collapse" data-id="<?= $arSections[$i]['ID'] ?>" data-parent="#accordian"
     href="#<? if (strtoupper(LANGUAGE_ID) == 'RU') {
         echo $arSections[$i]['NAME'];
     } else {
         echo $arResult['SECTIONS_DETAILS'][$arSections[$i]['ID']]['UF_SECTION_NAME_' . strtoupper(LANGUAGE_ID) . ''];
     } ?>" class="collapsed">
<span class="badge pull-right"><i class="fa fa-plus"></i></span>
<? if (strtoupper(LANGUAGE_ID) == 'RU') {
    echo $arSections[$i]['NAME'];
} else {
    echo $arResult['SECTIONS_DETAILS'][$arSections[$i]['ID']]['UF_SECTION_NAME_' . strtoupper(LANGUAGE_ID) . ''];
} ?>
</a>
    </h4>
    </div>
<div id="<?= $arSections[$i]['ID'] ?>" data-parent="<?= $arSections[$i]['ID'] ?>" class="deepmenu panel-collapse collapse ">
<div class="panel-body">
<ul>
<?
}
}

if ($arSections[$i]['RELATIVE_DEPTH_LEVEL'] === 2) {
?>
<li><a href="<?= $arSections[$i]['SECTION_PAGE_URL'] ?>">
        <? if (strtoupper(LANGUAGE_ID) == 'RU') {
            echo $arSections[$i]['NAME'];
        } else {
            echo $arResult['SECTIONS_DETAILS'][$arSections[$i]['ID']]['UF_SECTION_NAME_' . strtoupper(LANGUAGE_ID) . ''];
        } ?>
    </a>
</li>
<?
if ($i == ($sectionsAmount - 1)) {
if ($i == ($sectionsAmount - 1)) {
?>
</ul>
</div>
</div>
    </div>
<?
}
}
}
}
?>
    </div><!--products categories ends-->
    </div>
<?php } ?>
