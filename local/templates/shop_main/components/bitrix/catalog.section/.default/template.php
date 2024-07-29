<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @var array $arParams
 * @var array $arResult
 * @var string $templateFolder
 * @var string $templateName
 * @var CMain $APPLICATION
 * @var CBitrixBasketComponent $component
 * @var CBitrixComponentTemplate $this
 * @var array $giftParameters
 */

$this->setFrameMode(true);
$APPLICATION->AddHeadScript('/local/templates/shop_main/components/bitrix/catalog/oksi_catalog/bitrix/catalog.section/.default/script.js');
?>

<?php //PR($arResult["ITEMS"]); ?>
    <div class="features_items"><!--features_items-->
        <h2 class="title text-center"><?= GetMessage('CATALOG_HEADER') ?></h2>
        <div class="cont">
            <!--<div class="row">-->
            <? foreach ($arResult["ITEMS"] as $cell => $arElement): ?>
                <?
                $this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
                //PR($arElement);
                ?>

                <div id="<?= $this->GetEditAreaId($arElement['ID']); ?>">
                    <div class="col-sm-4">
                        <div class="product-image-wrapper">
                            <div class="single-products" id="<?= $arElement['ID'] ?>">
                                <div class="productinfo text-center">
                                    <div class="img-wrap"
                                         data-img-source="<?= $arElement['DETAIL_PICTURE']['SAFE_SRC'] ?>"></div>
                                    <h2 class="ref-to-detail"
                                        data-href="<?= $arElement['DETAIL_PAGE_URL'] ?>">
                                        <?
                                        if (isset($_SESSION['CURRENCY'])) {
                                            echo $_SESSION['CURRENCY'] . ' ' . $arResult['PRICES'][$arElement['ID']][$_SESSION['CURRENCY']];
                                        } else {
                                            echo CPrice::GetBasePrice($arElement['ID'])['CURRENCY'] . ' ' . $arResult['PRICES'][$arElement['ID']][CPrice::GetBasePrice($arElement['ID'])['CURRENCY']];
                                        }
                                        ?></h2>
                                    <p class="ref-to-detail"
                                       data-href="<?= $arElement['DETAIL_PAGE_URL'] ?>">
                                        <? if (strtoupper(LANGUAGE_ID) == 'RU') {
                                            echo $arElement["NAME"];
                                        } else {
                                            echo $arElement['PROPERTIES']['NAME_' . strtoupper(LANGUAGE_ID) . '']['VALUE'];
                                        } ?>
                                    </p>
                                    <?
                                    if (in_array($arElement['ID'], $arResult['IN_BASKET'])) {
                                        ?>
                                        <a href="#!"
                                           class="btn btn-default add-to-cart" data-id="<?= $arElement['ID'] ?>"
                                           data-count="1"
                                           data-cart-status="Y"><i
                                                    class="fa fa-shopping-cart"></i><?= GetMessage('CATALOG_IN_CART') ?>
                                        </a>
                                        <?
                                    } else { ?>
                                        <a href=""
                                           class="btn btn-default add-to-cart"
                                           data-id="<?= $arElement['ID'] ?>"
                                           data-count="1"
                                           data-cart-status="N"><i
                                                    class="fa fa-shopping-cart"></i><?= GetMessage('CATALOG_ADD') ?>
                                        </a>
                                        <?
                                    }
                                    ?>
                                </div>
                                <div class="product-overlay">
                                    <div class="overlay-content">
                                        <h2 class="ref-to-detail"
                                            data-href="<?= $arElement['DETAIL_PAGE_URL'] ?>">
                                            <?
                                            if (isset($_SESSION['CURRENCY'])) {
                                                echo $_SESSION['CURRENCY'] . ' ' . $arResult['PRICES'][$arElement['ID']][$_SESSION['CURRENCY']];
                                            } else {
                                                echo CPrice::GetBasePrice($arElement['ID'])['CURRENCY'] . ' ' . $arResult['PRICES'][$arElement['ID']][CPrice::GetBasePrice($arElement['ID'])['CURRENCY']];
                                            }
                                            ?>
                                        </h2>
                                        <p class="ref-to-detail"
                                           data-href="<?= $arElement['DETAIL_PAGE_URL'] ?>">
                                            <? if (strtoupper(LANGUAGE_ID) == 'RU') {
                                                echo $arElement["NAME"];
                                            } else {
                                                echo $arElement['PROPERTIES']['NAME_' . strtoupper(LANGUAGE_ID) . '']['VALUE'];
                                            } ?>
                                        </p>
                                        <?
                                        if (in_array($arElement['ID'], $arResult['IN_BASKET'])) {
                                            ?>
                                            <a href="#!"
                                               class="btn btn-default add-to-cart" data-id="<?= $arElement['ID'] ?>"
                                               data-cart-status="Y"><i
                                                        class="fa fa-shopping-cart"></i><?= GetMessage('CATALOG_IN_CART') ?>
                                            </a>
                                            <?
                                        } else { ?>
                                            <a href="#"
                                               class="btn btn-default add-to-cart" data-id="<?= $arElement['ID'] ?>"
                                               data-name="<?= $arElement['NAME'] ?>"
                                                <?
                                                if (isset($_SESSION['CURRENCY'])) { ?>
                                                    data-price="<?= $arResult['PRICES'][$arElement['ID']][$_SESSION['CURRENCY']] ?>"
                                                    data-currency="<?= $_SESSION['CURRENCY'] ?>"
                                                <? } else { ?>
                                                    data-price="<?= $arResult['PRICES'][$arElement['ID']][CPrice::GetBasePrice($arElement['ID'])['CURRENCY']] ?>"
                                                    data-currency="<?= CPrice::GetBasePrice($arElement['ID'])['CURRENCY'] ?>"
                                                <? } ?>
                                               data-cart-status="N"
                                               data-lang="<?= LANGUAGE_ID ?>"><i
                                                        class="fa fa-shopping-cart"></i><?= GetMessage('CATALOG_ADD') ?>
                                            </a>
                                            <?
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="choose">
                                <ul class="nav nav-pills nav-justified">
                                    <li>
                                        <?
                                        if (isset($arResult['IN_WISHLIST'])) {
                                            if (in_array($arElement['ID'], $arResult['IN_WISHLIST'])) {
                                                ?>
                                                <a href="#"
                                                   class="act-with-wishlist"
                                                   data-id="<?= $arElement['ID'] ?>"
                                                ><i class="fa fa-plus-square"></i><?= GetMessage('CATALOG_WISHLIST_DEL') ?>
                                                </a>
                                                <?
                                            } else { ?>
                                                <a href="#"
                                                   class="act-with-wishlist"
                                                   data-id="<?= $arElement['ID'] ?>"
                                                ><i class="fa fa-plus-square"></i><?= GetMessage('CATALOG_WISHLIST_ADD') ?>
                                                </a>
                                                <?
                                            }
                                        } else {
                                            ?>
                                            <a href="#"
                                               class="act-with-wishlist"
                                               data-id="<?= $arElement['ID'] ?>"
                                            ><i class="fa fa-plus-square"></i><?= GetMessage('CATALOG_WISHLIST_ADD') ?>
                                            </a>
                                            <?
                                        }
                                        ?>
                                    </li>
                                    <li>
                                        <?
                                        if (isset($arResult['IN_COMPARE'])) {
                                            if (in_array($arElement['ID'], $arResult['IN_COMPARE'])) {
                                                ?>
                                                <a href="#"
                                                   class="add-to-compare"
                                                   data-id="<?= $arElement['ID'] ?>"
                                                   data-compare-status="Y"
                                                   data-href="<?= $arElement['DETAIL_PAGE_URL'] . '?' . $arParams['ACTION_VARIABLE'] . '=' . 'DELETE_FROM_COMPARE_LIST&' . $arParams['PRODUCT_ID_VARIABLE'] . '=' . $arElement['ID'] ?>"><?= GetMessage('CATALOG_COMPARE_IN') ?></a>
                                                <?
                                            } else { ?>
                                                <a href="#"
                                                   class="add-to-compare"
                                                   data-id="<?= $arElement['ID'] ?>"
                                                   data-compare-status="N"
                                                   data-href="<?= $arElement['DETAIL_PAGE_URL'] . '?' . $arParams['ACTION_VARIABLE'] . '=' . 'ADD_TO_COMPARE_LIST&' . $arParams['PRODUCT_ID_VARIABLE'] . '=' . $arElement['ID'] ?>">
                                                    <i class="fa fa-plus-square"></i><?= GetMessage('CATALOG_COMPARE_ADD') ?>
                                                </a>
                                                <?
                                            }
                                        } else {
                                            ?>
                                            <a href="#"
                                               class="add-to-compare"
                                               data-id="<?= $arElement['ID'] ?>"
                                               data-compare-status="N"
                                               data-href="<?= $arElement['DETAIL_PAGE_URL'] . '?' . $arParams['ACTION_VARIABLE'] . '=' . 'ADD_TO_COMPARE_LIST&' . $arParams['PRODUCT_ID_VARIABLE'] . '=' . $arElement['ID'] ?>">
                                                <i class="fa fa-plus-square"></i><?= GetMessage('CATALOG_COMPARE_ADD') ?>
                                            </a>
                                            <?
                                        }
                                        ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <? endforeach; ?>
        </div>
    </div><!--features_items-->

<? /*if (is_array($arElement["OFFERS"]) && !empty($arElement["OFFERS"])): ?>
            <? foreach ($arElement["OFFERS"] as $arOffer): ?>
                <? foreach ($arOffer["DISPLAY_PROPERTIES"] as $pid => $arProperty): ?>
                    <?= $arProperty["NAME"] ?>: <?
                    if (is_array($arProperty["DISPLAY_VALUE"]))
                        echo implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);
                    else
                        echo $arProperty["DISPLAY_VALUE"]; ?>
                <? endforeach ?>
                <? foreach ($arOffer["PRICES"] as $code => $arPrice): ?>
                    <? if ($arPrice["CAN_ACCESS"]): ?>
                        <?= $arResult["PRICES"][$code]["TITLE"]; ?>
                        <? if ($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]): ?>
                            <s><?= $arPrice["PRINT_VALUE"] ?></s> <?= $arPrice["PRINT_DISCOUNT_VALUE"] ?>
                        <? else: ?>
                            <?= $arPrice["PRINT_VALUE"] ?>
                        <? endif ?>
                    <? endif; ?>
                <? endforeach; ?>
                <? if ($arOffer["CAN_BUY"]): ?>
                    <form action="<?= POST_FORM_ACTION_URI ?>" method="post" enctype="multipart/form-data">
                        <input type="text" name="<? echo $arParams["PRODUCT_QUANTITY_VARIABLE"] ?>" value="1" size="5">
                        <input type="hidden" name="<? echo $arParams["ACTION_VARIABLE"] ?>" value="BUY">
                        <input type="hidden" name="<? echo $arParams["PRODUCT_ID_VARIABLE"] ?>"
                               value="<? echo $arOffer["ID"] ?>">
                        <input type="submit" name="<? echo $arParams["ACTION_VARIABLE"] . "ADD2BASKET" ?>"
                               value="<? echo GetMessage("CATALOG_ADD") ?>">
                    </form>
                <? elseif (count($arResult["PRICES"]) > 0): ?>
                    <?= GetMessage("CATALOG_NOT_AVAILABLE") ?>
                <? endif ?>
            <? endforeach; ?>
        <? else: ?>
            <? foreach ($arElement["PRICES"] as $code => $arPrice): ?>
                <? if ($arPrice["CAN_ACCESS"]): ?>
                    <?= $arResult["PRICES"][$code]["TITLE"]; ?>:&nbsp;&nbsp;
                    <? if ($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]): ?>
                        <s><?= $arPrice["PRINT_VALUE"] ?></s> <?= $arPrice["PRINT_DISCOUNT_VALUE"] ?>
                    <? else: ?>
                        <?= $arPrice["PRINT_VALUE"] ?>
                    <? endif; ?>
                <? endif; ?>
            <? endforeach; ?>

            <? if ($arElement["CAN_BUY"]): ?>
                <form action="<?= POST_FORM_ACTION_URI ?>" method="post" enctype="multipart/form-data">
                    <input type="text" name="<? echo $arParams["PRODUCT_QUANTITY_VARIABLE"] ?>" value="1" size="5">
                    <input type="hidden" name="<? echo $arParams["ACTION_VARIABLE"] ?>" value="BUY">
                    <input type="hidden" name="<? echo $arParams["PRODUCT_ID_VARIABLE"] ?>"
                           value="<? echo $arElement["ID"] ?>">
                    <input type="submit" name="<? echo $arParams["ACTION_VARIABLE"] . "ADD2BASKET" ?>"
                           value="<? echo GetMessage("CATALOG_ADD") ?>">
                </form>
            <? elseif ((count($arResult["PRICES"]) > 0) || is_array($arElement["PRICE_MATRIX"])): ?>
                <?= GetMessage("CATALOG_NOT_AVAILABLE") ?>
            <? endif ?>
        <? endif ?>

    </div>

<? endforeach; */ ?>

<? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
    <?= $arResult["NAV_STRING"] ?>
<? endif; ?>