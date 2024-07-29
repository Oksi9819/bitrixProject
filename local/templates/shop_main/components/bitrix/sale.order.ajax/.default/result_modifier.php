<?php

use Bitrix\Catalog\Product\Basket;
use Bitrix\Catalog\Model\Price;
use Bitrix\Catalog\ProductTable;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Sale;
use Bitrix\Sale\Delivery\Services\Base;
use Bitrix\Sale\Delivery\Services\Manager;
use Bitrix\Sale\Internals\ServiceRestrictionTable;
use Bitrix\Sale\Location\LocationTable;
use Bitrix\Sale\Order;
use Bitrix\Sale\OrderBase;
use Bitrix\Sale\PaymentCollection;
use Bitrix\Sale\PaySystem;
use Bitrix\Sale\Services\Base\RestrictionManager;
use Bitrix\Sale\Services\PaySystem\Restrictions\Delivery;
use Bitrix\Main\Localization\Loc;
use General\System\Data\Iblock;

Loader::includeModule("general.system");


/**
 * @var array $arParams
 * @var array $arResult
 * @var SaleOrderAjax $component
 */


// Подключение файла с языковыми сообщениями
/* На какой переключаем */
$arResult['LANG'] = isset($_SESSION['LANG']) ? $_SESSION['LANG'] : LANGUAGE_ID;
Loc::setCurrentLang($arResult['LANG']);


$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), 's1');
//PR($basket);
$items = $basket->getBasketItems();

$ibn = new Iblock(4, false, 'ID');
$filt = array();
$arResult['CART_PRODUCTS'] = array();
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

/*if (isset($prices)) {
    $arResult['PRICES'] = $prices;
}

$arResult['TOTAL'] = $total;
$arResult['CART'] = $arBasketItems;*/
//PR($arResult['PRICES']);
//PR($arResult['TOTAL']);
//PR($arResult['CART_PRODUCTS']);

//Order actions
$siteId = SITE_ID; // код сайта
global $USER;
$userId = $USER->GetID(); // ID пользователя
$order = Order::create($siteId, $userId);
$order->setBasket($basket);
$order->setPersonTypeId(1);
$propertyCollection = $order->getPropertyCollection();
//PR($propertyCollection);

$arResult['props'] = $arResult['ORDER_PROP']['USER_PROPS_Y'];

//Locations
$locations = array();
$locList = LocationTable::getList(array(
    'filter' => array('=NAME.LANGUAGE_ID' => LANGUAGE_ID, 'TYPE_CODE' => array('COUNTRY', 'REGION', 'CITY')),
    'select' => array('*', 'NAME_RU' => 'NAME.NAME', 'TYPE_CODE' => 'TYPE.CODE')
));
while ($item = $locList->fetch()) {
    $item['IN_SELECT_ARR'] = 'N';
    array_push($locations, $item);
}
$arResult['LOCATIONS'] = $locations;
//PR($arResult['LOCATIONS']);

//Deliveries
$shipmentCollection = $order->getShipmentCollection();
$shipment = $shipmentCollection->createItem();
$propertyLocation = $propertyCollection->getDeliveryLocation();
//PR($propertyLocation);

//PaymentSystems
$paySystems = array();
$paySystemList = Sale\PaySystem\Manager::getList(array(
    'filter' => array(
        'ACTIVE' => 'Y',
    )
));
while ($paySystem = $paySystemList->fetch()) {
    $paySystem['IN_SELECT_ARR'] = 'N';
    $dbRestriction = ServiceRestrictionTable::getList(array(
        'select' => array('PARAMS'),
        'filter' => array(
            'SERVICE_ID' => $paySystem['ID'],
            //'CLASS_NAME' => $helper->forSql('\Bitrix\Sale\Services\PaySystem\Restrictions\PersonType'),
            'SERVICE_TYPE' => Sale\Services\PaySystem\Restrictions\Manager::SERVICE_TYPE_PAYMENT
        )
    ));
    $restrictions = array();
    while ($restriction = $dbRestriction->fetch()) {
        if (is_array($restriction['PARAMS']))
            $restrictions = array_merge($restrictions, $restriction['PARAMS']);
    }
    $restriction = Delivery::prepareParamsValues(array(), $paySystem['ID']);
    $restrictions['DELIVERY'] = $restriction['DELIVERY'];
    $paySystem['RESTRICTIONS'] = $restrictions;
    array_push($paySystems, $paySystem);
}
$arResult['PAY_SYSTEMS'] = $paySystems;
//PR($arResult['PAY_SYSTEMS']);


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
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'SELECT_COUNTRY') {
        $countryId = $_POST['region_id'];
        foreach ($arResult['LOCATIONS'] as $key => $location) {
            if ($location['TYPE_CODE'] == 'REGION' && $location['COUNTRY_ID'] == $countryId) {
                $arResult['LOCATIONS'][$key]['IN_SELECT_ARR'] = 'Y';
            }
        }
    }
    if ($_POST['action'] == 'SELECT_REGION') {
        $regionId = $_POST['region_id'];
        foreach ($arResult['LOCATIONS'] as $key => $location) {
            if ($location['TYPE_CODE'] == 'CITY' && $location['REGION_ID'] == $regionId) {
                $arResult['LOCATIONS'][$key]['IN_SELECT_ARR'] = 'Y';
            }
        }
    }
    if ($_POST['action'] == 'OPEN_DELIVERY_OPTIONS') {
        $countryCode = $_POST['country_code'];
        $propertyLocation->setField('VALUE', $countryCode);
        $delList = Manager::getRestrictedList($shipment, RestrictionManager::MODE_CLIENT);

        $deliveryList = array();
        foreach ($delList as $deliveryService) {
            $deliveryService['IN_SELECT_ARR'] = 'Y';
            array_push($deliveryList, $deliveryService);
        }
        $arResult['DELIVERY_LIST'] = $deliveryList;
    }
    if ($_POST['action'] == 'OPEN_PAYMENT_OPTIONS') {
        $deliveryCode = $_POST['delivery_code'];
        foreach ($arResult['PAY_SYSTEMS'] as $key => $paySys) {
            if (in_array($deliveryCode, $paySys['RESTRICTIONS']['DELIVERY'])) {
                $arResult['PAY_SYSTEMS'][$key]['IN_SELECT_ARR'] = 'Y';
            }
        }
    }
    if ($_POST['action'] == 'FINISH_CHECKOUT') {
        $fio = $_POST['fio'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $zip = $_POST['zip'];
        $locationId = $_POST['locationId'];
        $cityCode = $_POST['cityCode'];
        $address = $_POST['address'];
        $deliveryCode = (int)$_POST['deliveryCode'];
        $paySystemId = (int)$_POST['paySystemId'];

        // Устанавливаем свойства
        $nameProp = $propertyCollection->getPayerName();
        $nameProp->setValue($fio);
        $emailProp = $propertyCollection->getUserEmail();
        $emailProp->setValue($email);
        $phoneProp = $propertyCollection->getPhone();
        $phoneProp->setValue($phone);
        $zipProp = $propertyCollection->getDeliveryLocationZip();
        $zipProp->setValue($zip);
        foreach ($arResult['LOCATIONS'] as $key => $location) {
            if ($location['TYPE_CODE'] == 'COUNTRY' && $location['COUNTRY_ID'] == $locationId) {
                $loc = $location['NAME_RU'];
                //$locationCode = $location['CODE'];
            }
            if ($location['TYPE_CODE'] == 'CITY' && $location['CODE'] == $cityCode) {
                $city = $location['NAME_RU'];
            }
        }
        if (isset($loc)) {
            foreach ($propertyCollection as $propertyObj) {
                if ($propertyObj->getField('CODE') == 'LOCATION') {
                    $propertyObj->setValue($loc);
                }
            }
        }
        if (isset($city)) {
            foreach ($propertyCollection as $propertyObj) {
                if ($propertyObj->getField('CODE') == 'CITY') {
                    $propertyObj->setValue($city);
                }
            }
        }
        $addressProp = $propertyCollection->getAddress();
        $addressProp->setValue($address);

        //Delivery
        $service = Manager::getById($deliveryCode);
        $shipment->setFields(array(
            'DELIVERY_ID' => $service['ID'],
            'DELIVERY_NAME' => $service['NAME'],
        ));
        $shipmentItemCollection = $shipment->getShipmentItemCollection();
        foreach ($items as $key => $basketItem) {
            $shipmentItem = $shipmentItemCollection->createItem($basketItem);
            $shipmentItem->setQuantity($basketItem->getQuantity());
        }

        //Payment
        $paymentCollection = $order->getPaymentCollection();
        $payment = $paymentCollection->createItem();
        $paySystemService = PaySystem\Manager::getObjectById($paySystemId);
        $payment->setFields(array(
            'PAY_SYSTEM_ID' => $paySystemService->getField("PAY_SYSTEM_ID"),
            'PAY_SYSTEM_NAME' => $paySystemService->getField("NAME"),
        ));
        $order->save();

        //Реадктируем доступное количество товара в каталоге
        foreach ($arResult['CART'] as $item) {
            $newQuant = $item['AVAILABLE_QUANTITY'] - $item['QUANTITY'];
            $quantArrFields = array(
                "QUANTITY" => $newQuant, //Кол-во товара
            );
            CCatalogProduct::Update($item['PRODUCT_ID'], $quantArrFields);
        }
        //$orderId = $order->getId();
    }

}

//$collection = $order->getPaymentCollection();
//PR($arResult['LANG']);
