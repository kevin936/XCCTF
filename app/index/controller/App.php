<?php
namespace app\index\controller;

use app\BaseController;
use app\index\model\Teams as TeamsModel;
use app\index\model\Users as UserModel;
use app\index\model\Users as UsersModel;


class App extends BaseController
{
    public function index()
    {
        return view('index');
    }

    public function hello($name = 'ThinkPHP6')
    {
        return 'hello,' . $name;
    }

    public function teams()
    {
        return view('teams',[
            'list'  =>  TeamsModel::withSearch('team_name',[
                'team_name' => request()->param('teamname')
            ])->paginate([
                'list_rows' => 10,
                'query' => request()->param()
            ])
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
