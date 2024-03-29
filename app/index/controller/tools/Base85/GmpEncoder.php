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

namespace app\index\controller\tools\Base85;

class GmpEncoder extends BaseEncoder
{
    /**
     * Encode given data to a base85 string
     */
    public function encode(string $data): string
    {
        $powers = [
            gmp_init("52200625", 10),
            gmp_init("614125", 10),
            gmp_init("7225", 10),
            gmp_init("85", 10),
            gmp_init("1", 10),
        ];

        $padding = 0;
        if ($modulus = strlen($data) % 4) {
            $padding = 4 - $modulus;
            $data .= str_repeat("\0", $padding);
        }

        $converted = [$this->options["prefix"]];
        foreach ((array)unpack("N*", $data) as $uint32) {
            /* Four spaces exception. */
            if ($this->options["compress.spaces"]) {
                if (0x20202020 === $uint32) {
                    $converted[] = "y";
                    continue;
                }
            }

            /* All zero data exception. */
            if ($this->options["compress.zeroes"]) {
                if (0x00000000 === $uint32) {
                    $converted[] = "z";
                    continue;
                }
            }

            $digits = "";
            $quotient = gmp_init($uint32, 10);

            foreach ($powers as $pow) {
                list($quotient, $reminder) = gmp_div_qr($quotient, $pow);
                $quotient = gmp_intval($quotient);
                $digits .= $this->options["characters"][$quotient];
                $quotient = $reminder;
            }

            $converted[] = $digits;
        }

        $last = count($converted) - 1;

        /* The z exception does not apply to the last block. */
        if ($this->options["compress.zeroes"] && "z" === $converted[$last]) {
            $converted[$last] = "!!!!!";
        }

        /* Remove any padding from the returned result. */
        if ($padding) {
            $converted[$last] = substr($converted[$last], 0, 5 - $padding);
        }

        $converted[] = $this->options["suffix"];

        return implode($converted);
    }
}

