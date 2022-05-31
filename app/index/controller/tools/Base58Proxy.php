<?php


namespace app\index\controller\tools;

use app\index\controller\tools;

class Base58Proxy
{
    /**
     * @var mixed[]
     */
    public static $options = [
        "characters" => Base58::GMP,
        "check" => false,
        "version" => 0x00,
    ];

    /**
     * Encode given data to a base58 string
     */
    public static function encode(string $data): string
    {
        return (new Base58(self::$options))->encode($data);
    }

    /**
     * Decode given base58 string back to data
     */
    public static function decode(string $data): string
    {
        return (new Base58(self::$options))->decode($data);
    }

    /**
     * Encode given integer to a base58 string
     */
    public static function encodeInteger(int $data): string
    {
        return (new Base58(self::$options))->encodeInteger($data);
    }

    /**
     * Decode given base58 string back to an integer
     */
    public static function decodeInteger(string $data): int
    {
        return (new Base58(self::$options))->decodeInteger($data);
    }

}