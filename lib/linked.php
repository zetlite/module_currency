<?php
namespace Custom;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type;

Loc::loadMessages(__FILE__);

class LinkedTable extends Entity\DataManager
{

    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'custom_currency';
    }

    /**
     * @return array
     */
    public static function getMap()
    {
        return array(
            'ID' => array(
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true,
            ),
            'DATE_CREATE' => array(
                'data_type' => 'datetime'
            ),
            'CODE' => array(
                'data_type' => 'string',
            ),
            'COURSE' => array(
                'data_type' => 'float',
            ),
        );
    }

}
