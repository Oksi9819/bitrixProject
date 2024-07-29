<? use Bitrix\Iblock\ElementTable;
use Bitrix\Main\UI\PageNavigation;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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
$APPLICATION->AddHeadScript('/local/templates/shop_main/components/bitrix/catalog/oksi_catalog/bitrix/catalog.section/wishlist/script.js');
?>
<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-11 padding-right">
                <?php //PR($arResult["WISHLIST_ITEMS"]); ?>
                <div class="features_items"><!--features_items-->
                    <h2 class="title text-center"><?= GetMessage('WISHLIST_HEADER') ?></h2>
                    <? foreach ($arResult["WISHLIST_ITEMS"] as $wishItem): ?>
                        <?
                        $this->AddEditAction($wishItem['ID'], $wishItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
                        $this->AddDeleteAction($wishItem['ID'], $wishItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
                        //PR($wishItem);
                        ?>
                        <div class="col-sm-3">
                            <div class="product-image-wrapper">
                                <div class="single-products" id="<?= $wishItem['ID'] ?>">
                                    <div class="productinfo text-center">
                                        <div class="img-wrap"
                                             data-img-source="<?= $wishItem['DETAIL_PICTURE'] ?>"></div>
                                        <h2 class="ref-to-detail"
                                            data-href="<?= $wishItem['DETAIL_PAGE_URL'] ?>">
                                            <?
                                            if (isset($_SESSION['CURRENCY'])) {
                                                echo $_SESSION['CURRENCY'] . ' ' . $arResult['PRICES'][$wishItem['ID']][$_SESSION['CURRENCY']];
                                            } else {
                                                echo CPrice::GetBasePrice($wishItem['ID'])['CURRENCY'] . ' ' . $arResult['PRICES'][$wishItem['ID']][CPrice::GetBasePrice($wishItem['ID'])['CURRENCY']];
                                            }
                                            ?>
                                        </h2>
                                        <p class="ref-to-detail"
                                           data-href="<?= $wishItem['DETAIL_PAGE_URL'] ?>">
                                            <? if (strtoupper(LANGUAGE_ID) == 'RU') {
                                                echo $wishItem["NAME"];
                                            } else {
                                                echo $wishItem['PROPERTIES']['NAME_' . strtoupper(LANGUAGE_ID) . '']['VALUE'];
                                            } ?>
                                        </p>
                                        <?
                                        if (in_array($wishItem['ID'], $arResult['IN_BASKET'])) {
                                            ?>
                                            <a href="#!"
                                               class="btn btn-default add-to-cart" data-id="<?= $wishItem['ID'] ?>"
                                               data-count="1"
                                               data-cart-status="Y"><i
                                                        class="fa fa-shopping-cart"></i><?= GetMessage('CATALOG_IN_CART') ?>
                                            </a>
                                            <?
                                        } else { ?>
                                            <a href=""
                                               class="btn btn-default add-to-cart" data-id="<?= $wishItem['ID'] ?>"
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
                                                data-href="<?= $wishItem['DETAIL_PAGE_URL'] ?>">
                                                <?
                                                if (isset($_SESSION['CURRENCY'])) {
                                                    echo $_SESSION['CURRENCY'] . ' ' . $arResult['PRICES'][$wishItem['ID']][$_SESSION['CURRENCY']];
                                                } else {
                                                    echo CPrice::GetBasePrice($wishItem['ID'])['CURRENCY'] . ' ' . $arResult['PRICES'][$wishItem['ID']][CPrice::GetBasePrice($wishItem['ID'])['CURRENCY']];
                                                }
                                                ?>
                                            </h2>
                                            <p class="ref-to-detail"
                                               data-href="<?= $wishItem['DETAIL_PAGE_URL'] ?>"><?= $wishItem["NAME"] ?></p>
                                            <?
                                            if (in_array($wishItem['ID'], $arResult['IN_BASKET'])) {
                                                ?>
                                                <a href="#!"
                                                   class="btn btn-default add-to-cart"
                                                   data-id="<?= $wishItem['ID'] ?>"
                                                   data-cart-status="Y"><i
                                                            class="fa fa-shopping-cart"></i><?= GetMessage('CATALOG_IN_CART') ?>
                                                </a>
                                                <?
                                            } else { ?>
                                                <a href="#"
                                                   class="btn btn-default add-to-cart"
                                                   data-id="<?= $wishItem['ID'] ?>"
                                                   data-name="<?= $wishItem['NAME'] ?>"
                                                   data-price="<?= $arResult['PRICES'][$wishItem['ID']]['PRICE'] ?>"
                                                   data-cart-status="N"><i
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
                                                if (in_array($wishItem['ID'], $arResult['IN_WISHLIST'])) {
                                                    ?>
                                                    <a href="#"
                                                       class="act-with-wishlist"
                                                       data-id="<?= $wishItem['ID'] ?>"
                                                    ><i class="fa fa-plus-square"></i><?= GetMessage('CATALOG_WISHLIST_DEL') ?>
                                                    </a>
                                                    <?
                                                } else { ?>
                                                    <a href="#"
                                                       class="act-with-wishlist"
                                                       data-id="<?= $wishItem['ID'] ?>"
                                                    ><i class="fa fa-plus-square"></i><?= GetMessage('CATALOG_WISHLIST_ADD') ?>
                                                    </a>
                                                    <?
                                                }
                                            } else {
                                                ?>
                                                <a href="#"
                                                   class="act-with-wishlist"
                                                   data-id="<?= $wishItem['ID'] ?>"
                                                ><i class="fa fa-plus-square"></i><?= GetMessage('CATALOG_WISHLIST_ADD') ?>
                                                </a>
                                                <?
                                            }
                                            ?>
                                        </li>
                                        <li>
                                            <?
                                            if (isset($arResult['IN_COMPARE'])) {
                                                if (in_array($wishItem['ID'], $arResult['IN_COMPARE'])) {
                                                    ?>
                                                    <a href="#"
                                                       class="add-to-compare"
                                                       data-id="<?= $wishItem['ID'] ?>"
                                                       data-compare-status="Y"
                                                       data-href="<?= $wishItem['DETAIL_PAGE_URL'] . '?' . $arParams['ACTION_VARIABLE'] . '=' . 'DELETE_FROM_COMPARE_LIST&' . $arParams['PRODUCT_ID_VARIABLE'] . '=' . $wishItem['ID'] ?>">
                                                        <?= GetMessage('CATALOG_COMPARE_IN') ?></a>
                                                    <?
                                                } else { ?>
                                                    <a href="#"
                                                       class="add-to-compare"
                                                       data-id="<?= $wishItem['ID'] ?>"
                                                       data-compare-status="N"
                                                       data-href="<?= $wishItem['DETAIL_PAGE_URL'] . '?' . $arParams['ACTION_VARIABLE'] . '=' . 'ADD_TO_COMPARE_LIST&' . $arParams['PRODUCT_ID_VARIABLE'] . '=' . $wishItem['ID'] ?>">
                                                        <i class="fa fa-plus-square"></i><?= GetMessage('CATALOG_COMPARE_ADD') ?>
                                                    </a>
                                                    <?
                                                }
                                            } else {
                                                ?>
                                                <a href="#"
                                                   class="add-to-compare"
                                                   data-id="<?= $wishItem['ID'] ?>"
                                                   data-compare-status="N"
                                                   data-href="<?= $wishItem['DETAIL_PAGE_URL'] . '?' . $arParams['ACTION_VARIABLE'] . '=' . 'ADD_TO_COMPARE_LIST&' . $arParams['PRODUCT_ID_VARIABLE'] . '=' . $wishItem['ID'] ?>">
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

                    <? endforeach; ?>
                </div><!--features_items-->

                <? $APPLICATION->IncludeComponent("bitrix:main.pagenavigation", "", array(
                    "NAV_OBJECT" => $arResult['NAV'],
                    "SEF_MODE" => "N"
                ),
                    false
                );
                ?>
                <? /*if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
                    <?= $arResult["NAV_STRING"] ?>
                <? endif; */ ?>
            </div>
        </div>
    </div>
</section>
