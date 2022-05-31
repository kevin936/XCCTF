<?php


namespace app\index\controller;
use app\BaseController;
use app\index\model\Users as UsersModel;
use app\Request;
use think\facade\Validate;
use think\facade\Session;

class Login extends BaseController
{
    /**登录页**/
    public function index()
    {

        /**渲染login.html页面**/
        return view('login');
    }

    public function checklogin(Request $request)
    {
        $data = $request->param();  //使用data变量来接收前端传过来的参数和值
        $msg['state']=0;            //定义msg字段，key为state值为0

        /**
         * 先匹配用户名和密码是否能够查询到对应数据，
         * 然后将查询的结果赋值给result变量。不存在返回的是null
         **/
        $result = UsersModel::where([
            'username'  => $data['username'],
            'password'  => md5($data['password'])
        ])->find();

        /**验证token**/


        /**判断验证码是否正确**/
        if(!captcha_check($data['code']))
        {
            $msg['state'] = 2;
            $msg['msg'] = '验证码不正确！';
            return json($msg);
        }

        if($result) {
            session('uid',$result['id']);
            session('users',$result['username']);
            session('img',$result['img']);
            if($request['team_id']!=null)
            {
                session('team_id',$result['team_id']);
            }else {
                session('team_id','');
            }
            $info = UsersModel::where('id',session('uid'))->find();
            UsersModel::update(['prev_time'=>$info['login_time']],['id'=>session('uid')]);
            UsersModel::update(['login_time'=>date("Y-m-d H:i:s")],['id'=>session('uid')]);
            $msg['state']=1;
            $msg['msg'] = '登入成功！';
            return json($msg);
        }else{
            $msg['state']=0;
            $msg['msg'] = '用户名或密码错误~';
            return json($msg);
        }

        $errors = [];
        $validate = Validate::rule([
            'name|用户名'  => 'unique:users，username^password'
        ]);
        $result = $validate->batch(true)->check([
            'username'  => $data['username'],
            'password'  => md5($data['password'])
        ]);

        if(!$result){
            dd($validate->getError());
        }

    }
}