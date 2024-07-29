<?php
$arSort = array(
$arParams["SORT_BY1"]=>$arParams["SORT_ORDER1"],
$arParams["SORT_BY2"]=>$arParams["SORT_ORDER2"],
);

$arSelect = array(
"ID",
"NAME",
"DETAIL_PAGE_URL"
);

$arFilter = array (
"IBLOCK_ID" => $arResult["IBLOCK_ID"],
"ACTIVE" => "Y",
"CHECK_PERMISSIONS" => "Y",
);

$arNavParams = array(
"nPageSize" => 1,
"nElementID" => $arResult["ID"],
);
$rsElement = CIBlockElement::GetList($arSort, $arFilter, false, $arNavParams, $arSelect);
$rsElement->SetUrlTemplates($arParams["DETAIL_URL"]);
while($obElement = $rsElement->GetNextElement())
$arItems[] = $obElement->GetFields();
if(count($arItems)==3):
$arResult["PREV"] = Array("NAME"=>$arItems[0]["NAME"], "URL"=>$arItems[0]["DETAIL_PAGE_URL"]);
$arResult["NEXT"] = Array("NAME"=>$arItems[2]["NAME"], "URL"=>$arItems[2]["DETAIL_PAGE_URL"]);
elseif(count($arItems)==2):
if($arItems[0]["ID"]!=$arResult["ID"])
$arResult["PREV"] = Array("NAME"=>$arItems[0]["NAME"], "URL"=>$arItems[0]["DETAIL_PAGE_URL"]);
else
$arResult["NEXT"] = Array("NAME"=>$arItems[1]["NAME"], "URL"=>$arItems[1]["DETAIL_PAGE_URL"]);
endif;

//Мой код. Добавляет значения при совершенном голосовании
if($_POST['action'] === "toRevote") {
    $el = new CIBlockElement;
    $newVoteValue = $_POST['voteValue'];
    $idToChangeVote = $_POST['id_new'];
    $voteNum = $arResult['PROPERTIES']['VOTES']['VALUE'];
    $arrVoteValue = $arResult['PROPERTIES']['ARR_VOTE_VALUE']['VALUE'];
    $newArrVoteValue = ($arrVoteValue + $newVoteValue) / 2;
    $properties = array (
        'VOTES' => $voteNum + 1,
        'ARR_VOTE_VALUE' => $newArrVoteValue
    );
    $arLoadProductArray = Array(
        "PROPERTY_VALUES"=> $properties,
    );
    $res =  $el->Update($idToChangeVote, $arLoadProductArray);

    if ($res>0) {
        $GLOBALS['APPLICATION']->RestartBuffer();
        echo true;
        die();
    } else {
        $GLOBALS['APPLICATION']->RestartBuffer();
        echo false;
        die();
    }
}