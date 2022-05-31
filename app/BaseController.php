<?php
declare (strict_types = 1);

namespace app;


use app\common\model\Config;
use app\index\model\Message;
use app\index\model\Navmenu;
use app\index\model\Users as UsersModel;
use think\App;
use think\exception\ValidateException;
use think\facade\View;
use think\Validate;

/**
 * 控制器基础类
 */
abstract class BaseController
{
    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;

        // 控制器初始化
        $this->initialize();
        $this->base();
    }

    // 初始化
    protected function initialize()
    {

        if(session('users')){
            $userinfo = UsersModel::where("username",session('users'))->select();
            return $userinfo;
        }


    }
    public function base()
    {
        $name = Config::field('value')->where('name','name')->find();
        $beian = Config::field('value')->where('name','beian')->find();
        $zblog = Config::field('value')->where('name','zblog_status')->find();
        $logo   = Config::field('value')->where('name','logo_img')->find();
        $navmenu = Navmenu::select();
        $navtow  = Navmenu::where('tid','<>',0)->select();
        $logo_icon  = Config::field('value')->where('name','logo_icon')->find();
        $qr_code  = Config::field('value')->where('name','qr_code')->find();


        if(session('uid')) {
            $message = Message::where(['uid'=>session('uid'),'status'=>0])->select();

            if($beian == null)
            {
                $beian = '';
            }
            return View::assign([
                'message'  => $message,
                'count'    => count($message),
                'name'     => $name,
                'beian'    => $beian,
                'zblog'    => $zblog,
                'logo'     => $logo,
                'nav'      =>  $navmenu,
                'navtow'   => $navtow,
                'logo_icon'   => $logo_icon,
                'qr_code'   =>$qr_code


            ]);
        }else{
            return View::assign([
                'message'  => '',
                'count' => 0,
                'name'  => $name,
                'beian' => $beian,
                'zblog' => $zblog,
                'logo'  => $logo,
                'nav'   => $navmenu,
                'navtow' => $navtow,
                'logo_icon'   => $logo_icon,
                'qr_code'   =>$qr_code
            ]);
        }
    }



    /**
     * 验证数据
     * @access protected
     * @param  array        $data     数据
     * @param  string|array $validate 验证器名或者验证规则数组
     * @param  array        $message  提示信息
     * @param  bool         $batch    是否批量验证
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                [$validate, $scene] = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v     = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
    }



}
