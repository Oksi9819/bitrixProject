<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
$APPLICATION->AddHeadScript('/local/templates/shop_main/components/bitrix/catalog/oksi_catalog/bitrix/catalog.element/.default/script.js');
?>

<!-- Свойства -->
<? /*foreach ($arResult["DISPLAY_PROPERTIES"] as $pid => $arProperty): ?>
    <?= $arProperty["NAME"] ?>: <?
    if (is_array($arProperty["DISPLAY_VALUE"])):
        echo implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);
    elseif ($pid == "MANUAL"):
        ?><a href="<?= $arProperty["VALUE"] ?>"><?= GetMessage("CATALOG_DOWNLOAD") ?></a><?
    else:
        echo $arProperty["DISPLAY_VALUE"]; ?>
    <? endif ?>
<? endforeach */ ?>

<? /*if (is_array($arResult["OFFERS"]) && !empty($arResult["OFFERS"])): ?>
    <!-- Если есть преддожения -->
    <? foreach ($arResult["OFFERS"] as $arOffer): ?>
        <!-- Свойства -->
        <? foreach ($arOffer["DISPLAY_PROPERTIES"] as $pid => $arProperty): ?>
            <small><?= $arProperty["NAME"] ?>:&nbsp;<?
                if (is_array($arProperty["DISPLAY_VALUE"]))
                    echo implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);
                else
                    echo $arProperty["DISPLAY_VALUE"]; ?></small><br/>
        <? endforeach ?>
        <!-- Цены -->
        <? foreach ($arOffer["PRICES"] as $code => $arPrice): ?>
            <? if ($arPrice["CAN_ACCESS"]): ?>
                <?= $arResult["CAT_PRICES"][$code]["TITLE"]; ?>
                <? if ($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]): ?>
                    <s><?= $arPrice["PRINT_VALUE"] ?></s> <?= $arPrice["PRINT_DISCOUNT_VALUE"] ?>
                <? else: ?>
                    <?= $arPrice["PRINT_VALUE"] ?>
                <? endif ?>
                </p>
            <? endif; ?>
        <? endforeach; ?>
        <!-- Покупка -->
        <? if ($arOffer["CAN_BUY"]): ?>
            <form action="<?= POST_FORM_ACTION_URI ?>" method="post" enctype="multipart/form-data" сlass="add_form">
                <a href="javascript:void(0)"
                   onclick="if (BX('QUANTITY<?= $arOffer['ID'] ?>').value &gt; 1) BX('QUANTITY<?= $arOffer['ID'] ?>').value--;">-</a>
                <input type="text" name="QUANTITY" value="1" id="QUANTITY<?= $arOffer['ID'] ?>"/>
                <a href="javascript:void(0)" onclick="BX('QUANTITY<?= $arOffer['ID'] ?>').value++;">+</a>
                <input type="text" name="<? echo $arParams["PRODUCT_QUANTITY_VARIABLE"] ?>" value="1" size="5">
                <input type="hidden" name="<? echo $arParams["ACTION_VARIABLE"] ?>" value="BUY">
                <input type="hidden" name="<? echo $arParams["PRODUCT_ID_VARIABLE"] ?>"
                       value="<? echo $arOffer["ID"] ?>">
                <input type="submit" name="<? echo $arParams["ACTION_VARIABLE"] . "BUY" ?>"
                       value="<? echo GetMessage("CATALOG_BUY") ?>">
                <input type="submit" name="<? echo $arParams["ACTION_VARIABLE"] . "ADD2BASKET" ?>"
                       value="<? echo GetMessage("CT_BCE_CATALOG_ADD") ?>">
            </form>
        <? elseif (count($arResult["CAT_PRICES"]) > 0): ?>
            <?= GetMessage("CATALOG_NOT_AVAILABLE") ?>
        <? endif ?>
    <? endforeach; ?>
<? else: ?>
    <!-- Если нет преддожений -->

    <!-- Цены -->
    <? foreach ($arResult["PRICES"] as $code => $arPrice): ?>
        <? if ($arPrice["CAN_ACCESS"]): ?>
            <?= $arResult["CAT_PRICES"][$code]["TITLE"]; ?>
            <? if ($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]): ?>
                <s><?= $arPrice["PRINT_VALUE"] ?></s> <?= $arPrice["PRINT_DISCOUNT_VALUE"] ?>
            <? else: ?>
                <?= $arPrice["PRINT_VALUE"] ?>
            <? endif ?>
            </p>
        <? endif; ?>
    <? endforeach; ?>
    <!-- Покупка -->
    <? if ($arResult["CAN_BUY"]): ?>
        <form action="<?= POST_FORM_ACTION_URI ?>" method="post" enctype="multipart/form-data" сlass="add_form">
            <a href="javascript:void(0)"
               onclick="if (BX('QUANTITY<?= $arElement['ID'] ?>').value &gt; 1) BX('QUANTITY<?= $arElement['ID'] ?>').value--;">-</a>
            <input type="text" name="QUANTITY" value="1" id="QUANTITY<?= $arElement['ID'] ?>"/>
            <a href="javascript:void(0)" onclick="BX('QUANTITY<?= $arElement['ID'] ?>').value++;">+</a>
            <input type="hidden" name="<? echo $arParams["ACTION_VARIABLE"] ?>" value="BUY">
            <input type="hidden" name="<? echo $arParams["PRODUCT_ID_VARIABLE"] ?>" value="<? echo $arResult["ID"] ?>">
            <input type="submit" name="<? echo $arParams["ACTION_VARIABLE"] . "BUY" ?>"
                   value="<? echo GetMessage("CATALOG_BUY") ?>">
            <input type="submit" name="<? echo $arParams["ACTION_VARIABLE"] . "ADD2BASKET" ?>"
                   value="<? echo GetMessage("CATALOG_ADD_TO_BASKET") ?>">
        </form>
    <? elseif ((count($arResult["PRICES"]) > 0) || is_array($arResult["PRICE_MATRIX"])): ?>
        <?= GetMessage("CATALOG_NOT_AVAILABLE") ?>
    <? endif ?>
<? endif */ ?>

<div class="product-details"><!--product-details-->
    <div class="col-sm-5">
        <div class="view-product">
            <img src="<?= $arResult['DETAIL_PICTURE']['SRC'] ?>"
                 alt="<?= $arResult["NAME"] ?>"/>
        </div>
        <div id="similar-product" class="carousel slide" data-ride="carousel">

            <!-- Wrapper for slides -->
            <div class="carousel-inner">
                <? $addPhotoCnt = count($arResult['MORE_PHOTO']); ?>
                <? if ($addPhotoCnt > 0): ?>
                    <?
                    for ($i = 0; $i < $addPhotoCnt; $i++) {
                        if ($i == 0) {
                            ?><div class="item active"><?
                        }
                        if ($i % 3 == 0) {
                            ?></div><div class="item"><?
                        }
                        $resizedAddImg = CFile::ResizeImageGet($arResult['MORE_PHOTO'][$i], array("width" => 85, "height" => 84), BX_RESIZE_IMAGE_EXACT, false);
                        ?>
                        <a href="<?= $arResult['MORE_PHOTO'][$i]['SRC'] ?>"
                           title="<?= $arResult['MORE_PHOTO'][$i]['NAME'] ?>"
                           target="_blank"><img
                                    src="<?= $resizedAddImg['src'] ?>"
                                    alt="<?= $arResult['MORE_PHOTO'][$i]['NAME'] ?>">
                        </a>
                        <?
                    }
                    ?>
                    </div>
                <? endif ?>
            </div>

            <!-- Controls -->
            <a class="left item-control" href="#similar-product" data-slide="prev">
                <i class="fa fa-angle-left"></i>
            </a>
            <a class="right item-control" href="#similar-product" data-slide="next">
                <i class="fa fa-angle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-sm-7">
        <div class="product-information"><!--/product-information-->
            <!--<img src="/local/templates/shop_main/HTML/images/product-details/new.jpg" class="newarrival" alt=""/>-->
            <h2>
                <? if (strtoupper(LANGUAGE_ID) == 'RU') {
                    echo $arResult["NAME"];
                } else {
                    echo $arResult['PROPERTIES']['NAME_' . strtoupper(LANGUAGE_ID) . '']['VALUE'];
                } ?>
            </h2>
            <p><?= GetMessage('CATALOG_ITEM_ARTICLE') ?>: <?= $arResult['PROPERTIES']['ARTNUMBER']['VALUE'] ?></p>
            <div class="post-meta">
               <span class="stars-block" id="stars_block<?= $arResult['ID'] ?>"
                     data-vote="<?= $arResult['PROPERTIES']['VOTES']['VALUE'] ?>"
                     data-value="<?= $arResult['PROPERTIES']['ARR_VOTE_VALUE']['VALUE'] ?>">
                            </span>
            </div>
            <span>
									<span>
                                        <?
                                        if (isset($_SESSION['CURRENCY'])) {
                                            echo $_SESSION['CURRENCY'] . ' ' . $arResult['PRICES'][$arResult['ID']][$_SESSION['CURRENCY']];
                                        } else {
                                            echo CPrice::GetBasePrice($arResult['ID'])['CURRENCY'] . ' ' . $arResult['PRICES'][$arResult['ID']][CPrice::GetBasePrice($arResult['ID'])['CURRENCY']];
                                        }
                                        ?>
                                    </span>
									<?
                                    if ($arResult['IN_BASKET'] == 'Y') {
                                        ?>
                                        <a href="#!"
                                           class="btn btn-default add-to-cart" data-id="<?= $arResult['ID'] ?>"
                                           data-cart-status="Y"><i class="fa fa-shopping-cart"></i><?= GetMessage('CATALOG_ITEM_IN_CART') ?>
                                    </a>
                                        <?
                                    } else { ?>
                                        <a href="#"
                                           class="btn btn-default add-to-cart" data-id="<?= $arResult['ID'] ?>"
                                           data-name="<?= $arResult['NAME'] ?>"
                                           data-price="<?= $arResult['ITEM_PRICES'][0]['PRICE'] ?>"
                                           data-cart-status="N"><i class="fa fa-shopping-cart"></i><?= GetMessage('CATALOG_ITEM_ADD_TO_CART') ?>
                                    </a>
                                        <?
                                    }
                                    ?>
								</span>
            <p><b><?= GetMessage('CATALOG_ITEM_AVAILABILITY') ?>:</b><? if ($arResult['PRODUCT']['AVAILABLE']) {
                    ?> <?= GetMessage('CATALOG_ITEM_AVAILABLE') ?><?
                } else {
                    ?> <?= GetMessage('CATALOG_ITEM_NOT_AVAILABLE') ?><?
                }
                ?>
            </p>
            <p><b><?= GetMessage('CATALOG_ITEM_BREND') ?>:</b> <?= $arResult['PROPERTIES']['BREND_REF']['VALUE'] ?></p>
        </div><!--/product-information-->
    </div>
</div><!--/product-details-->

<div class="category-tab shop-details-tab"><!--category-tab-->
    <div class="col-sm-12">
        <ul class="nav nav-tabs">
            <li><a href="#companyprofile" data-toggle="tab"><?= GetMessage('CATALOG_ABOUT_BREND') ?></a></li><!--brand description-->
            <li class="active"><a href="#reviews"
                                  data-toggle="tab"><?= GetMessage('CATALOG_ITEM_REVIEWS') ?> <? if (isset($GLOBALS['CNT_COMMENTS'])) {
                        echo '(' . $GLOBALS['CNT_COMMENTS'] . ')';
                    } ?></a>
            </li><!--reviews about product-->
        </ul>
    </div>
    <div class="tab-content">
        <div class="tab-pane fade" id="companyprofile">
            <div class="col-sm-12">
                <div class="product-image-wrapper">
                    <div class="single-products">
                        <div class="productinfo text-center">
                            Здесь находится описание компании производителя.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade active in" id="reviews">
            <div class="col-sm-12">
                <? $APPLICATION->IncludeComponent(
                    "bitrix:news.list",
                    "",
                    array(
                        "ACTIVE_DATE_FORMAT" => "d.m.Y",
                        "ADD_SECTIONS_CHAIN" => "Y",
                        "AJAX_MODE" => "N",
                        "AJAX_OPTION_ADDITIONAL" => "",
                        "AJAX_OPTION_HISTORY" => "N",
                        "AJAX_OPTION_JUMP" => "N",
                        "AJAX_OPTION_STYLE" => "Y",
                        "CACHE_FILTER" => "N",
                        "CACHE_GROUPS" => "Y",
                        "CACHE_TIME" => "36000000",
                        "CACHE_TYPE" => "A",
                        "CHECK_DATES" => "Y",
                        "DETAIL_URL" => "",
                        "DISPLAY_BOTTOM_PAGER" => "Y",
                        "DISPLAY_DATE" => "Y",
                        "DISPLAY_NAME" => "Y",
                        "DISPLAY_PICTURE" => "Y",
                        "DISPLAY_PREVIEW_TEXT" => "Y",
                        "DISPLAY_TOP_PAGER" => "N",
                        "FIELD_CODE" => array("ID", ""),
                        "FILTER_NAME" => "",
                        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                        "IBLOCK_ID" => "7",
                        "IBLOCK_TYPE" => "news",
                        "INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
                        "INCLUDE_SUBSECTIONS" => "Y",
                        "MESSAGE_404" => "",
                        "NEWS_COUNT" => "20",
                        "PAGER_BASE_LINK_ENABLE" => "N",
                        "PAGER_DESC_NUMBERING" => "N",
                        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                        "PAGER_SHOW_ALL" => "N",
                        "PAGER_SHOW_ALWAYS" => "N",
                        "PAGER_TEMPLATE" => ".default",
                        "PAGER_TITLE" => "Новости",
                        "PARENT_SECTION" => "",
                        "PARENT_SECTION_CODE" => "",
                        "PREVIEW_TRUNCATE_LEN" => "",
                        "PROPERTY_CODE" => array("COMMENT", "RATING", ""),
                        "SET_BROWSER_TITLE" => "Y",
                        "SET_LAST_MODIFIED" => "N",
                        "SET_META_DESCRIPTION" => "Y",
                        "SET_META_KEYWORDS" => "Y",
                        "SET_STATUS_404" => "N",
                        "SET_TITLE" => "Y",
                        "SHOW_404" => "N",
                        "SORT_BY1" => "ACTIVE_FROM",
                        "SORT_BY2" => "SORT",
                        "SORT_ORDER1" => "DESC",
                        "SORT_ORDER2" => "ASC",
                        "STRICT_SECTION_CHECK" => "N",
                    )
                ); ?>
            </div>
        </div>
    </div>
</div><!--/category-tab-->
