<?php

namespace Custom;


class CurrencyAgent
{
    public static function load()
    {
        $xml = new \DOMDocument();
        $url = 'http://www.cbr.ru/scripts/XML_daily.asp?date_req=' . date('d.m.Y');

        if (@$xml->load($url)) {

            $root = $xml->documentElement;
            $items = $root->getElementsByTagName('Valute');

            foreach ($items as $item) {
                $code = $item->getElementsByTagName('CharCode')->item(0)->nodeValue;
                $curs = $item->getElementsByTagName('Value')->item(0)->nodeValue;

                $fields = [
                    "CODE" => $code,
                    "COURSE" => $curs,
                    "DATE_CREATE" => new \Bitrix\Main\Type\DateTime(),
                ];

                \Custom\LinkedTable::add($fields);
            }

            return true;
        }

        return false;
    }

    public static function getInfo()
    {
        self::load();
        return "Custom\CurrencyAgent::getInfo();";
    }
}