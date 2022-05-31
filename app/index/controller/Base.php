<?php


namespace app\index\controller;
use app\index\model\Users as UsersModel;

class Base
{
    public function _initialize()
    {
        if(session('users')){
            $userinfo = UsersModel::where("username",session('users'))->select();
            return $userinfo;
        }
    }

}