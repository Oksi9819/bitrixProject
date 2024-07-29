<?php

use Bitrix\Catalog\Product\Basket;
use Bitrix\Currency\CurrencyManager;
use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Context;
use Bitrix\Main\UI\PageNavigation;
use Bitrix\Sale;
use Bitrix\Sale\BasketItem;
use Bitrix\Main\Loader;
use Bitrix\Main\Entity;
use General\System\Data\Highload;


Loader::includeModule("general.system");

$prices = array();
$cart = array();
$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
//PR($basket);
if ($basket) {
    $items = $basket->getBasketItems();
}

//Список сравнивнимаемых товаров
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


// создаем объект пагинации
$filter = array("=IBLOCK_ID" => 4, "=ID" => "343 | 344 | 345 | 346");
$nav = new PageNavigation("nav-more-news");
$nav->allowAllRecords(true)
    ->setPageSize(5)
    ->initFromUri();
$newsList = \Bitrix\Iblock\ElementTable::getList(
    array(
        "filter" => $filter,
        "count_total" => true,
        "offset" => $nav->getOffset(),
        "limit" => $nav->getLimit(),
    )
);
$nav->setRecordCount($newsList->getCount());
while ($news = $newsList->fetch()) {
}
//PR($nav);
$arResult['NAV'] = $nav;


//Вывод товаров из вишлиста
$wishlistProducts = array();

foreach ($arResult['IN_WISHLIST'] as $key => $wishItemId):
    $catalogEl = CCatalogProduct::GetByIDEx($wishItemId);
    $catalogEl['DETAIL_PICTURE'] = CFile::GetPath($catalogEl['DETAIL_PICTURE']);
    $wishlistProducts[] = $catalogEl;

    //Prices actions
    $arResult['PRICES'] += getRatePrices($wishItemId);

    //Basket actions
    if (isset($items)) {
        if (selectProdInBasket($wishItemId, $items)) {
            array_push($cart, $wishItemId);
        }
    }
endforeach;
$arResult['WISHLIST_ITEMS'] = $wishlistProducts;
$arResult['IN_BASKET'] = $cart;


if (isset($_POST['action'])) {
    //ADD TO BASKET
    if ($_POST['action'] == 'ADD2BASKET') {
        $productId = (int)$_POST['itemId'];
        $productName = $_POST['itemName'];
        $productPrice = (int)$_POST['itemPrice'];
        $basketItem = $basket->createItem('catalog', $productId);
        $basketItem->setFields([
            'QUANTITY' => 1,
            'CURRENCY' => 'BYN',
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

    //DElETE FROM WISHLIST
    if ($_POST['action'] == 'ACT_WITH_WISHLIST') {
        $productId = (int)$_POST['itemId'];
        $wishlistID = $wishlist[0]['ID'];
        if (count($arResult['IN_WISHLIST']) == 1) {
            $result = $hghlo->deleteElementHighload($wishlistID);
        } else {
            $wishlistItems = $arResult['IN_WISHLIST'];
            $wishlistItems = array_diff($wishlistItems, [$productId]);
            $data = array(
                'UF_ITEMS' => $wishlistItems
            );
            $result = $hghlo->updateElementHighload($wishlistID, $data);
        }
    }
}

//PR();
//PR($arResult['PRICES']);
//PR($_SESSION);
//PR($arResult['IN_WISHLIST']);
