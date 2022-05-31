<?php

/**
 ******星辰信安*******
 * @author: Kevin
 * @email: root_1024@163.com
 * @time: 2022-03-25 11:45:35
 */

namespace app\index\controller;


use app\BaseController;
use app\index\controller\tools\Base85;
use app\index\controller\tools\XXencode;
use app\Request;
use app\index\controller\tools\Base32 as Base32fun;
use app\index\controller\tools\Base100 as Base100fun;
use app\index\controller\tools\Base92 as Base92fun;
use app\index\controller\tools\Base85Proxy;
use app\index\controller\tools\Base62 as Base62fun;
use app\index\controller\tools\Base58Proxy;
use app\index\controller\tools\Base91 as Base91fun;


class Tools extends BaseController
{
    /****/
    public function index()
    {

        return view('index');
    }

    /**base64解密页渲染**/
    public function base64()
    {
        return view('base64');
    }


    public function base64encode(Request $request)
    {

        $data = $request->param();
        $encode = base64_encode($data['content']);
        $msg['content']=$encode;
        return json($msg);
    }

    public function base64decode(Request $request)
    {
        $data = $request->param();
        $decode = base64_decode($data['content']);
        $msg['content']=$decode;
        return json($msg);
    }

    /**UUencode解密页面渲染**/
    public function uuencode()
    {
        return view('uuencode');
    }

    /**UUencode解密**/
    public function uencode(Request $request)
    {
        $data = $request->param()['content'];
        $result = convert_uuencode($data);
        $msg['content']=$result;
        return json($msg);

    }

    /**UUdecode解密**/
    public function udecode(Request $request)
    {
        $data = $request->param()['content'];
        $result = convert_uudecode($data);
        $msg['content']=$result;
        return json($msg);

    }

    /**与佛禅论页面渲染**/
    public function todousharp()
    {
        return view('todousharp');
    }

    /**与佛禅论加密**/
    public function sharpencode(Request $request)
    {
        $data =$request->param();

    }
    /**与佛禅论解密**/
    public function sharpdecode(Request $request)
    {
        $data=$request->param();
    }


    /**XXencode页面渲染**/
    public function xxencode()
    {
        return view('xxencode');
    }


    /**维吉尼亚加密页面渲染**/
    public function vigenere()
    {
        return view('vigenere');
    }

    /**希尔密码**/
    public function hill()
    {
        return view('hill');
    }

    /**栅栏密码**/
    public function railfence()
    {
        return view('railfence');
    }

    /**凯撒密码**/
    public function caesar()
    {
        return view('caesar');
    }

    /**TripleDes(3DES)**/
    public function tripledes()
    {
        return view('tripledes');
    }

    /**Base100**/
    public function base100()
    {
        return view('base100');
    }
    /**base100加密*/
    public function base100en(Request $request)
    {
        $data = $request->param()['content'];
        $result = Base100fun::encode($data);
        $msg['content']=$result;
        return json($msg);
    }
    /**base100解密*/
    public function base100de(Request $request)
    {
        $data = $request->param()['content'];
        $result = Base100fun::decode($data);
        $msg['content']=$result;
        return json($msg);

    }


    /**ADFGX密码**/
    public function adfgx()
    {
        return view('adfgx');
    }

    /**base92**/
    public function base92()
    {
        return view('base92');
    }
    /**base92加密*/
    public function base92en(Request $request)
    {
        $data = $request->param()['content'];
        $result = Base92fun::encode($data);
        $msg['content']=$result;
        return json($msg);

    }
    /**base92解密*/
    public function base92de(Request $request)
    {
        $data = $request->param()['content'];
        $result = Base92fun::decode($data);
        $msg['content']=$result;
        return json($msg);
    }


    /**base91**/
    public function base91()
    {
        return view('base91');
    }
    /**base91加密*/
    public function base91en(Request $request)
    {
        $data = $request->param()['content'];
        $result = Base91fun::base91_encode($data);
        $msg['content']=$result;
        return json($msg);

    }
    /**base91解密*/
    public function base91de(Request $request)
    {
        $data = $request->param()['content'];
        $result = Base91fun::base91_decode($data);
        $msg['content']=$result;
        return json($msg);
    }


    /**base85*/
    public function base85()
    {
        return view('base85');
    }
    /**base85加密*/
    public function base85en(Request $request)
    {
        $data = $request->param()['content'];
        $result = Base85Proxy::encode($data);
        $msg['content']=$result;
        return json($msg);
    }
    /**base85解密*/
    public function base85de(Request $request)
    {
        $data = $request->param()['content'];
        $result = Base85Proxy::decode($data);
        //dd($result);
        $msg['content'] = $result;
        return json($msg);

    }


    /**base62**/
    public function base62()
    {
        return view('base62');
    }
    /**base62加密*/
    public function base62en(Request $request)
    {
        $data = $request->param()['content'];
        $result = Base62fun::encode($data);
        $msg['content']=$result;
        return json($msg);


    }
    /**base62解密*/
    public function base62de(Request $request)
    {
        $data = $request->param()['content'];
        $result = Base62fun::decode($data);
        $msg['content']=$result;
        return json($msg);
    }


    /**base58*/
    public function base58()
    {
        return view('base58');
    }
    /**base58加密*/
    public function base58en(Request $request)
    {
        $data = $request->param()['content'];
        $result = Base58Proxy::encode($data);
        $msg['content']=$result;
        return json($msg);

    }
    /**base58解密*/
    public function base58de(Request $request)
    {
        $data = $request->param()['content'];
        $result = Base58Proxy::decode($data);
        $msg['content']=$result;
        return json($msg);
    }




    /**base32*/
    public function base32()
    {
        return view('base32');
    }
    /**base32加密*/
    public function base32en(Request $request)
    {
        $data = $request->param()['content'];
        $result = Base32fun::encode($data);
        $msg['content']=$result;
        return json($msg);

    }
    /**base32解密*/
    public function base32de(Request $request)
    {
        $data = $request->param()['content'];
        $result = Base32fun::decode($data);
        $msg['content']=$result;
        return json($msg);
    }


    /**base16*/
    public function base16()
    {
        return view('base16');
    }
    /**base16加密*/
    public function base16en(Request $request)
    {
        //$key = $request->param();
        $data = $request->param()['content'];
        $result = "";
        $BASE_16_CHARS = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "A", "B", "C", "D", "E", "F");
        for ($i = 0; $i < strlen($data); $i++) {
            $result .= $BASE_16_CHARS[(@ord($data[$i]) & 0xf0) >> 4];
            $result .= $BASE_16_CHARS[@ord($data[$i]) & 0x0f];
        }
        $msg['content']=$result;
        return json($msg);

    }
    /**base16解密*/
    public function base16de(Request $request)
    {
        //$key = $request->param();
        $data = $request->param()['content'];
        $result = "";
        $len = strlen($data) / 2;
        for ($i = 0; $i < $len; $i++) {
            $result .= chr(intval(substr($data, $i * 2, 2), 16));
        }
        $msg['content']=$result;
        return json($msg);

    }


    /**cvecode*/
    public function cvecode()
    {
        return view('cvecode');
    }

    /**brainfuck*/
    public function brainfuck()
    {

        return view('brainfuck');
    }
    /**brainfuck处理*/
    public function brainfuckende(Request $request)
    {
        include 'tools\Brainfuck.php';
        include 'tools\Util.php';

        $data = $request->param();
        $content = $data['content'];
        $output ='';
        switch ($data['do']) {
            case 'text2Ook':
            case 'text2sOok':
                $output = fuck_text($content);
                $output = strtr($output, array('>' => 'Ook. Ook? ',
                    '<' => 'Ook? Ook. ',
                    '+' => 'Ook. Ook. ',
                    '-' => 'Ook! Ook! ',
                    '.' => 'Ook! Ook. ',
                    ',' => 'Ook. Ook! ',
                    '[' => 'Ook! Ook? ',
                    ']' => 'Ook? Ook! ',
                ));
                if ($_REQUEST['do'] == 'Text to short Ook!') {
                    $output = str_replace('Ook', '', $output);
                    $output = str_replace(' ', '', $output);
                    $output = preg_replace('/(.....)/', '\\1 ', $output);
                }
                $output = wordwrap($output, 75, "\n");
                $msg['content']=$output;
                break;
            case 'text2b':
                $output = fuck_text($content);
                $output = preg_replace('/(.....)/', '\\1 ', $output);
                $output = wordwrap($output, 75, "\n");
                $msg['content']=$output;
                break;
            case 'Ook2text':
                $lookup = array(
                    '.?' => '>',
                    '?.' => '<',
                    '..' => '+',
                    '!!' => '-',
                    '!.' => '.',
                    '.!' => ',',
                    '!?' => '[',
                    '?!' => ']',
                );

                $input = preg_replace('/[^\.?!]+/', '', $content);
                $len = strlen($input);
                for ($i = 0; $i < $len; $i += 2) {
                    $output .= $lookup[$input[$i] . $input[$i + 1]];
                }
                $output = brainfuck($output);
                $msg['content']=$output;
                break;
            case 'b2text':
                $output = brainfuck($content);
                $msg['content']=$output;
                break;
        }
        return json($msg);
    }

    /**sojson5*/
    public function sojson5()
    {
        return view('sojson5');
    }

    /**sojson4**/
    public function sojson4()
    {
        return view('sojson4');
    }








}