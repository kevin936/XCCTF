<?php


namespace app\index\controller\tools;

use think\exception\InvalidArgumentException;



class Base62
{
    /*private $values = array(
        '0','1','2','3','4','5','6','7','8','9',
        'a','b','c','d','e','f','g','h','i','j','k','l','m',
        'n','o','p','q','r','s','t','u','v','w','x','y','z',
        'A','B','C','D','E','F','G','H','I','J','K','L','M',
        'N','O','P','Q','R','S','T','U','V','W','X','Y','Z'
    );*/

    public function __construct()
    {
    }

    public static function encode($value = null)
    {
        $values = array(
            '0','1','2','3','4','5','6','7','8','9',
            'a','b','c','d','e','f','g','h','i','j','k','l','m',
            'n','o','p','q','r','s','t','u','v','w','x','y','z',
            'A','B','C','D','E','F','G','H','I','J','K','L','M',
            'N','O','P','Q','R','S','T','U','V','W','X','Y','Z'
        );
        if (is_null($value)) {
            throw new InvalidArgumentException('Value must not be blank');
        }

        $base62 = '';
        do {
            $base62 = $values[$value % 62] . $base62;
            $value /= 62;
        } while ($value >= 1);

        return $base62;
    }

    public static function decode($value = null)
    {
        $values = array(
            '0','1','2','3','4','5','6','7','8','9',
            'a','b','c','d','e','f','g','h','i','j','k','l','m',
            'n','o','p','q','r','s','t','u','v','w','x','y','z',
            'A','B','C','D','E','F','G','H','I','J','K','L','M',
            'N','O','P','Q','R','S','T','U','V','W','X','Y','Z'
        );
        if (is_null($value)) {
            throw new InvalidArgumentException('Value must not be blank');
        }

        $base10 = 0;
        $length = strlen($value);

        for ($i = 0; $i < $length; $i++) {
            $val = array_keys($values, substr($value, $i, 1));
            $base10 += $val[0] * pow(62, $length - $i - 1);
        }

        return $base10;
    }
}