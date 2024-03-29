<?php






declare(strict_types = 1);

/*
Copyright (c) 2017-2021 Mika Tuupola
Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:
The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/

/**
 * @see       https://github.com/tuupola/base85
 * @license   https://opensource.org/licenses/mit-license.php
 */

namespace app\index\controller\tools;

use app\index\controller\tools\Base85 as Base85z;

class Base85Proxy
{
    /**
     * @var array
     */
    public static $options = [
        "characters" => Base85z::ASCII85,
        "compress.spaces" => false,
        "compress.zeroes" => true,
        "prefix" => null,
        "suffix" => null,
    ];

    /**
     * Encode given data to a base85 string
     */
    public static function encode(string $data): string
    {
        return (new Base85z(self::$options))->encode($data);
    }

    /**
     * Decode given a base85 string back to data
     */
    public static function decode(string $data): string
    {
        return (new Base85z(self::$options))->decode($data);
    }

    /**
     * Encode given integer to a base85 string
     */
    public static function encodeInteger(int $data): string
    {
        return (new Base85z(self::$options))->encodeInteger($data);
    }

    /**
     * Decode given base85 string back to an integer
     */
    public static function decodeInteger(string $data): int
    {
        return (new Base85z(self::$options))->decodeInteger($data);
    }
}