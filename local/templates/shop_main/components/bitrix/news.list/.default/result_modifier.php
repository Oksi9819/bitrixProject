<?php

//global $USER;
//PR($USER);
$rsUser = CUser::GetByID($USER->GetID());
$arUser = $rsUser->Fetch();
$arResult['USER'] = array(
    'ID' => $arUser['ID'],
    'NAME' => $arUser['NAME'],
    'EMAIL' => $arUser['EMAIL'],
);


