<?php

namespace AppBundle\Enum;

abstract class BankPaymentTypeEnum
{
    const TYPE_TRANSFERT = "transfert";
    const TYPE_CHECK = "check";


    /** @var array user friendly named type */
    protected static $typeName = [
        self::TYPE_TRANSFERT    => 'Virement',
        self::TYPE_CHECK => 'Chèque',
    ];

    /**
     * @param  string $typeShortName
     * @return string
     */
    public static function getTypeName($typeShortName)
    {
        if (!isset(static::$typeName[$typeShortName])) {
            return "Unknown type ($typeShortName)";
        }

        return static::$typeName[$typeShortName];
    }

    /**
     * @return array<string>
     */
    public static function getAvailableTypes()
    {
        return [
            self::TYPE_TRANSFERT,
            self::TYPE_CHECK,
        ];
    }
}
