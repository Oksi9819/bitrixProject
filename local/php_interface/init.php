<?php

use Bitrix\Main\Config\Configuration;
use Bitrix\Currency\CurrencyManager;
CModule::IncludeModule("currency");

function PR($o, $show = false, $die = false, $user_id = [1])
{
    global $USER, $APPLICATION;

    if (isset($_REQUEST['DEBUG-Y']) and $_REQUEST['DEBUG-Y'] == 'Y') {
        $show = true;
    }

    if ($die) {
        $APPLICATION->RestartBuffer();
    }

    if ((is_object($USER) and $USER->isAdmin() and in_array($USER->GetID(), $user_id)) || $show) {


        $bt = debug_backtrace();
        $bt = $bt[0];
        $dRoot = $_SERVER["DOCUMENT_ROOT"];
        $dRoot = str_replace("/", "\\", $dRoot);
        $bt["file"] = str_replace($dRoot, "", $bt["file"]);
        $dRoot = str_replace("\\", "/", $dRoot);
        $bt["file"] = str_replace($dRoot, "", $bt["file"]);
        ?>
        <div style='font-size: 12px;font-family: monospace;width: 100%;color: #181819;background: #EDEEF8;border: 1px solid #006AC5;'>
            <div style='padding: 5px 10px;font-size: 10px;font-family: monospace;background: #006AC5;font-weight:bold;color: #fff;'>
                File: <?= $bt["file"] ?> [<?= $bt["line"] ?>]
            </div>
            <pre style='padding:10px;text-align: left'><? print_r($o) ?></pre>
        </div>
        <?
    } else {
        return false;
    }
    if ($die) {
        die();
    }

}

//Function of selecting product in basket
function selectProdInBasket(int $needId, array $items): bool
{
    $result = false;
    foreach ($items as $key => $item) {
        if ($item->getProductId() === $needId) {
            global $result;
            $result = true;
        }
    }
    return $result;
}

function getRatePrices(int $elId): array
{
    $priceParams = CPrice::GetBasePrice($elId);
    $lcur = CCurrency::GetList(($by = "name"), ($order = "asc"), LANGUAGE_ID);
    //PR($lcur);
    //PR($priceParams);
    $prices = array();
    while ($lcur_res = $lcur->Fetch()) {
        if ($lcur_res['CURRENCY'] == $priceParams['CURRENCY']) {
            $prices[$elId] = array(
                $priceParams['CURRENCY'] => $priceParams['PRICE']
            );
        } else {
            $num = CCurrencyRates::ConvertCurrency($priceParams['PRICE'], $priceParams['CURRENCY'], $lcur_res['CURRENCY']);
            $prices[$elId][$lcur_res['CURRENCY']] = round($num, 2);
        }
    }
    return $prices;
}

function refreshRates(string $currency)
{
    if (CModule::IncludeModule('currency')) {
        // URL API НБРБ
        $url = 'https://api.nbrb.by/exrates/rates/' . $currency . '?parammode=2';

        // Получение данных API
        $data = file_get_contents($url);

        // Преобразование данных в формат JSON
        $jsonData = json_decode($data);

        $code = $jsonData->Cur_Abbreviation;
        $rate = $jsonData->Cur_OfficialRate;
        $scale = $jsonData->Cur_Scale;
        $date = $jsonData->Date;
        $posT = explode('T', $date);
        $date = $posT[0];
        $date = DateTimeImmutable::createFromFormat('Y-m-d', $date);
        $date = $date->format('d.m.Y');

        $arFields = array(
            "RATE" => $rate,
            "RATE_CNT" => $scale,
            "CURRENCY" => $code,
            "DATE_RATE" => $date
        );
        CCurrencyRates::Add($arFields);
    }
}

function UpdateExchangeRate()
{
    if (CModule::IncludeModule('currency')) {
        $base_currency = CCurrency::GetBaseCurrency();
        $rsCurrency = CCurrency::GetList($by, $order);

        while ($curr = $rsCurrency->GetNext()) {
            if ($curr['CURRENCY'] !== $base_currency) {
                refreshRates($curr['CURRENCY']);
            }
        }
    }
    return "UpdateExchangeRate();";
}

function GetLangList(): array
{
    $result = array();
    $langList = CLanguage::GetList("lid", "asc", array('ACTIVE' => 'Y'));
    while ($arLang = $langList->Fetch()) {
        array_push($result, strtoupper($arLang['LID']));
        if ($arLang['DEF'] == 'Y') {
            $GLOBALS["DEF_LANG"] = $arLang['LID'];
        }
    }
    return $result;
}

$GLOBALS["LANG_LIST"] = GetLangList();
$GLOBALS["BASE_CURRENCY"] = CCurrency::GetBaseCurrency();


//PARSER!!!
function Parser()
{
    // URL страницы каталога
    $url = 'https://fh.by/women/category/plaschi-i-trenchi-obschee-362';
    $parseUrl = parse_url($url);

    // Получаем HTML-код страницы каталога
    $html = file_get_contents($url);
    //PR($html);

    // Создаем объект DOMDocument и загружаем HTML-код страницы
    $dom = new DOMDocument();
    libxml_use_internal_errors(true); // Игнорируем ошибки парсинга HTML
    $dom->loadHTML($html);
    libxml_use_internal_errors(false);

    // Создаем объект DOMXPath для выполнения запросов XPath
    $xpath1 = new DOMXPath($dom);

    // Находим все элементы с тегом <a> и классом "product-link"
    $query = '//a[contains(@class, "ProductCard")]';
    $productLinks = $xpath1->query($query);

    $sectionToAdd = 35;

    // Обходим найденные ссылки на товары
    foreach ($productLinks as $link) {
        // Получаем URL товара
        $productUrl = $link->getAttribute('href');
        $productUrl = $parseUrl['scheme'] . "://" . $parseUrl['host'] . $productUrl;
        //PR($productUrl);

        // Получаем HTML-код страницы товара
        $productHtml = file_get_contents($productUrl);
        //PR($productHtml);

        // Создаем новый объект DOMDocument и загружаем HTML-код страницы товара
        $productDom = new DOMDocument();
        libxml_use_internal_errors(true); // Игнорируем ошибки парсинга HTML
        $productDom->loadHTML($productHtml);
        libxml_use_internal_errors(false);

        $xpath2 = new DOMXPath($productDom);
        // Находим элемент с классом "product-title"
        //Brand
        $query = '//span[contains(@class, "Product_brand")]';
        $brandCont = $xpath2->query($query);
        $brandNameVal = trim($brandCont[0]->nodeValue);
        //$brandName = $brandNameCont[0]->childNodes;
        //$brandNameVal = $brandName[1]->nodeValue;
        PR($brandNameVal);

        //Name
        $query = '//span[contains(@class, "Product_desc")]';
        $nameCont = $xpath2->query($query);
        $nameVal = $nameCont[0]->nodeValue;
        PR($nameVal);

        //Артикул
        $productValue = $xpath2->query('//span[contains(@class, "Product_value")]');
        $article = $productValue[0]->nodeValue;
        PR($article);

        //Состав
        if (($productValue->length) == 5) {
            $material = $productValue[1]->nodeValue;
            PR($material);
        } else {
            $material = $productValue[2]->nodeValue;
            PR($material);
        }

        //Цена
        $price = $xpath2->query('//div[contains(@class, "Price")]');
        if ($price->length == 3) {
            $priceValue = ($price[0]->childNodes)[0]->nodeValue;
            $repl = array(' BYN', ' ');
            $pr = str_replace($repl, '', $priceValue);
            $priceNum = str_replace(',', '.', $pr);
            PR($priceNum);
        } else {
            $priceValue = $price[0]->nodeValue;
            $repl = array(' BYN', ' ');
            $pr = str_replace($repl, '', $priceValue);
            $priceNum = str_replace(',', '.', $pr);
            PR($priceNum);
        }


        //Описание
        $desc = $xpath2->query('//p[contains(@class, "Product_description")]');
        $descValue = $desc[0]->nodeValue;
        //PR($descValue);

        //Ссылки на изображения
        // Находим все изображения на странице каталога
        $images = $xpath2->query('//img[contains(@class, "ProductGallery_image")]');
        //PR($images);
        $gallery = array();
        for ($g = 1; $g < count($images); $g++) {
            $src = CFile::MakeFileArray($images[$g]->getAttribute("src"));
            array_push($gallery, $src);
        }

        //транслит символьного кода
        $params = array(
            "max_len" => "75", // обрезает символьный код до 75 символов
            "change_case" => "L", // буквы преобразуются к нижнему регистру
            "replace_space" => "-", // меняем пробелы на нижнее подчеркивание
            "replace_other" => "-", // меняем левые символы на нижнее подчеркивание
            "delete_repeat_replace" => "true", // удаляем повторяющиеся нижние подчеркивания
            "use_google" => "false", // отключаем использование google
        );
        $CODE_translit = CUtil::translit($nameVal, "ru", $params);

        $detailPageUrl = $sections[21]['SECTION_PAGE_URL'] . $CODE_translit . '/';

        $detailPic = CFile::MakeFileArray($images[0]->getAttribute("src"));
        $detailPicture = $detailPic;

        //IDs products in catalog
        $resy = ProductTable::getList(array(
            'filter' => array("=IBLOCK_ELEMENT.IBLOCK_ID" => 4),
        ));

        $catalogIds = array();

        while ($ar_res = $resy->Fetch()) {
            array_push($catalogIds, $ar_res['ID']);
        }

        //Рекомендации
        $randKeys = array_rand($catalogIds, 5);
        $recommendations = array();

        foreach ($randKeys as $randKey) {
            array_push($recommendations, $catalogIds[$randKey]);
            //PR($catalogIds[$randKey]);
        }

        //Add new product
        $PROP = array();       // здесь у нас будут храниться свойства
        $PROP['BREND_REF'] = $brandNameVal;
        $PROP['ARTNUMBER'] = $article;
        $PROP['CONDITION'] = 'Новое';
        $PROP['MORE_PHOTO'] = $gallery;
        $PROP['MATERIAL'] = $material;
        $PROP['RECOMMEND'] = $recommendations;

        //Добавляем элемент инфоблока
        $arLoadProductArray = array(
            "ACTIVE_FROM" => date('d.m.Y H:i:s'), // обязательно нужно указать дату начала активности элемента
            "IBLOCK_SECTION_ID" => $sectionToAdd, // В корне или нет
            "IBLOCK_ID" => 4, //  собственно сам id блока куда будем добавлять новый элемент
            "NAME" => $nameVal,
            "CODE" => $CODE_translit,
            "DETAIL_PAGE_URL" => $detailPageUrl,
            "DETAIL_PICTURE" => $detailPicture,
            "ACTIVE" => "Y", // активен или  N не активен
            "PROPERTY_VALUES" => $PROP,  // Добавим нашему элементу заданные свойства
        );
        $el = new CIBlockElement;
        $newElId = $el->Add($arLoadProductArray);

        //Добавляем товар в торговый каталог
        $productFields = array(
            "ID" => $newElId, //ID добавленного элемента инфоблока
            "VAT_INCLUDED" => "Y", //НДС входит в стоимость
            "TYPE " => ProductTable::TYPE_PRODUCT //Тип товара
        );
        CCatalogProduct::Add($productFields);

        //Return ID to add price
        PR($newElId);
        if (!empty($newElId)) {
            //Добавляем или обновляем цену товара
            $arFieldsPrice = array(
                "PRODUCT_ID" => $newElId,                         //ID типа цены
                "PRICE" => $priceNum,                        //значение цены
                "CATALOG_GROUP_ID" => 1,
                "CURRENCY" => "BYN",    // валюта
            );

            //Смотрим установлена ли цена для данного товара
            $dbPrice = Price::getList([
                "filter" => array(
                    "PRODUCT_ID" => $newElId,
                )
            ]);

            if ($arPrice = $dbPrice->fetch()) {
                //Если цена установлена, то обновляем
                $result = Price::update($arPrice["ID"], $arFieldsPrice);

                if ($result->isSuccess()) {
                    echo "Обновили цену у товара у элемента каталога ";
                } else {
                    echo "Ошибка обновления цены у товара у элемента каталога ";
                }
            } else {
                //Если цены нет, то добавляем
                $result = Price::add($arFieldsPrice);
                if ($result->isSuccess()) {
                    echo "Добавили цену у товара у элемента каталога ";
                } else {
                    echo "Ошибка добавления цены у товара у элемента каталога ";
                }
            }

            //Добавляем или обновляем общее кол-во товара (параметр "Доступное кол-во")
            $quantArrFields = array(
                "QUANTITY" => 30, //Кол-во товара
            );
            if (CCatalogProduct::Update($newElId, $quantArrFields)) {
                echo "Добавили quantity у товара у элемента каталога ";
            } else {
                echo "Ошибка добавления quantity у товара у элемента каталога ";
            }
        } else {
            echo "Prices & quantity were not updated";
        }
    }
}

