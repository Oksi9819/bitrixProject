<?php

namespace General\System\Data;


class Iblock extends Base
{
    protected $KEY = '';
    protected $IBLOCK_ID = '';

    public function __construct($IBLOCK_ID = false, $IBLOCK_CODE = false, $KEY = 'ID')
    {
        \CModule::IncludeModule('iblock');
        $this->KEY = $KEY;
        if($IBLOCK_ID){
            $this->IBLOCK_ID = $IBLOCK_ID;
        }elseif($IBLOCK_CODE){
            $this->IBLOCK_ID = $this->GetIblockID($IBLOCK_CODE);
        }else {
            echo 'Укажите ID или CODE инфоблока';
        }
    }

    /***
     * @param array $arFilter
     * @return array
     */
    public function GetData($arFilter)
    {
        $arFilter['IBLOCK_ID']=$this->IBLOCK_ID;
        $arData = [];
        $arSelect = array("ID", 'PREVIEW_TEXT', "IBLOCK_ID", "*", "PROPERTY_*");
        $res = \CIBlockElement::GetList(
            ['sort' => 'ASC'],
            $arFilter,
            false,
            false,
            $arSelect
        );
        while ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
            $key = $arFields[$this->KEY] ? $arFields[$this->KEY] : $this->KEY;
            $arData[$key] = $this->RemoveKeyT($arFields);
            $props = $ob->GetProperties();
            $prop = array_column($props, 'VALUE', 'CODE');
            $prop = array_diff($prop, ['']);
            $arData[$key]['PROPERTY']['VALUE'] = $prop;

            $propDescription = array_column($props, 'DESCRIPTION', 'CODE');
            $propDescription = array_diff($propDescription, ['']);
            $arData[$key]['PROPERTY']['DESCRIPTION'] = $propDescription;

            unset($arData[$key]['SEARCHABLE_CONTENT']);
        }

        return $arData;
    }

    public function GetDataMin($arFilter)
    {
        $arFilter['IBLOCK_ID']=$this->IBLOCK_ID;
        $arData = [];
        $arSelect = array("ID", "IBLOCK_ID", "NAME", 'CODE');
        $res = \CIBlockElement::GetList(
            ['sort' => 'ASC'],
            $arFilter,
            false,
            false,
            $arSelect
        );
        while ($ob = $res->GetNext()) {
            $arData[]=$ob;
        }

        return $arData;
    }

    public function  GetSection($arFilter, $arSelect = []){
        $arData = [];
        $arFilter['IBLOCK_ID'] = $this->IBLOCK_ID;
        $Select = array_merge(["ID", "IBLOCK_ID", "NAME", 'CODE', 'UF_*'], $arSelect);
        $db_list = \CIBlockSection::GetList(Array('name'=>'asc'), $arFilter, false, $Select);
        while($ar_result = $db_list->GetNext())
        {
            $arData[$ar_result[$this->KEY]]=$ar_result;
        }
        return $arData;
    }

    public function AddSection($arFields){
        $bs = new \CIBlockSection;
        $Field = array_merge(["IBLOCK_ID" => $this->IBLOCK_ID], $arFields);
        $ID = $bs->Add($Field);
        if($ID > 0) {
            return $ID;
        }else{
            return $bs->LAST_ERROR;
        }
    }

    /***
     * @param array $arData
     * @return array
     */
    protected function RemoveKeyT($arData)
    {
        $arData = array_filter($arData, function ($k) {
            return strrpos($k, '~') === false ? true : false;
        }, ARRAY_FILTER_USE_KEY);
        return array_diff($arData, ['']);
    }

    /***
     * @param $iblockCode
     * @return false|mixed
     */
    protected function GetIblockID($iblockCode)
    {
        static $arIblock = [];
        $result = false;

        if (!empty($arIblock[$iblockCode])) {
            $result = $arIblock[$iblockCode];
        } else if (empty($arIblockID) && \Bitrix\Main\Loader::includeModule('iblock')) {
            $o = \CIBlock::GetList([]);
            while ($r = $o->Fetch()) {
                $arIblock[$r['CODE']] = (int)$r['ID'];
            }

            if (!empty($arIblock[$iblockCode])) {
                $result = $arIblock[$iblockCode];
            }
        }

        return $result;
    }


    public function AddElement($arLoadProductArray, $PROP){
        if(is_array($arLoadProductArray) && !empty($arLoadProductArray)){
            $el = new CIBlockElement;
            $arLoadProductArray['PROPERTY_VALUES'] = $PROP;
            $arLoadProductArray['IBLOCK_ID'] = $this->IBLOCK_ID;
            $arLoadProductArray['ACTIVE'] = 'Y';
            $arLoadProductArray['CODE'] = $this->translit($arLoadProductArray['NAME']);
            if($PRODUCT_ID = $el->Add($arLoadProductArray)) {
                return $PRODUCT_ID;
            } else {
                return 'Error: '.$el->LAST_ERROR;
            }
        }
    }

    public function translit ($text, $lit = 'ru', $params = []){
        $trans = Cutil::translit($text,$lit,$params);
        return $trans;
    }

}