<?php

use Bitrix\Catalog\Model\Price;
use Bitrix\Catalog\Product\Basket;
use Bitrix\Catalog\ProductTable;
use Bitrix\Currency\CurrencyManager;
use Bitrix\Main\Context;
use Bitrix\Sale;
use Bitrix\Sale\BasketItem;
use Bitrix\Main\Loader;
use General\System\Data\Iblock;

Loader::includeModule("general.system");

$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
if ($basket) {
    $items = $basket->getBasketItems();
}

$elId = $arResult['ID'];
$GLOBALS['ITEM_ID'] = $arResult['ID'];
if (isset($items)) {
    if (selectProdInBasket($elId, $items)) {
        $arResult['IN_BASKET'] = 'Y';
    } else $arResult['IN_BASKET'] = 'N';
}

$arResult['PRICES'] = getRatePrices($elId);
//PR($arResult['PRICES']);

//From comments
$arSort = array(
    $arParams["SORT_BY1"] => $arParams["SORT_ORDER1"],
    $arParams["SORT_BY2"] => $arParams["SORT_ORDER2"],
);

$arSelect = array(
    "ID",
    "PROPERTY_REVIEW_PRODUCT_ID",
    "ACTIVE_FROM",
    "PROPERTY_ID_USER_COMMENT",
    "PROPERTY_USER_NAME",
    "PROPERTY_EMAIL",
    "PROPERTY_RATING",
    "PROPERTY_COMMENT",
    "DATE_ACTIVE_FROM"
);

$arFilter = array(
    "PROPERTY_REVIEW_PRODUCT_ID" => $GLOBALS['ITEM_ID'],
    "ACTIVE" => "Y",
    "CHECK_PERMISSIONS" => "Y",
);

$ibObj = new Iblock($arParams["LINK_IBLOCK_ID"], false, 'ID');
$arItems = $ibObj->GetData($arFilter);

if (isset($arItems)) {
    $GLOBALS['SELECTED_COMMENTS'] = $arItems;
    $GLOBALS['CNT_COMMENTS'] = count($arItems);
}
$GLOBALS['VOTES'] = $arResult['PROPERTIES']['VOTES']['VALUE'];
$GLOBALS['VOTES_VALUE'] = $arResult['PROPERTIES']['ARR_VOTE_VALUE']['VALUE'];


//Add product to basket
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

    //Send a review
    if ($_POST['action'] == 'WRITE_REVIEW') {
        $el1 = new CIBlockElement;
        $el2 = new CIBlockElement; // обязательно указываем класс
        $newVoteValue = $_POST['vote_value'];
        $idToChangeVote = $elId;
        $userId = $_POST['user_id'];
        $userName = $_POST['user_name'];
        $reviewText = $_POST['review_text'];
        $userEmail = $_POST['user_email'];

        //Update product card info
        if (!empty($GLOBALS['VOTES_VALUE'])) {
            $arrVoteValue = $GLOBALS['VOTES_VALUE'];
            $newArrVoteValue = ($arrVoteValue + $newVoteValue) / 2;
        } else {
            $newArrVoteValue = $newVoteValue;
        }
        if (!empty($GLOBALS['VOTES'])) {
            $voteNum = $GLOBALS['VOTES'];
        } else {
            $voteNum = 0;
        }
        $properties = array(
            'BREND_REF' => $arResult['PROPERTIES']['BREND_REF']['VALUE'],
            'ARTNUMBER' => $arResult['PROPERTIES']['ARTNUMBER']['VALUE'],
            'CONDITION' => $arResult['PROPERTIES']['CONDITION']['VALUE'],
            'MORE_PHOTO' => $arResult['PROPERTIES']['MORE_PHOTO']['VALUE'],
            'MATERIAL' => $arResult['PROPERTIES']['MATERIAL']['VALUE'],
            'RECOMMEND' => $arResult['PROPERTIES']['RECOMMEND']['VALUE'],
            'VOTES' => $voteNum + 1,
            'ARR_VOTE_VALUE' => $newArrVoteValue,
        );
        $arLoadProductArray1 = array(
            "PROPERTY_VALUES" => $properties,
        );
        $res = $el1->Update($idToChangeVote, $arLoadProductArray1);

        //Add new review
        $PROP = array();       // здесь у нас будут храниться свойства
        $PROP['REVIEW_PRODUCT_ID'] = $arResult['ID'];
        $PROP['EMAIL'] = $userEmail;
        $PROP['RATING'] = $newVoteValue;
        $PROP['COMMENT'] = $reviewText;
        $PROP['USER_NAME'] = $userName;
        $PROP['ID_COMMENT_USER'] = $userId;

        $arLoadProductArray2 = array(
            "ACTIVE_FROM" => date('d.m.Y H:i:s'), // обязательно нужно указать дату начала активности элемента
            "MODIFIED_BY" => $userId, // указываем какой пользователь добавил элемент
            "IBLOCK_SECTION_ID" => false, // В корне или нет
            "IBLOCK_ID" => $arParams["LINK_IBLOCK_ID"], //  собственно сам id блока куда будем добавлять новый элемент
            "NAME" => $arResult['NAME'],
            "ACTIVE" => "Y", // активен или  N не активен
            "PROPERTY_VALUES" => $PROP,  // Добавим нашему элементу заданные свойства
        );
        $newElement = $el2->Add($arLoadProductArray2);
    }
}
//PR($arResult);
