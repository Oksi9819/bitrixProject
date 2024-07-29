<?php

use Bitrix\Catalog\Product\Basket;
use Bitrix\Sale;

$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
if ($basket) {
    $items = $basket->getBasketItems();
}


$cart = array();
foreach ($arResult['ITEMS'] as $cell => $arElement):
    $elId = $arElement['ID'];

    //Basket actions
    if (isset($items)) {
        foreach ($items as $key => $item) {
            if ($item->getProductId() === $elId) {
                array_push($cart, $elId);
            }
        }
    }
endforeach;
$arResult['IN_BASKET'] = $cart;
//PR($arResult['ITEMS']);
//PR($arResult['IN_BASKET']);