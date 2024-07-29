<?php


namespace General\System\Crm;

use Bitrix\Crm\LeadTable;
use Bitrix\Main\Loader;
use General\System\Crm\Base as CrmBase;
use Bitrix\Crm\ProductRowTable;


class Lead
{

    function __construct()
    {
        if (!Loader::includeModule('crm')) {
            echo 'can not include module crm in ' . __FILE__ . ':' . __LINE__;
            die();
        }
    }

    /**
     *  Получить список lead
     * @param array $filter
     * @param string[] $select
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    function getList($filter = [], $select = ['*']): array
    {
        $result = [];

        $leadList = LeadTable::getList([
            'order' => ['ID' => 'ASC'],
            'filter' => $filter,
            'select' => $select,
            'cache' => array(
                'ttl' => 3600,
                'cache_joins' => true,
            )
        ])->fetchAll();

        foreach ($leadList as $lead) {
            $result[$lead['ID']] = $lead;
        }

        //получаем все мультиполя типа Телефоны и Емейлы
        CrmBase::getFieldMulti($result, 'LEAD');

        return $result;
    }

    /**
     * Получить lead по ID
     * @param $ID
     * @param array $SELECT
     * @return mixed
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getByID($ID, $SELECT=[])
    {
        array_unshift($SELECT, '*');
        $lead = $this->getList(['ID' => $ID], $SELECT);
        return $lead[$ID];
    }

    /**
     * Получить товары lead
     * @param $LEAD_ID
     * @return array
     */
    public function getProducts($LEAD_ID): array
    {
        $productList = ProductRowTable::getList([
            'filter' => [
                '=OWNER_ID' => $LEAD_ID
            ],
        ]);
        $products = [];
        while ($product = $productList->fetch())
        {
            $products[$product['ID']] = $product;
        }
        return $products;
    }

}