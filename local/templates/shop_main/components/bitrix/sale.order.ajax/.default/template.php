<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CMain $APPLICATION
 * @var CUser $USER
 * @var SaleOrderAjax $component
 * @var string $templateFolder
 */
$APPLICATION->AddHeadScript('/local/templates/shop_main/components/bitrix/sale.basket.basket/oksi_catalog/script.js');
$APPLICATION->AddHeadScript('/local/templates/shop_main/components/bitrix/sale.order.ajax/.default/script.js');
?>
<section id="cart_items">
    <div class="container">
        <div class="checkout-options">
            <ul class="nav">
                <li>
                    <a href="<?
                    if ($arResult['LANG'] == $GLOBALS["DEF_LANG"]) {
                        echo '/profile/cart';
                    } else {
                        echo '/' . $arResult['LANG'] . '/profile/cart';
                    }
                    ?>"><i class="fa fa-times"></i><?= GetMessage('ORDER_CANCEL') ?></a>
                </li>
            </ul>
        </div><!--/checkout-options-->

        <div class="shopper-informations">
            <div class="row">
                <div class="col-sm-7 clearfix">
                    <div class="bill-to">
                        <p><?= GetMessage('ORDER_SHOPPER_INFO') ?></p>
                        <div class="form-one">
                            <form>
                                <input type="text" class="full-name" placeholder="<?= GetMessage('ORDER_FULLNAME') ?> *"
                                       value="<?= $arResult['props'][1] ? $arResult['props'][1]['VALUE'] : ''; ?>"
                                       required>
                                <input type="text" class="email" placeholder="<?= GetMessage('ORDER_EMAIL') ?> *"
                                       value="<?= $arResult['props'][2] ? $arResult['props'][2]['VALUE'] : ''; ?>"
                                       required>
                                <input type="text" class="phone" placeholder="<?= GetMessage('ORDER_PHONE') ?> *"
                                       value="<?= $arResult['props'][3] ? $arResult['props'][3]['VALUE'] : ''; ?>"
                                       required>
                                <input type="text" class="address1" placeholder="<?= GetMessage('ORDER_ADDRESS') ?> 1 *"
                                       value="<?= $arResult['props'][7] ? $arResult['props'][3]['VALUE_FORMATTED'] : ''; ?>"
                                       required>
                                <input type="text" class="address2" placeholder="<?= GetMessage('ORDER_ADDRESS') ?> 2">
                            </form>
                        </div>
                        <div class="form-two">
                            <form>
                                <input type="text" class="zip" placeholder="<?= GetMessage('ORDER_ZIP') ?> *">
                                <select class="select-country" name="country" required>
                                    <option value="example">-- <?= GetMessage('ORDER_COUNTRY') ?> --</option>
                                    <?
                                    foreach ($arResult['LOCATIONS'] as $key => $location) {
                                        if ($location['TYPE_CODE'] == 'COUNTRY') {
                                            ?>
                                            <option value="<?= $location['COUNTRY_ID'] ?>"><?= $location['NAME_RU'] ?></option>
                                            <?
                                        }
                                    } ?>
                                </select>
                                <select class="select-region" name="region" required>
                                    <option value="example">-- <?= GetMessage('ORDER_REGION') ?> --</option>
                                    <?
                                    foreach ($arResult['LOCATIONS'] as $key => $location) {
                                        if ($location['TYPE_CODE'] == 'REGION' && $location['IN_SELECT_ARR'] == 'Y') {
                                            ?>
                                            <option value="<?= $location['REGION_ID'] ?>"><?= $location['NAME_RU'] ?></option>
                                            <?
                                        }
                                    } ?>
                                </select>
                                <select class="select-city" name="city" required>
                                    <option value="example">-- <?= GetMessage('ORDER_CITY') ?> --</option>
                                    <?
                                    foreach ($arResult['LOCATIONS'] as $key => $location) {
                                        if ($location['TYPE_CODE'] == 'CITY' && $location['IN_SELECT_ARR'] == 'Y') {
                                            ?>
                                            <option value="<?= $location['CITY_ID'] ?>"
                                                    data-code="<?= $location['CODE'] ?>"><?= $location['NAME_RU'] ?></option>
                                            <?
                                        }
                                    } ?>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="order-message">
                        <p><?= GetMessage('ORDER_SHIPPING') ?></p>
                        <div class="select-delivery">
                            <?
                            foreach ($arResult['DELIVERY_LIST'] as $key => $delivery) {
                                if ($delivery['IN_SELECT_ARR'] = 'Y') {
                                    ?>
                                    <label><input type="radio" data-type="delivery" name="delivery"
                                                  value="<?= $delivery['CODE'] ?>"> <?= $delivery['NAME'] ?>
                                    </label>
                                    <?
                                }
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="review-payment">
            <h2><?= GetMessage('ORDER_REVIEW') ?></h2>
        </div>

        <div class="table-responsive cart_info">
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
                                    <? if (strtoupper($arResult['LANG']) == 'RU') {
                                        echo $product["NAME"];
                                    } else {
                                        echo $product['PROPERTY']['VALUE']['NAME_' . strtoupper($arResult['LANG'])];
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
        </div>
        <div class="payment-options">
            <?
            foreach ($arResult['PAY_SYSTEMS'] as $key => $paySystem) {
                if ($paySystem['IN_SELECT_ARR'] == 'Y') {
                    ?>
                    <span><label><input type="checkbox"
                                        value="<?= $paySystem['PAY_SYSTEM_ID'] ?>"> <?= $paySystem['NAME'] ?></label></span>
                    <?
                }
            } ?>
            <div class="finish-checkout">
                <a class="btn btn-primary finish-checkout-btn" href="#"><?= GetMessage('SBB_HEADER_CHECKOUT') ?></a>
            </div>
        </div>
    </div>
</section> <!--/#cart_items-->






























