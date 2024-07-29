<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>
<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <? $APPLICATION->IncludeComponent(
                    "bitrix:catalog.section.list",
                    "",
                    array(
                        "ADDITIONAL_COUNT_ELEMENTS_FILTER" => "additionalCountFilter",
                        "ADD_SECTIONS_CHAIN" => "Y",
                        "CACHE_FILTER" => "N",
                        "CACHE_GROUPS" => "Y",
                        "CACHE_TIME" => "36000000",
                        "CACHE_TYPE" => "A",
                        "COUNT_ELEMENTS" => "Y",
                        "COUNT_ELEMENTS_FILTER" => "CNT_ACTIVE",
                        "FILTER_NAME" => "sectionsFilter",
                        "HIDE_SECTIONS_WITH_ZERO_COUNT_ELEMENTS" => "N",
                        "IBLOCK_ID" => "4",
                        "IBLOCK_TYPE" => "catalog",
                        "SECTION_CODE" => "",
                        "SECTION_FIELDS" => array("ID", "CODE", "NAME", ""),
                        "SECTION_ID" => $_REQUEST["SECTION_ID"],
                        "SECTION_URL" => "",
                        "SECTION_USER_FIELDS" => array("", ""),
                        "SHOW_PARENT_NAME" => "Y",
                        "TOP_DEPTH" => "2",
                        "VIEW_MODE" => "LINE"
                    ),
                    $component
                ); ?>
            </div>
            <div class="col-sm-9 padding-right">
                <? $ElementID = $APPLICATION->IncludeComponent(
                    "bitrix:catalog.element",
                    "",
                    array(
                        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                        "PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
                        "META_KEYWORDS" => $arParams["DETAIL_META_KEYWORDS"],
                        "META_DESCRIPTION" => $arParams["DETAIL_META_DESCRIPTION"],
                        "BROWSER_TITLE" => $arParams["DETAIL_BROWSER_TITLE"],
                        "BASKET_URL" => $arParams["BASKET_URL"],
                        "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
                        "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
                        "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
                        "DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
                        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                        "CACHE_TIME" => $arParams["CACHE_TIME"],
                        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                        "SET_TITLE" => $arParams["SET_TITLE"],
                        "SET_STATUS_404" => $arParams["SET_STATUS_404"],
                        "PRICE_CODE" => $arParams["PRICE_CODE"],
                        "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
                        "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
                        "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
                        "PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
                        "LINK_IBLOCK_TYPE" => $arParams["LINK_IBLOCK_TYPE"],
                        "LINK_IBLOCK_ID" => $arParams["LINK_IBLOCK_ID"],
                        "LINK_PROPERTY_SID" => $arParams["LINK_PROPERTY_SID"],
                        "LINK_ELEMENTS_URL" => $arParams["LINK_ELEMENTS_URL"],

                        "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
                        "OFFERS_FIELD_CODE" => $arParams["DETAIL_OFFERS_FIELD_CODE"],
                        "OFFERS_PROPERTY_CODE" => $arParams["DETAIL_OFFERS_PROPERTY_CODE"],
                        "OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
                        "OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],

                        "ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
                        "ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
                        "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
                        "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
                        "SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
                        "DETAIL_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["element"],
                    ),
                    $component
                ); ?>
                <? $APPLICATION->IncludeComponent(
                    "bitrix:catalog.recommended.products",
                    "",
                    array(
                        "ACTION_VARIABLE" => "action_crp",
                        "ADDITIONAL_PICT_PROP_2" => "MORE_PHOTO",
                        "ADDITIONAL_PICT_PROP_3" => "MORE_PHOTO",
                        "ADDITIONAL_PICT_PROP_4" => "MORE_PHOTO",
                        "ADD_PROPERTIES_TO_BASKET" => "Y",
                        "BASKET_URL" => "/profile/cart/",
                        "CACHE_TIME" => "86400",
                        "CACHE_TYPE" => "A",
                        "CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
                        "CONVERT_CURRENCY" => "N",
                        "DETAIL_URL" => "/catalog/#SECTION_CODE#/#ELEMENT_CODE#/",
                        "ELEMENT_SORT_FIELD" => "NAME",
                        "ELEMENT_SORT_FIELD2" => "ID",
                        "ELEMENT_SORT_ORDER" => "ASC",
                        "ELEMENT_SORT_ORDER2" => "DESC",
                        "HIDE_NOT_AVAILABLE" => "N",
                        "IBLOCK_ID" => "4",
                        "IBLOCK_TYPE" => "catalog",
                        "ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
                        "LABEL_PROP_2" => "-",
                        "LABEL_PROP_4" => "-",
                        "LINE_ELEMENT_COUNT" => "5",
                        "MESS_BTN_BUY" => "Add to cart",
                        "MESS_BTN_DETAIL" => "Подробнее",
                        "MESS_BTN_SUBSCRIBE" => "Подписаться",
                        "MESS_NOT_AVAILABLE" => "Нет в наличии",
                        "OFFERS_PROPERTY_LINK" => "RECOMMEND",
                        "PAGE_ELEMENT_COUNT" => "30",
                        "PARTIAL_PRODUCT_PROPERTIES" => "N",
                        "PRICE_CODE" => array("BASE"),
                        "PRICE_VAT_INCLUDE" => "Y",
                        "PRODUCT_DISPLAY_MODE" => "N",
                        "PRODUCT_ID_VARIABLE" => "id",
                        "PRODUCT_PROPS_VARIABLE" => "prop",
                        "PRODUCT_QUANTITY_VARIABLE" => "quantity",
                        "PRODUCT_SUBSCRIPTION" => "N",
                        "PROPERTY_LINK" => "RECOMMEND",
                        "SHOW_DISCOUNT_PERCENT" => "N",
                        "SHOW_IMAGE" => "Y",
                        "SHOW_NAME" => "Y",
                        "SHOW_OLD_PRICE" => "N",
                        "SHOW_PRICE_COUNT" => "1",
                        "SHOW_PRODUCTS_2" => "N",
                        "SHOW_PRODUCTS_4" => "Y",
                        "TEMPLATE_THEME" => "blue",
                        "USE_PRODUCT_QUANTITY" => "N"
                    )
                ); ?>
            </div>
        </div>
    </div>
</section>