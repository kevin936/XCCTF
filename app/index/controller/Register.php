<?php


namespace app\index\controller;


use app\BaseController;
use app\index\model\Users as UsersModel;
use app\Request;
use app\index\validate\Rigister as Rgevalidate;
use think\exception\ValidateException;
use app\index\validate\Rigister;

class Register extends BaseController
{
    public function index()
    {
        return view('register');
    }
    public function createname(Request $request){

        $data = $request->param();
        //$token = token('__token__');
        //dd($token);
        $msg['state']=0;
        $res = UsersModel::where('username',$data['username'])->find();

        try {
            validate(Rigister::class)->batch(true)->check($request->param());

            if(captcha_check($data['code']))
            {
                if(!$res)
                {

                    $result = UsersModel::create([
                        'username'  => $data['username'],
                        'password'  => md5($data['password']),
                        'email'     => $data['email'],
                        'phone'     => $data['phone']

                    ]);

                }else{
                    $msg['msg']='用户已存在~';
                    return json($msg)->header(['__token__'=>token('__token__')]);
                }

                if($result){
                    $msg['state']=1;
                    $msg['msg']='注册成功!';
                    return json($msg)->header(['__token__'=>token('__token__')]);
                }
            }else{
                $msg['state'] =2;
                $msg['msg']='验证码错误~';
                return json($msg)->header(['__token__'=>token('__token__')]);
            }
        }catch (ValidateException $exception){
            $msg['state']=3;
            $error = $exception->getError();
            $msg['msg']=$error;
            return json($msg)->header(['__token__'=>token('__token__')]);
        }

    }

    public function checkusername(Request $request)
    {
        $data = $request->param();
        $msg = [];
        $result = UsersModel::where('username',$data['username'])->find();
        if($result){
            $msg['msg'] = '用户名已存在！';
            return json($msg);
        }
    }

}