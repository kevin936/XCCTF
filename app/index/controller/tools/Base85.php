<?php


namespace app\index\controller\tools;


use app\index\controller\tools\Base85\GmpEncoder;
use app\index\controller\tools\Base85\PhpEncoder;

class Base85
{
    /* Adobe ASCII85. Only all zero data exception, ignore whitespace. */
    /* https://www.adobe.com/products/postscript/pdfs/PLRM.pdf */
    const ASCII85 = "!\"#$%&'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstu";

    /* https://rfc.zeromq.org/spec:32/Z85/ */
    const Z85 = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ.-:+=^!/*?&<>()[]{}@%$#";

    /* https://github.com/tuupola/base85/issues/22 */
    const Z85BUG = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz.-:+=^!/*?&<>()[]{}@%$#";

    /* https://tools.ietf.org/html/rfc1924 which is an Aprils fools joke. */
    const RFC1924 = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz!#$%&()*+-;<=>?@^_`{|}~";

    /**
     * @var array
     */
    private $options = [];

    /**
     * @var Base85\GmpEncoder|Base85\PhpEncoder
     */
    private $encoder;

    public function __construct(array $options = [])
    {
        $this->options = array_merge($this->options, (array) $options);
        if (function_exists("gmp_init")) {
            $this->encoder = new GmpEncoder($this->options);


        } else {
            $this->encoder = new PhpEncoder($this->options);
        }
    }

    /**
     * Encode given data to a base85 string
     */
    public function encode(string $data): string
    {
        return $this->encoder->encode($data);
    }

    /**
     * Decode given a base85 string back to data
     */
    public function decode(string $data): string
    {
        return $this->encoder->decode($data);
    }

    /**
     * Encode given integer to a base85 string
     */
    public function encodeInteger(int $data): string
    {
        return $this->encoder->encodeInteger($data);
    }
    /**
     * Decode given base85 string back to an integer
     */
    public function decodeInteger(string $data): int
    {
        return $this->encoder->decodeInteger($data);
    }

}