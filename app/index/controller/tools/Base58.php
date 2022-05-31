<?php


namespace app\index\controller\tools;



class Base58
{
    const GMP = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuv";
    const BITCOIN = "123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz";
    const FLICKR = "123456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ";
    const RIPPLE = "rpshnaf39wBUDNEGHJKLM4PQRST7VWXYZ2bcdeCg65jkm8oFqi1tuvAxyz";
    const IPFS = "123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz";

    const VERSION_SIZE = 1;
    const CHECKSUM_SIZE = 4;

    /**
     * @var Base58\GmpEncoder|Base58\PhpEncoder
     */
    private $encoder;

    /**
     * @var mixed[]
     */
    private $options = [];

    public function __construct(array $options = [])
    {
        $this->options = array_merge($this->options, (array) $options);
        if (function_exists("gmp_init")) {
            $this->encoder = new Base58\GmpEncoder($this->options);
        } else {
            $this->encoder = new Base58\PhpEncoder($this->options);
        }
    }

    /**
     * Encode given data to a base58 string
     */
    public function encode(string $data): string
    {
        return $this->encoder->encode($data);
    }

    /**
     * Decode given base58 string back to data
     */
    public function decode(string $data): string
    {
        return $this->encoder->decode($data);
    }

    /**
     * Encode given integer to a base58 string
     */
    public function encodeInteger(int $data): string
    {
        return $this->encoder->encodeInteger($data);
    }

    /**
     * Decode given base58 string back to an integer
     */
    public function decodeInteger(string $data): int
    {
        return $this->encoder->decodeInteger($data);
    }

}