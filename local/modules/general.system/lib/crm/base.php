<?php

namespace General\System\Crm;

use Bitrix\Main\UserFieldTable;
use General\System\Config;
use Bitrix\Main\Loader;
use Bitrix\Crm\CompanyTable;
use Bitrix\Crm\FieldMultiTable;
use Bitrix\Crm\ProductRowTable;


class Base
{
    /**
     * @param $result //результат выбора сущности где ключи это идентификаторы сущности
     * @param $entity //тип сущности например COMPANY или CONTACT
     * @return void
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    static function getFieldMulti(&$result, $entity)
    {
        if (
            empty($result)
            || empty($entity)
        )
            return;


        $items = FieldMultiTable::getList([
            'filter' => [
                'ENTITY_ID' => $entity,
                'ELEMENT_ID' => array_keys($result)
            ],
            'cache' => array(
                'ttl' => 3600,
                'cache_joins' => true,
            )
        ])->fetchAll();

        foreach ($items as $item) {
            $companyId = $item['ELEMENT_ID'];
            $type = $item['TYPE_ID'];

            $result[$companyId][$type][] = $item;
        }
    }

    /**
     * @param $fieldCode
     * @param $enumName
     * @return mixed
     */
    static function getEnumIdByName($fieldCode, $enumName)
    {

        $field = UserFieldTable::getList([
            'filter' => ['FIELD_NAME' => $fieldCode]
        ])->fetch();

        $enumList = \CUserFieldEnum::GetList(array(), array(
            "USER_FIELD_ID" => $field["ID"],
        ));

        while ($enum = $enumList->Fetch())
            if ($enumName == $enum['VALUE'])
                return $enum['ID'];


        $obEnum = new \CUserFieldEnum();
        $arAddEnum['n0'] = array(
            'XML_ID' => md5($enumName),
            'VALUE' => $enumName,
            'DEF' => 'N',
            'SORT' => 507
        );
        $obEnum->SetEnumValues($field["ID"], $arAddEnum);

        //еще раз вызываем себя для получений ID добавленного значения
        return self::getEnumIdByName($fieldCode, $enumName);
    }

    /**
     * @param $id
     * @return mixed
     */
    static function getEnumById($id)
    {
        return \CUserFieldEnum::GetList(array(), array("ID" => $id))->Fetch();
    }
}