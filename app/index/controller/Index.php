<?php
namespace app\index\controller;

use app\admin\controller;
use app\BaseController;
use app\common\model\Config;
use app\index\model\Action;
use app\index\model\Answer;
use app\index\model\Article as ArticleModel;
use app\index\model\Banner;
use app\index\model\Comment;
use app\index\model\Message;
use app\index\model\Teams as TeamsModel;
use app\index\model\Teamusers;
use app\index\model\Users as UserModel;
use app\index\model\Users as UsersModel;
use app\Request;


class Index extends BaseController
{
    public function index()
    {
        $webstatus = Config::field('value')->where('name','web_status')->find();
        if($webstatus['value']==1){
        return view('index',[
            'banner' => Banner::select(),
            'gonggao' => Action::where('action_type',0)->select(),
            'usertop10' => UsersModel::order('score','desc')->limit(10)->select(),
            'teamtop10' => TeamsModel::order('team_score','desc')->limit(10)->select(),
            'newslist' =>   ArticleModel::order('create_time','desc')->with('Types')->limit(7)->select(),
            'commentlist'   =>  Comment::order('create_time','desc')->limit(8)->with(['users','cids'])->select()

        ]);
        }else{
            return '网站已关闭';
        }
    }


    public function noticedetail(Request $request)
    {
        $data = $request->param();
        $uid =Action::where('id',$data['id'])->find();
        $name = UsersModel::where('id',$uid['uid'])->find();
        Action::where('id',$data['id'])->inc('action_count',1)->update();

        return view('noticedetail',[
            'ontice' => Action::where('id',$data['id'])->find(),
            'name' => $name
        ]);


    }

    public function teams()
    {
        return view('teams',[
            'list'  =>  TeamsModel::withSearch('team_name',[
                'team_name' => request()->param('teamname')
            ])->paginate([
                'list_rows' => 20,
                'query' => request()->param()
            ])
        ]);
    }
    public function teamlist()
    {
        return view('teamlist',[
            'teamlist' => TeamsModel::withSearch('team_name',[
                'team_name' => request()->param('teamname')
            ])->order('team_score','desc')->with('teamusers')->paginate([
                'list_rows' => 20,
                'query' => request()->param()
            ])
        ]);
    }

    public function teamgrid()
    {

        return view('teamgrid',[
            'teamlist' => TeamsModel::withSearch('team_name',[
                'team_name' => request()->param('teamname')
            ])->with('teamusers')->order('team_score','desc')->paginate([
                'list_rows' => 20,
                'query' => request()->param()
            ])
        ]);
    }

    public function teaminfo(Request $request)
    {
        $data = $request->param();
        /*
        $ceshi = Answer::hasWhere('users',['uid'=>1])->select();
        $answerlist = Answer::where('team_id',$data['id'])->order('create_time','desc')->with('users')->select();
        $userlist = Teamusers::where('zid',$data['id'])->order('position','desc')->select();*/


        //return json($answerlist);
        return view('teaminfo',[
            'teaminfo' => TeamsModel::where('id',$data['id'])->find(),
            'userlist' => Teamusers::where(['zid'=>$data['id'],'status'=>1])->order('position','desc')->with('users')->select(),
            'answerlist' => Answer::where('team_id',$data['id'])->order('create_time','desc')->limit(12)->select(),
            'teamcheck' => UsersModel::where('id',session('uid'))->find()
    ]);

    }


    public function usertop()
    {

        return view('usertop',[
            'weblist' => UserModel::field(['id','username','flag_web'])->order('flag_web','desc')->limit(10)->select(),
            'misclist' => UserModel::field(['id','username','flag_misc'])->order('flag_misc','desc')->limit(10)->select(),
            'cryptolist' => UserModel::field(['id','username','flag_crypto'])->order('flag_crypto','desc')->limit(10)->select(),
            'pwnlist' => UserModel::field(['id','username','flag_pwn'])->order('flag_pwn','desc')->limit(10)->select()
        ]);
    }

    public function usertopdata()
    {
        $data = UserModel::field(['username','flag_web','flag_misc','flag_crypto','flag_pwn','flag_reverse','flag_web+flag_misc+flag_crypto+flag_pwn+flag_reverse as count'])->order('count desc')->limit(10)->select();





        //$data->flag_count  = array_sum($data['flag_web'],$data['flag_misc'],$data['flag_crypto'],$data['flag_pwn'],$data['flag_reverse']);
        return json($data);


    }

    public function teamtop()
    {
        return view('teamtop');
    }

    public function teamtopdata()
    {
        $data = TeamsModel::field(['id','team_name','team_score'])->order('team_score desc')->limit(50)->select();
        return json($data);
    }
}
