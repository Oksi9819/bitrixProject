<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Catalog\Product\Basket;
use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UI\Extension;
use Bitrix\Sale;

Extension::load(["ui.fonts.ruble", "ui.fonts.opensans"]);

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

$APPLICATION->AddHeadScript('/local/templates/shop_main/components/bitrix/sale.basket.basket/oksi_catalog/script.js');

$documentRoot = Main\Application::getDocumentRoot();
?>
<?php //PR($arResult); ?>
<section id="cart_items">
    <div class="container">
        <div class="table-responsive cart_info">
            <?
            if ($arResult['CART_IS_EMPTY'] == 'N') {
                ?>
                <table class="table table-condensed">
                    <thead>
                    <tr class="cart_menu">
                        <td class="image"><?= GetMessage('SBB_HEADER_ITEM') ?></td>
                        <td class="description"></td>
                        <td class="price"><?= GetMessage('SBB_HEADER_PRICE') ?></td>
                        <td class="quantity"><?= GetMessage('SBB_HEADER_QUANTITY') ?></td>
                        <td class="total"><?= GetMessage('SBB_HEADER_TOTAL') ?></td>
                        <td></td>
                    </tr>
                    </thead>

                    <?
                    if ($arResult['AJAX'] == 'Y') {
                        $APPLICATION->RestartBuffer();
                    }
                    ?>
                    <div id="cart_upd_area">
                        <tbody>
                        <? foreach ($arResult['CART_PRODUCTS'] as $key => $product): ?>
                            <? //PR($product)?>
                            <tr>
                                <td class="cart_product">
                                    <a href=""><img src="<?= $product['PREVIEW_PICTURE']['src'] ?>"
                                                    alt="<?= $product['NAME'] ?>"></a>
                                </td>
                                <td class="cart_description">
                                    <h4><a href=""></a>
                                        <? if (strtoupper(LANGUAGE_ID) == 'RU') {
                                            echo $product["NAME"];
                                        } else {
                                            echo $product['PROPERTY']['VALUE']['NAME_' . strtoupper(LANGUAGE_ID)];
                                        } ?>
                                    </h4>
                                    <p><?= GetMessage('SBB_HEADER_ARTICLE') ?>
                                        : <?= $product['PROPERTY']['VALUE']['ARTNUMBER'] ?></p>
                                </td>
                                <td class="cart_price">
                                    <? if (isset($_SESSION['CURRENCY'])): ?>
                                        <p><?= $_SESSION['CURRENCY'] . ' ' . $arResult['PRICES'][$product['ID']][$_SESSION['CURRENCY']]['PRICE'] ?></p>
                                    <? else: ?>
                                        <p><?= $GLOBALS["BASE_CURRENCY"] . ' ' . $arResult['PRICES'][$product['ID']][$GLOBALS["BASE_CURRENCY"]]['PRICE'] ?></p>
                                    <? endif; ?>
                                </td>
                                <td class="cart_quantity">
                                    <div class="cart_quantity_button">
                                        <? if ($product['QUANTITY'] > 1) { ?>
                                            <a class="cart_quantity_down" href=""
                                               data-id="<?= $product['ID'] ?>">
                                                - </a>
                                        <? } ?>
                                        <label>
                                            <input class="cart_quantity_input" type="text" name="quantity"
                                                   data-id="<?= $product['ID'] ?>"
                                                   value="<?= $product['QUANTITY'] ?>"
                                                   data-prev-value="<?= $product['QUANTITY'] ?>"
                                                   autocomplete="off" size="4"
                                                   maxlength="<?= $product['AVAILABLE_QUANTITY'] ?>"
                                            >
                                        </label>
                                        <? if ($product['QUANTITY'] < $product['AVAILABLE_QUANTITY']) { ?>
                                            <a class="cart_quantity_up" href="" data-id="<?= $product['ID'] ?>">
                                                + </a>
                                        <? } ?>
                                    </div>
                                </td>
                                <td class="cart_total">
                                    <? if (isset($_SESSION['CURRENCY'])): ?>
                                        <p class="cart_total_price"><?= $_SESSION['CURRENCY'] . ' ' . $arResult['PRICES'][$product['ID']][$_SESSION['CURRENCY']]['SUM'] ?></p>
                                    <? else: ?>
                                        <p class="cart_total_price"><?= $GLOBALS["BASE_CURRENCY"] . ' ' . $arResult['PRICES'][$product['ID']][$GLOBALS["BASE_CURRENCY"]]['SUM'] ?></p>
                                    <? endif; ?>
                                </td>
                                <td class="cart_delete">
                                    <a class="cart_quantity_delete" href="" data-id="<?= $product['ID'] ?>"><i
                                                class="fa fa-times"></i></a>
                                </td>
                            </tr>
                        <? endforeach; ?>
                        </tbody>
                    </div>
                    <?
                    if ($arResult['AJAX'] == 'Y') {
                        $arResult['AJAX'] = die();
                    } ?>
                </table>
                <?
            } else echo 'CART IS EMPTY';
            ?>
        </div>
    </div>
</section> <!--/#cart_items-->
<?
if ($arResult['CART_IS_EMPTY'] == 'N') {
    ?>
    <section id="do_action">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                </div>
                <div class="col-sm-6">
                    <div class="total_area">
                        <ul>
                            <li><?= GetMessage('SBB_HEADER_TOTAL') ?><span>
                                    <? if (isset($_SESSION['CURRENCY'])): ?>
                                        <?= $_SESSION['CURRENCY'] . ' ' . $arResult['TOTAL'][$_SESSION['CURRENCY']] ?>
                                    <? else: ?>
                                        <p><?= $arResult['TOTAL']['BASE']['CURRENCY'] . ' ' . $arResult['TOTAL']['BASE']['VALUE'] ?></p>
                                    <? endif; ?>
                                </span></li>
                        </ul>
                        <a class="btn btn-default check_out"
                           href="<?
                           if (LANGUAGE_ID == $GLOBALS["DEF_LANG"]) {
                               echo '/profile/cart/checkout/';
                           } else {
                               echo '/' . LANGUAGE_ID . '/profile/cart/checkout/';
                           }
                           ?>"><?= GetMessage('SBB_HEADER_CHECKOUT') ?></a>
                    </div>
                </div>
            </div>
        </div>
    </section><!--/#do_action-->
    <?
}
?>
