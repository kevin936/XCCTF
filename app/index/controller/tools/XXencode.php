<?php


namespace app\index\controller\tools;


class XXencode
{
    public static function encode($src)
    {
        //64个可打印字符
        static $base="+-0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        ///每次读取3个字节
        $lbyte = 3;
        ////将原始的3个字节转换为4个字节
        $slen=strlen($src);
        $smod = ($slen%$lbyte);
        $snum = floor($slen/$lbyte);


        $desc = array();

        //将剩下字节以0字节补齐
        $src = $smod===0?$src:$src.str_repeat("\0",$lbyte-$smod);
        $snum = $smod===0?$snum:$snum+1;

        for($i=0;$i<$snum;$i++)
        {
            ////读取3个字节
            $_arr = array_map('ord',str_split(substr($src,$i*$lbyte,$lbyte)));

            ///计算每一个6位值
            $_dec = array();
            $_dec[]=$_arr[0]>>2;
            $_dec[]=(($_arr[0]&3)<<4)|($_arr[1]>>4);
            $_dec[]=(($_arr[1]&0xF)<<2)|($_arr[2]>>6);
            $_dec[]=$_arr[2]&63;

            ///求每一位值，在64字符中所对应的字符
            foreach ($_dec as &$v)
            {
                $v=$base[$v];
            }
            $desc = array_merge($desc,$_dec);
        }


        //每60个编码输出（相当于45个输入字节）将输出为独立的一行，每行的开头会加上长度字符，除了最后一行之外，长度字符都应该是'h'这个ASCII字符（45），最后一行的长度字符为剩下的字节数目,在64字符中对应字符。
        $abyte = 60;
        $crlf = "\r\n";
        $alen = count($desc);
        $anum = floor($alen/$abyte);
        $amod = ($alen%$abyte);

        $adesc = array();

        for ($i=0;$i<$anum;$i++)
        {
            $adesc[]='h'.implode('',array_slice($desc,$i*$abyte,$abyte)).$crlf;
        }

        ///截取后面剩余数组长度
        if($amod!==0)
        {
            ///以下计算不满45字节编码情况
            $adesc[]=$base[$amod/4*$lbyte+($smod?$smod-$lbyte:$smod)].implode('',array_slice($desc,-$amod)).$crlf;
        }

        return implode('',$adesc);
    }
}