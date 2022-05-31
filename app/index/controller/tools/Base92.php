<?php


namespace app\index\controller\tools;


use think\Exception;

class Base92
{
     private static function base92_chr($val)
    {
        if ($val < 0 || $val >= 91)
            throw new Exception("Error:CHR");
        if ($val == 0) {
            return '!';
        } else if ($val <= 61) {
            return chr(ord('#') + $val - 1);
        } else {
            return chr(ord('a') + $val - 62);
        }
    }

     private static function base92_ord($val)
    {
        $num = ord($val);
        if ($val == '!') {
            return 0;
        } else if (ord('#') <= $num && $num <= ord('_')) {
            return $num - ord('#') + 1;
        } else if (ord('a') <= $num && $num <= ord('}')) {
            return $num - ord('a') + 62;
        } else {
            throw new Exception('This`s not Base92');
        }
    }


     public static function encode($content)
    {
        if ($content == "") return '~';
        $bin = '';
        while (strlen($bin) < 13 && $content) {
            $tmp = decbin(ord($content[0]));
            if (strlen($tmp) < 8) {
                $tmp= str_pad($tmp, 8, "0",STR_PAD_LEFT);
            } else if (strlen($tmp) > 8) {
                throw new Exception('Error: Base92 encode');
            }
            $bin .= $tmp;
            $content = substr($content, 1);
        }
        $result = '';
        while (strlen($bin) > 13 || $content) {
            $i = bindec(substr($bin, 0, 13));
            $result .= Base92::base92_chr($i / 91);
            $result .= Base92::base92_chr($i % 91);
            $bin = substr($bin, 13);
            while (strlen($bin) < 13 && $content) {
                $tmp = decbin(ord($content[0]));
                if (strlen($tmp) < 8) {
                    $tmp = str_pad($tmp , 8 , "0",STR_PAD_LEFT);;
                } else if (strlen($tmp) > 8) {
                    throw new Exception('Error: Base92 encode');
                }
                $bin .= $tmp;
                $content = substr($content, 1);
            }
        }

        if ($bin) {
            if (strlen($bin) < 7) {
                $bin =  str_pad($bin, 6 , "0");
                $result .= Base92::base92_chr(bindec($bin));
            } else {
                $bin = str_pad($bin , 13 , "0");
                $i = bindec($bin);
                $result .= Base92::base92_chr(($i / 91));
                $result .= Base92::base92_chr($i % 91);
            }
        }
        return $result;
    }

    public static function decode($content)
    {
        $bin = '';
        $result = '';
        if ($content == '~') {
            return '';
        }
        $length = intval(strlen($content) / 2);
        for ($i = 0; $i < $length; $i++) {
            $j = Base92::base92_ord($content[2 * $i]) * 91 + Base92::base92_ord($content[2 * $i + 1]);

            $tmp = decbin($j);
            if (strlen($tmp) < 13) {
                $tmp =str_pad($tmp , 13 , "0",STR_PAD_LEFT);
            } else if (strlen($tmp) > 13) {
                throw new Exception('Error: Base92 decode');
            }
            $bin .= $tmp;
            while (8 <= strlen($bin)) {
                $result .= chr(bindec(substr($bin, 0, 8)));
                $bin = substr($bin, 8);
            }
        }
        if (strlen($content) % 2 == 1) {
            $j = Base92::base92_ord(substr($content, strlen($content) - 1));
            $tmp = decbin($j);
            if (strlen($tmp) < 6) {
                $tmp = str_pad($tmp , 6 , "0",STR_PAD_LEFT);
            } else if (strlen($tmp) > 6) {
                throw new Exception('Error:Base92 decode');
            }
            $bin .= $tmp;
            while (8 <= strlen($bin)) {
                $result .= chr(bindec(substr($bin, 0, 8)));
                $bin = substr($bin, 8);
            }

        }
        return $result;

    }

}