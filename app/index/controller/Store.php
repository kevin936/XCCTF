<?php


namespace app\index\controller;


use app\BaseController;
use app\index\model\Imgframe;
use app\index\model\Order;
use app\Request;
use think\exception\ValidateException;
use think\facade\View;
use app\index\model\Users as UsersModel;
use app\index\model\Store as StoreModel;

class Store extends BaseController
{
    /**积分商城首页方法**/
    public function index()
    {

        /*$da =Order::with('users')->paginate([
            'list_rows' => 10,
            'query' => request()->param()
        ]);
        dd($da);*/
        return view('index',[
            'info'  =>  UsersModel::where('id',session('uid'))->find(),
            'storelist' => StoreModel::where("stock >0")->order('create_time','desc')->paginate([
                    'list_rows' => 20,
                    'query' => request()->param()
                ]),
            'orderlist' => Order::with('users')->order('create_time','desc')->paginate([
                    'list_rows' => 10,
                    'query' => request()->param()
                ])
        ]);
    }

    /**积分商城商品详情页方法**/
    public function detail(Request $request)
    {

        $data = $request->param();  //获取前端传来的参数和值
        $tag = StoreModel::where('id',$data['id'])->find();  //获取商品信息，主要用于取里面的tip标签
        return view('detail',[
           'info'   => StoreModel::where('id',$data['id'])->find(),
            'tip'   => explode(',',$tag['tip'])
        ]);
    }

    /**创建订单页面方法**/
    public function create(Request $request)
    {
        $data = $request->param();
        $info = StoreModel::where('id',$data['id'])->find();   //查询相同商品id的订单用于统计数量
        if($info['stock'] >$data['num'] && $data['num'] <= $info['quota']){
            $id = date('Ymd') . str_pad(mt_rand(1, 9999999999), 5, '0', STR_PAD_LEFT);
            return view('create',[
                'info'=>StoreModel::where('id',$data['id'])->find(),
                'id'  =>$id,
            ]);
        }else{
            abort(404, '未找到资源');
        }


    }

    /**创建订单请求方法**/
    public function creorder(Request $request)
    {
        $data = $request->param();
        /**传过来的参数样式
         *  "id" => "1"
         *  "num" => "8"
         *  "shp_id" => "202203192135"
         *  "receive_name" => "张三"
         *  "receive_tel" => "18720742568"
         *  "province" => "江西省"
         *  "city" => "赣州市"
         *  "area" => "赣县区"
         *  "addr" => "王母渡镇"
         *  "remarks" => "发顺丰"
        **/
        $userinfo = UsersModel::where('id',session('uid'))->find();
        $info = StoreModel::where('id',$data['id'])->find();
        $zongjia = $info['score']*$data['num'];

        try {
            validate(Rgevalidate::class)->batch(true)->check($request->param());

            if($userinfo['score']>=$zongjia )
            {
                $order = new Order;
                $order->shp_id   = $data['shp_id'];
                $order->uid      = session('uid');
                $order->shp_name = $info['name'];
                $order->shp_img = $info['img'];
                $order->shp_score = $info['score'];
                $order->shp_unit  = $data['num'];
                $order->shp_num = $zongjia;
                $order->name = $data['receive_name'];
                $order->phone = $data['receive_tel'];
                $order->address = $data['province'].$data['city'].$data['area'].$data['addr'];
                $order->comment = $data['remarks'];
                if($info['type']==0)
                {
                    $order->status = 4;
                    Imgframe::create(['uid'=>session('uid'),'frame_name'=>$info['name'],'frame_img'=>$info['img'],'create_time'=>date('Y-m-d H:i:s')]);

                }else{
                    $order->status = 1;
                }
                $order->create_time = date('Y-m-d H:i:s');
                $order->save();

                UsersModel::where('id',session('uid'))->dec('score',$zongjia)->update();
                StoreModel::where('id',$data['id'])->dec('stock',$data['num'])->update();

                $msg['code']=1;
                $msg['url']='/store/order/id/'.$data['shp_id'];
                $msg['msg']='兑换成功！';
                return json($msg);
            }else{
                $msg['code']=0;
                $msg['msg']='抱歉，您的积分不足~';
                return json($msg);
            }

        }catch (ValidateException $exception){
            $msg['state']=3;
            $error = $exception->getError();
            $msg['code']=0;
            $msg['msg']=$error['__token__'];
            return json($msg);
        }


    }

    public function order(Request $request)
    {
        $data = $request->param();

        $check = Order::where('shp_id',$data['id'])->find();
        if($check['uid']==session('uid'))
        {
            return view('order',[
                'info' => Order::where('shp_id',$data['id'])->find()
            ]);
        }else{
            $msg['url']='/';
            $msg['msg']='无权查看他人订单';
            return view('/public/skip',[
                'msg'   => $msg,
            ]);
        }
    }

    public function shouhuo(Request $request)
    {
        $data = $request->param();

        Order::update(['status'=>4],['shp_id'=>$data['id']]);
        //$msg['code']=1;
        $msg['msg']='签收成功~';
        return json($msg);
    }


}