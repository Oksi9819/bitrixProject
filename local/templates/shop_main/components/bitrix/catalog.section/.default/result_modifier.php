<?php

use Bitrix\Catalog\Model\Price;
use Bitrix\Catalog\Product\Basket;
use Bitrix\Catalog\ProductTable;
use Bitrix\Currency\CurrencyManager;
use Bitrix\Main\Context;
use Bitrix\Sale;
use Bitrix\Sale\BasketItem;
use Bitrix\Main\Loader;
use Bitrix\Main\Entity;
use General\System\Data\Highload;
use General\System\Data\Iblock;

Loader::includeModule("general.system");

$prices = array();
$cart = array();
$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
//PR($basket);
if ($basket) {
    $items = $basket->getBasketItems();
}

foreach ($arResult["ITEMS"] as $cell => $arElement):
    $elId = $arElement['ID'];
    $arResult['PRICES'] += getRatePrices($elId);

    //Basket actions
    if (isset($items)) {
        if (selectProdInBasket($elId, $items)) {
            array_push($cart, $elId);
        }
    }
endforeach;
$arResult['IN_BASKET'] = $cart;

$compareList = array();
if (isset($_SESSION['CATALOG_COMPARE_LIST'])) {
    foreach ($_SESSION['CATALOG_COMPARE_LIST'][$arParams['IBLOCK_ID']]['ITEMS'] as $key => $item):
        array_push($compareList, $item['ID']);
    endforeach;
}
if (!empty($compareList)) {
    $arResult['IN_COMPARE'] = $compareList;
}

//Получение список товаров из Wishlist пользователя
$hghlo = new Highload(5);
$filterHB = array("UF_USER" => $USER->GetID());
$orderHB = array('ID' => 'ASC');
$selectHB = array('ID', 'UF_USER', 'UF_ITEMS');
$limitHB = 100;
$wishlist = $hghlo->getElementHighload($filterHB, $orderHB, $selectHB, $limitHB);
if (!empty($wishlist)) {
    $arResult['IN_WISHLIST'] = $wishlist[0]['UF_ITEMS'];
}

if (isset($_POST['action'])) {
    //ADD TO BASKET
    if ($_POST['action'] == 'ADD2BASKET') {
        $productId = (int)$_POST['itemId'];
        $productName = $_POST['itemName'];
        $productPrice = (int)$_POST['itemPrice'];
        $currency = (string)(trim($_POST['currency']));
        $lang = $_POST['lang'];
        $basketItem = $basket->createItem('catalog', $productId);
        $basketItem->setFields([
            'QUANTITY' => 1,
            'CURRENCY' => $currency,
            'LID' => Context::getCurrent()->getSite(),
            'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider'
        ]);

        $basketItem->save();

        $props = [
            'NAME' => $productName,
            'PRICE' => $productPrice
        ];

        $propertyCollection = $basketItem->getPropertyCollection();
        $propertyCollection->getPropertyValues();
        $propertyCollection->setProperty($props);
        $propertyCollection->save();

        $basketItem->setPropertyCollection($propertyCollection);
        $basketItem->save();
    }
    $basket->save();

    //ADD TO WISHLIST
    if ($_POST['action'] == 'ACT_WITH_WISHLIST') {
        $productId = (int)$_POST['itemId'];

        if (empty($wishlist)) {
            //Добавляем запись
            $data = array(
                'UF_USER' => $USER->GetID(),
                'UF_ITEMS' => [$productId]
            );
            $result = $hghlo->setNewElementHighload($data);
        } else {
            //Обновляем запись
            $wishlistID = $wishlist[0]['ID'];
            $wishlistDeleted = 'N';
            $wishlistItems = $arResult['IN_WISHLIST'];
            if (!in_array($productId, $wishlistItems)) { //Проверяем, есть ли данный товар в вишлисте юзера, если да - удаляем
                array_push($wishlistItems, $productId);
            } elseif (count($arResult['IN_WISHLIST']) == 1) {
                //удаляем вишлист пользователя, если у него всего был 1 товар в нем
                $result = $hghlo->deleteElementHighload($wishlistID);
                $wishlistDeleted = 'Y';
            } else {
                $wishlistItems = array_diff($wishlistItems, [$productId]);
            }
            if ($wishlistDeleted == 'N') {
                $data = array(
                    'UF_ITEMS' => $wishlistItems
                );
                $result = $hghlo->updateElementHighload($wishlistID, $data);
            }
        }
    }
}


//PR();
//$amountOfItems = count($arResult["ITEMS"]);
//echo CPrice::GetBasePrice($arElement['ID'])['CURRENCY'];
//PR($arResult["PRICES"]);
//PR($_SESSION);
//PR($arResult['IN_BASKET']);
//PR();
//$_COOKIE['CURRENCY'] = 'BYN';
//PR($arResult['IN_WISHLIST']);
//PR($GLOBALS["LANG_LIST"]);
//PR($GLOBALS["DEF_LANG"]);
//PR(strtoupper(LANGUAGE_ID));