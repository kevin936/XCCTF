<?php
declare (strict_types = 1);

namespace app\index\validate;

use think\Validate;

class Rigister extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'username'          =>  'require|min:2|max:10|chsDash|unique:user',
        '__token__'         =>  'require|token',
        'password'          =>  'require|min:6',
        'passwordnot'       =>  'require|confirm:password',
        'email'             =>  'require|email|unique:user',
        'phone'             =>  'require|min:11',
        //'agree'             =>  'require|accepted',


        //'newpasswordnot'    =>  'min:6|requireWith:newpassword|confirm:newpassword'

    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [
        'username.require'      => '用户名不得为空~',
        'username.min'          => '用户名不得小于 2 位~',
        'username.max'          => '用户名不得大于 10 位~',
        'username.chsDash'      => '用户名只能是汉字、字母、数字或下划线以及破折号~',
        'username.unique'       => '用户名已存在~',
        'password.require'      => '密码不得为空~',
        'password.min'          => '密码不得小于 6 位~',
        'passwordnot.require'   => '密码确认不得为空~',
        'passwordnot.confirm'   => '密码确认和密码不一致~',
        'email.require'         => '电子邮件不得为空~',
        'email.email'           => '电子邮件格式不正确~',
        'email.unique'          => '电子邮件已存在~',
        'agree.require'         => '必须确认协议~',
        'agree.accepted'        => '必须认同协议~',
        '__token__.require'     => '令牌不得为空~',

        'newpasswordnot.min'            => '新密码不得小于 6 位~',
        'newpasswordnot.requireWith'    => '新密码不得为空~',
        'newpasswordnot.confirm'        => '新密码确认不一致~',
        'phone.require'         =>'请输入联系电话~',
        'phone.min'           =>'联系电话格式不正确~'
    ];

}
