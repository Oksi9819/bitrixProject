<?php

use Bitrix\Catalog\Product\Basket;
use \Bitrix\Sale\BasketPropertyItem;
use Bitrix\Sale;
use Bitrix\Sale\Order;
use Bitrix\Main\Loader;
use General\System\Data\Iblock;

Loader::includeModule("general.system");

$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), 's1');
$items = $basket->getBasketItems();

$ibn = new Iblock(4, false, 'ID');
$filt = array();
$arResult['CART_PRODUCTS'] = array();

if (!empty($items)) {
    $total = array();
    $total['BASE']['CURRENCY'] = CCurrency::GetBaseCurrency();
    $curs = CCurrency::GetList(($by = "name"), ($order = "asc"), LANGUAGE_ID);
    while ($curs_res = $curs->Fetch()) {
        $total[$curs_res['CURRENCY']] = 0;
        foreach ($basket as $item) {
            $elId = $item->getProductId();
            $filt['ID'] = $elId;
            $elInfo = $ibn->GetData($filt);
            $elInfo[$elId]['QUANTITY'] = $item->getQuantity();
            $elInfo[$elId]['PREVIEW_PICTURE'] = CFile::ResizeImageGet(
                $elInfo[$elId]['DETAIL_PICTURE'],
                array("width" => 180, "height" => 200),
                BX_RESIZE_IMAGE_PROPORTIONAL
            );
            $elInfo[$elId]['DETAIL_PICTURE'] = CFile::GetPath($elInfo[$elId]['DETAIL_PICTURE']);
            $elInfo[$elId]['AVAILABLE_QUANTITY'] = CCatalogProduct::GetByID($elId)['QUANTITY'];
            $picInfo = CFile::MakeFileArray($elInfo[$elId]['DETAIL_PICTURE']);
            $arResult['CART_PRODUCTS'] += $elInfo;
            $priceParams = CPrice::GetBasePrice($elId);
            if ($curs_res['CURRENCY'] == $priceParams['CURRENCY']) {
                $total['BASE']['VALUE'] += $item->getFinalPrice();
                $total[$curs_res['CURRENCY']] += $item->getFinalPrice();
                $arr = array(
                    'PRICE' => $priceParams['PRICE'],
                    'SUM' => $item->getFinalPrice()
                );
                $prices[$elId][$priceParams['CURRENCY']] = $arr;
            } else {
                $num = CCurrencyRates::ConvertCurrency($priceParams['PRICE'], $priceParams['CURRENCY'], $curs_res['CURRENCY']);
                $numTotal = CCurrencyRates::ConvertCurrency($item->getFinalPrice(), $priceParams['CURRENCY'], $curs_res['CURRENCY']);
                $total[$curs_res['CURRENCY']] += round($numTotal, 2);
                $prices[$elId][$curs_res['CURRENCY']] = array(
                    'PRICE' => round($num, 2),
                    'SUM' => round($numTotal, 2)
                );
            }
        }
    }
    $arResult['TOTAL'] = $total;

    if (isset($prices)) {
        $arResult['PRICES'] = $prices;
    }

    if (isset($_POST['ajax'])) {
        if ($_POST['action'] == 'DELETE_FROM_BASKET') {
            $needId = $_POST['id'];

            foreach ($items as $key => $item) {
                if ($item->getProductId() == $needId) {
                    $it = $item;
                }
            }
            if (isset($it)) {
                $id = $it->getId();
                $result = $basket->getItemById($id)->delete();
                if ($result->isSuccess()) {
                    $basket->save();
                    return 'Success';
                } else return 'Failure';
            }
            return "There is no such product";
        } elseif ($_POST['action'] == 'DECREASE_IN_BASKET') {
            $needId = $_POST['id'];
            $amountDiff = (int)$_POST['amount'];

            foreach ($items as $key => $item) {
                if ($item->getProductId() == $needId) {
                    $it = $item;
                }
            }
            if (isset($it)) {
                $id = $it->getId();
                $quant = $it->getQuantity();
                if ($quant == 1) {
                    $result = $basket->getItemById($id)->delete();
                    if ($result->isSuccess()) {
                        $basket->save();
                        return 'Success';
                    } else return 'Failure';
                } else {
                    $it->setField('QUANTITY', $it->getQuantity() - $amountDiff);
                    $basket->save();
                    return true;
                }
            }
            return "There is no such product";
        } elseif ($_POST['action'] == 'INCREASE_IN_BASKET') {
            $needId = $_POST['id'];
            $amountDiff = (int)$_POST['amount'];

            foreach ($items as $key => $item) {
                if ($item->getProductId() == $needId) {
                    $it = $item;
                }
            }
            if (isset($it)) {
                $id = $it->getId();
                $paramProd = CCatalogProduct::GetByID($needId);
                $quantity = $paramProd['QUANTITY'];
                $quant = $it->getQuantity();
                if ($quant < $quantity) {
                    $it->setField('QUANTITY', $it->getQuantity() + $amountDiff);
                    $basket->save();
                    return true;
                } else return 'Failure. There is no more product available for buying in the shop.';
            }
            return "There is no such product";
        }
        $arResult['AJAX'] = 'Y';
    }
    $arResult['CART_IS_EMPTY'] = 'N';
} else $arResult['CART_IS_EMPTY'] = 'Y';


//Function of deleting all products in basket
/*function deleteAllProdFromBasket(array $items, $basket): bool
{
    foreach ($items as $item) {
        $item->delete();
    }
    $basket->save();
    return true;
}*/
//PR($arResult['PRICES']);
//PR($arResult['CART_PRODUCTS']);
//PR($ibn->GetData($filt));