<?php


namespace app\index\controller;
/**导入要用到的模块和model**/
use app\BaseController;
use app\common\model\Config;
use app\index\controll\Login;
use app\index\model\Answer as AnswerModel;
use app\index\model\Article;
use app\index\model\ArticleType;
use app\index\model\Chatype;
use app\index\model\Imgframe;
use app\index\model\Message;
use app\index\model\Order;
use app\index\model\Teams;
use app\index\model\Topics;
use app\index\model\Users as UsersModel;
use app\Request;
use think\facade\Filesystem;
use think\facade\Session;
use think\facade\Validate;
use think\middleware;

use app\index\model\Challenge as ChallengeModel;
use app\index\model\Action as ActionModel;
use app\index\model\Teamusers as TeamusersModel;
use app\index\model\Chatype as ChatypeModel;

class Users extends BaseController
{

    /*
    public function index()
    {
        return view('index');
    }*/

    /**找回密码方法**/
    public function reset()
    {
        //这个功能还没写，准备使用邮箱找回，用户输入邮箱号发送修改密码的链接到用户邮箱
        return view('reset');
    }


    /**挑战靶场的题目列表页方法**/
    public function challenge(Request $request)
    {
        $data = $request->param();      //获取前端传过来的参数

        if(empty($data['tid']))  //判断前端传参如果没有tid参数和值执行以下，tid参数主要用于判断题目分类筛选
        {

            return view('challenge',[

                'tmlist'    => ChallengeModel::field(['id,topic_name,topic_type,topic_difficulty,topic_score,topic_succeed,topic_file,top_1,top_2,top_3'])->order('create_time','desc')->paginate([
                    'list_rows' => 20,
                    'query' => request()->param()
                ]), //使用Challenge模型查询所有题目数据，分页显示，每页展示20个题目

                'answerlist' => AnswerModel::order('create_time','desc')->limit(10)->select(),  //获取最近10条解题记录
                'answer' => AnswerModel::where('uid',session('uid'))->select(),  //answer变量获取的值主要用于前端判断当前用户是否完成了该题
                'typelist'  => ChatypeModel::select()           //查询题目类型
            ]);

        }else    //判断如果前端传参tid且值为1则执行以下，该值对应web类型题目
        {
            return view('challenge',[
                'tmlist'   =>ChallengeModel::field(['id,topic_type,topic_name,topic_difficulty,topic_score,topic_succeed,top_1,top_2,top_3'])->where('topic_type',$data['tid'])->order('create_time','desc')->paginate([
                    'list_rows' => 20,
                    'query' => request()->param()
                ]),
                'answerlist' => AnswerModel::order('create_time','desc')->limit(10)->select(),
                'toplist' => AnswerModel::order('create_time','asc')->limit(3)->select(),
                'answer' => AnswerModel::where('uid',session('uid'))->select(),  //answer变量获取的值主要用于前端判断当前用户是否完成了该题
                'typelist'  => ChatypeModel::select()           //查询题目类型
            ]);

        }

    }

    /**题目详情页展示方法**/
    public function challengeui(Request $request)
    {
        $data = $request->param();  //获取前端传过来的参数，主要接收cid题目的id值

        $topic = ChallengeModel::field(['id','topic_content','topic_file'])->where('id = '.$data['cid'])->find();   //通过模型查询数据获取题目的基本信息
        return json($topic);    //通过json格式返回给前端ajax
    }


    /**验证用户提交的对应题目的flag值**/
    public function checkflag(Request $request)
    {
        $data = $request->param();  //获取前端传来的题目id值和flag值

        $flag = ChallengeModel::where('id',$data['cid'])->find();  //用$flag变量暂存查询到对应题目的flag值
        $user = UsersModel::where('id',session('uid'))->find();     //用$user变量暂存当前的用户信息
        $anwer = AnswerModel::where(['tid'=>$data['cid'],'uid'=>session('uid')])->find();   //该变量主要用于判断是否是第一次解题还是重复做题
        /**判断用户输入的flag和题目flag是否一致**/
        if($flag['topic_flag'] == $data['flag'])  //判断前端提交的flag与该题数据库存储的falg是否一致，如果一致执行以下
        {


            /***判断用户是否为第一次答题，如果是则插入数据，如果为否则更新答题时间不插入新数据***/
            if($anwer != null){

                /**判断如果为重复做题则只作更新**/
                if($user->getData('team_id') != null)   //判断解题用户是否有战队，如果有解题记录需要记录战队id
                {
                    AnswerModel::update(['team_id' => $user->getData('team_id'),'update_time'=>date("Y-m-d H:i:s")],['id'=>$anwer['id']]);


                }else{
                    AnswerModel::update(['update_time'=>date("Y-m-d H:i:s")],['id'=>$anwer['id']]);
                }


            }else{
                //判断用户是否有team_id数据
                if($user->getData('team_id') != null) {
                    //如果team_id非空则在插入数据时插入team_id
                    AnswerModel::create(['tid' => $data['cid'], 'uid' => session('uid'), 'team_id' => $user->getData('team_id'),'img'=>session('uid') ,'type' => $flag['topic_type'], 'score' => $flag['topic_score']]);
                    ChallengeModel::where('id',$data['cid'])->inc('topic_succeed',1)->update();
                    $tops = AnswerModel::where('tid',$data['cid'])->order('create_time','asc')->limit(3)->select();
                    if(count($tops) == 1)
                    {
                        ChallengeModel::update(['top_1'=>$tops[0]->getData('uid')],['id' => $data['cid']]);
                    }else if(count($tops) == 2)
                    {
                        ChallengeModel::update(['top_2'=>$tops[1]->getData('uid')],['id' => $data['cid']]);
                    }else if(count($tops) == 3)
                    {
                        ChallengeModel::update(['top_3'=>$tops[2]->getData('uid')],['id' => $data['cid']]);
                    }

                    if($flag['topic_type']=='web')
                    {
                        UsersModel::where('id',session('uid'))->inc('score',$flag['topic_score'])->inc('flag_count',1)->inc('flag_web',1)->update();  //给用户增加积分及题目类型解题数量记录
                        Teams::where('id',$user->getData('team_id'))->inc('team_score',$flag['topic_score'])->update();         //给用户所在战队加相应分数

                    }else if($flag['topic_type']=='misc')
                    {
                        UsersModel::where('id',session('uid'))->inc('score',$flag['topic_score'])->inc('flag_count',1)->inc('flag_misc',1)->update();   //给用户增加积分及题目类型解题数量记录
                        Teams::where('id',$user->getData('team_id'))->inc('team_score',$flag['topic_score'])->update();      //给用户所在战队加相应分数
                    }else if($flag['topic_type']=='crypto')
                    {
                        UsersModel::where('id',session('uid'))->inc('score',$flag['topic_score'])->inc('flag_count',1)->inc('flag_crypto',1)->update();     //给用户增加积分及题目类型解题数量记录
                        Teams::where('id',$user->getData('team_id'))->inc('team_score',$flag['topic_score'])->update();      //给用户所在战队加相应分数
                    }else if($flag['topic_type']=='reverse')
                    {
                        UsersModel::where('id',session('uid'))->inc('score',$flag['topic_score'])->inc('flag_count',1)->inc('flag_reverse',1)->update();    //给用户增加积分及题目类型解题数量记录
                        Teams::where('id',$user->getData('team_id'))->inc('team_score',$flag['topic_score'])->update();      //给用户所在战队加相应分数
                    }else if($flag['topic_type']=='pwn')
                    {
                        UsersModel::where('id',session('uid'))->inc('score',$flag['topic_score'])->inc('flag_count',1)->inc('flag_pwn',1)->update();       //给用户增加积分及题目类型解题数量记录
                        Teams::where('id',$user->getData('team_id'))->inc('team_score',$flag['topic_score'])->update();      //给用户所在战队加相应分数
                    }

                }else {
                    //如果用户team_id为空没有队伍则在插入到数据库时不写team_id字段
                    AnswerModel::create(['tid' => $data['cid'], 'uid' => session('uid'), 'img'=>session('uid'),'type' => $flag['topic_type'], 'score' => $flag['topic_score']]);
                    ChallengeModel::where('id',$data['cid'])->inc('topic_succeed',1)->update();
                    $tops = AnswerModel::where('tid',$data['cid'])->order('create_time','asc')->limit(3)->select();

                    /***判断题目前三血，如果没有则进将用户id插入到该题目的top_1~3字段***/
                        if(count($tops) == 1)
                        {
                            ChallengeModel::update(['top_1'=>$tops[0]->getData('uid')],['id' => $data['cid']]);
                        }else if(count($tops) == 2)
                        {
                            ChallengeModel::update(['top_2'=>$tops[1]->getData('uid')],['id' => $data['cid']]);
                        }else if(count($tops) == 3)
                        {
                            ChallengeModel::update(['top_3'=>$tops[2]->getData('uid')],['id' => $data['cid']]);
                        }


                    /***判断题目类型，在用户对应题目类型字段进行增加计数和加分***/
                    if($flag['topic_type']=='web')
                    {
                        UsersModel::where('id',session('uid'))->inc('score',$flag['topic_score'])->inc('flag_count',1)->inc('flag_web',1)->update();

                    }else if($flag['topic_type']=='misc')
                    {
                        UsersModel::where('id',session('uid'))->inc('score',$flag['topic_score'])->inc('flag_count',1)->inc('flag_misc',1)->update();

                    }else if($flag['topic_type']=='crypto')
                    {
                        UsersModel::where('id',session('uid'))->inc('score',$flag['topic_score'])->inc('flag_count',1)->inc('flag_crypto',1)->update();

                    }else if($flag['topic_type']=='reverse')
                    {
                        UsersModel::where('id',session('uid'))->inc('score',$flag['topic_score'])->inc('flag_count',1)->inc('flag_reverse',1)->update();

                    }else if($flag['topic_type']=='pwn')
                    {
                        UsersModel::where('id',session('uid'))->inc('score',$flag['topic_score'])->inc('flag_count',1)->inc('flag_pwn',1)->update();
                    }


                }

            }

            $msg['msg'] = '恭喜你，解题成功！';
            $msg['code']  = 1;
            return json($msg);

        }else{
            $msg['msg'] = '抱歉，您提交的FLAG错误~';
            $msg['code']  = 0;

            return json($msg);
        }
    }

    /**最新动态方法**/
    public function action()
    {

        return view('action',[
            'list' => ActionModel::order('action_time','desc')->paginate([ //使用ActionModel模型查询最新动态
                'list_rows' => 20,
                'query' => request()->param()
            ])
        ]);


    }


    /**个人信息页方法**/
    public function info()
    {
        //$data = $request->param();
        $data = UsersModel::where(['id'=>session('uid'),'username' => session('users')])->select();
        return view('info',[
            'list'  => UsersModel::where(['id'=>session('uid'),'username' => session('users')])->select(),
            'answerlist'  => AnswerModel::where('uid',session('uid'))->paginate([
                'list_rows' => 10,
                'query' => request()->param()
            ])

        ]);
    }

    public function userflag()
    {
        $data = UsersModel::field('flag_web,flag_misc,flag_crypto,flag_reverse,flag_pwn')->where('id',session('uid'))->find();

        return json($data);
    }

    /***设置个人信息***/
    public function setinfo()
    {
        return view('setinfo',[
            'infonow' => UsersModel::where('id',session('uid'))->select(),
            'frame' => Imgframe::where('uid',session('uid'))->select()
        ]);
    }


    /***重置密码***/
    public function setpass()
    {

        return view('setpass',[
            'infonow' => UsersModel::where('id',session('uid'))->select(),
        ]);
    }

    /***我的战队信息页***/
    public function myteam()
    {
        $data = UsersModel::where('id',session('uid'))->find();
        $info = Teams::where('id', $data->getData('team_id'))->select();
        $infoedt = Teams::where('id', $data->getData('team_id'))->select();
        //$userinfo = UsersModel::field('id,img')->where(['id'=>$info->getData('team_1'),'id'=>$info->getData('team_2')])->select();
        //return $userinfo;
        $teamlist = TeamusersModel::where(['zid'=>$data->getData('team_id'),'status'=>1])->with('users')->order('position','desc')->select();
        //$teamlist = UsersModel::where('team_id',$data->getData('team_id'))->with('teamusers')->select();
        //dd($teamlist);
        $applylist = TeamusersModel::where(['zid'=>$data->getData('team_id'),'status'=>0])->select();
        $myapply = TeamusersModel::where('cid',session('uid'))->find();

        if($myapply) {
            $teamstatus = $myapply->getData('status');
        }else{
            $teamstatus = 99;
        }

        if($data) {
            return view('myteam', [
                'info' => $info,
                'infoedt'=> $infoedt,
                //'userinfo' => $userinfo
                'teamlist' => $teamlist,
                'applylist' => $applylist,
                'count' => count($applylist),
                'myapply' => $myapply,
                'teamstatus' =>$teamstatus
            ]);
        }else{
            return view('myteam');
        }


    }

    /***用户退出账号***/
    public function logout()
    {
        session('uid',null);
        session('users',null);
        session('img',null);
        session('team_id',null);
        return redirect('/');
    }


    /**创建战队方法**/
    public function teamadd(Request $request)
    {

        $data = $request->param();

        if($request->file()){
            $file = $request->file('file');
            $result=validate(['file' => [
                'fileSize'=>1048576,
                'fileExt'=>'gif,jpg,png,jpeg',
                'fileMime'=>'image/jpeg,image/png,image/gif',
            ]])->check(['file' => $file]);
        }else{
            $result = 0;
            $file = null;
        }
        $user = UsersModel::where('id',session('uid'))->find();
        $msg = [];

        $checkname = Teams::where('team_name',$data['teamname'])->find();

        $checkteam = Teams::where('team_leader',session('uid'))->find();
        if($checkname && $checkname->getData('team_leader') != session('uid'))
        {
            $msg['code']=0;
            $msg['msg']='战队名已存在';
            return json($msg);
        }else if($checkteam && $checkteam->getData('team_leader') == session('uid')){

            if($file != null){
                if ($result) {
                    //上传到服务器,
                    $path = Filesystem::disk('local')->putFile('uploads', $file);
                    //结果是 $path = upload/20200825\***.jpg

                    //图片路径，Filesystem::getDiskConfig('public','url')功能是获取public目录下的storage，
                    $picCover = Filesystem::getDiskConfig('local', 'url')  . str_replace('\\', '/', $path);
                    //结果是 $picCover = storage/upload/20200825/***.jpg

                    //获取图片名称
                    //$fileName = $file->getOriginalName();

                    Teams::update(['team_name'=>$data['teamname'],'team_img'=>$picCover,'team_content'=>$data['manifesto'],'update_time'=>date('Y-m-d H:i:s')],['team_leader'=>session('uid')]);



                    $msg['code'] = 1;
                    $msg['msg'] = '编辑成功';
                    return json($msg);
                }else{
                    $msg['code'] = 0;
                    $msg['msg'] = '头像文件类型好像不符，或图片太大！';
                    return json($msg);
                }

            }else{
                Teams::update(['team_name'=>$data['teamname'],'team_content'=>$data['manifesto'],'update_time'=>date('Y-m-d H:i:s')],['team_leader'=>session('uid')]);
                $msg['code'] = 1;
                $msg['msg'] = '编辑成功';
                return json($msg);
            }



        }else{
            if($file != null){
                if ($result) {
                    //上传到服务器,
                    $path = Filesystem::disk('local')->putFile('uploads', $file);
                    //结果是 $path = upload/20200825\***.jpg

                    //图片路径，Filesystem::getDiskConfig('public','url')功能是获取public目录下的storage，
                    $picCover = Filesystem::getDiskConfig('local', 'url')  . str_replace('\\', '/', $path);
                    //结果是 $picCover = storage/upload/20200825/***.jpg

                    //获取图片名称
                    //$fileName = $file->getOriginalName();

                    $team = new Teams;      //创建Teamd模型实例赋值给team变量用来保存战队信息
                    $team->team_name = $data['teamname'];
                    $team->team_img = $picCover;
                    $team->team_content = $data['desc'];
                    $team->team_status = 1;
                    $team->team_leader = session('uid');
                    $team->team_number = 1;
                    $team->create_time = date('Y-m-d H:i:s');
                    $team->save();

                    $teamusers = new TeamusersModel;      //把创建用户的和创建战队加入到team用户表用来统计战队人数
                    $teamusers->zid = $team->id;
                    $teamusers->cid = session('uid');
                    $teamusers->uid_img = session('img');
                    $teamusers->status = 1;
                    $teamusers->position = 2;
                    $teamusers->score = session('uid');
                    $teamusers->create_time = date('Y-m-d H:i:s');
                    $teamusers->save();

                    UsersModel::update(['team_id' => $team->id], ['id' => session('uid')]);
                    Teams::update(['team_1'=>session('uid')],['id'=>$team->id]);
                    $msg['code'] = 1;
                    $msg['msg'] = '创建成功';
                    return json($msg);
                }else{
                    $msg['code'] = 0;
                    $msg['msg'] = '头像文件类型好像不符，或图片太大！';
                    return json($msg);
                }
            }else{
                $team = new Teams;
                $team->team_name = $data['teamname'];
                $team->team_content = $data['desc'];
                $team->team_status = 1;
                $team->team_leader = session('uid');
                $team->team_number = 1;
                $team->save();

                $teamusers = new TeamusersModel;
                $teamusers->zid = $team->id;
                $teamusers->cid = session('uid');
                $teamusers->uid_img = session('img');
                $teamusers->status = 1;
                $teamusers->position = 2;
                $teamusers->score = session('uid');
                $teamusers->save();

                UsersModel::update(['team_id' => $team->id], ['id' => session('uid')]);
                Teams::update(['team_1'=>session('uid')],['id'=>$team->id]);
                $msg['code'] = 1;
                $msg['msg'] = '创建成功';
                return json($msg);
            }
        }

    }

    /**删除战队方法**/
    public function delteam()
    {
        $user = UsersModel::field('team_id')->where('id',session('uid'))->find();



            Teams::where(['id'=>$user->getData('team_id'),'team_leader'=>session('uid')])->delete();
            TeamusersModel::where('zid',$user->getData('team_id'))->delete();

            UsersModel::update(['team_id'=>null],['id'=>session('uid')]);
        $msg['msg'] = '队伍已成功解散！';
        return json($msg);
    }


    /**申请加入战队方法**/
    public function teamapply( Request $request)
    {

        $key = json($request->param());
        $data = json_decode($key->getContent(),true);
        $msg =[];
        $user = UsersModel::where('id',session('uid'))->find();
        $team = Teams::where('team_name',$data['teamname'])->find();

        $apply = TeamusersModel::where('cid',session('uid'))->find();

        if($team != null)
        {
            $teamcount = TeamusersModel::where(['zid'=>$team['id'],'status'=>1],)->select();
            if(count($teamcount) < 10)
            {
                if($apply == null) {
                    TeamusersModel::create(['zid' => $team['id'], 'cid' => session('uid'), 'uid_img' => session('img'), 'status' => 0, 'score' => session('uid'), 'text_content' => $data['reason']]);
                    Message::create(['uid'=>$team->getData('team_leader'),'content'=>'<span class="text-primary">'.session('users').'</span>&nbsp;申请加入您的战队','url'=>'/users/myteam','type'=>0]);
                    $msg['code']=1;
                    $msg['msg'] = '已发出申请入队请求！';
                    return json($msg);
                }else{
                    $msg['code'] =0;
                    $msg['msg'] = '异常错误！';
                    return json($msg);
                }
            }else if(count($teamcount) >=10){

                $msg['code']=0;
                $msg['msg']='抱歉，该队伍已经满员~';
                return json($msg);
            }

        }else{
            $msg['code']=0;
            $msg['msg']='战队不存在，请查看战队名是否有误！';
            return json($msg);
        }
    }

    /**删除申请加入战队的请求方法**/
    public function delApply(Request $request)
    {
        $data = $request->param();
        $msg['msg']='已删除该请求！';
        TeamusersModel::where('id',$data['id'])->delete();
        return json($msg);
    }

    /**同意战队申请入队方法**/
    public function agreeApply(Request $request)
    {
        $data = $request->param();
        $info = TeamusersModel::where('cid',$data['id'])->find();
        $team = Teams::where('id',$info['zid'])->find();
        TeamusersModel::update(['status'=>1],['cid'=>$data['id']]);
        UsersModel::update(['team_id'=>$info['zid']],['id'=>$data['id']]);
        $cut = TeamusersModel::where('zid',$info['zid'])->select();
        Message::create(['uid'=>$data['id'],'content'=>'<span class="text-primary">'.$team['team_name'].'</span>'.'战队已经<span class="text-primary">'.'同意'.'</span>>了您的加入申请！','url'=>'/users/myteam','type'=>0]);


        Teams::update(["team_".count($cut)=>$data['id']],['id'=>$info['zid']]);
        $msg['msg']= '恭喜您，多了一位队员!';
        return json($msg);

    }

    /**拒绝入队请求方法**/
    public function refuseApply(Request $request)
    {
        $data = $request->param();
        $msg['msg']='已拒绝该请求！';
        $info = TeamusersModel::where('cid',$data['id'])->find();
        $team = ChallengeModel::where('id',$info['zid'])->find();
        TeamusersModel::update(['status'=>2],['cid'=>$data['id']]);
        Message::create(['uid'=>$data['id'],'content'=>'您好，<span class="text-primary">'.$team['team_name'].'</span>'.'&nbsp;战队<span class="text-danger">拒绝</span>了您的加入申请！','url'=>'/users/myteam','type'=>0]);

        return json($msg);

    }

    /***撤销加入战队的入队申请***/
    public function cancelApply()
    {
        $the_time = date('Y-m-d h:i:s', time());
        $apply = TeamusersModel::where('cid',session('uid'))->find();
        $dur = strtotime($apply['create_time'])-strtotime($the_time);

        if($apply)
        {
            if($dur > 86400)
            {
                TeamusersModel::where('cid',session('uid'))->delete();
                $msg['msg'] = '成功撤销入队申请！';
                $msg['bg'] = 'success';
                return json($msg);
            }else{
                $msg['msg']='入队请求未满24小时，暂无法撤销！';
                $msg['bg'] = 'error';
                return json($msg);
            }
        }else {
            $msg['msg'] = '未知错误~';
            return json($msg);
        }

    }

    /***修改战队成员角色***/
    public function updateRole(Request $request)
    {
        $data = $request->param();

        TeamusersModel::update(['position'=>$data['role']],['cid'=>$data['user_id']]);

        $msg['msg']='编辑成功~';
        return json($msg);

    }


    /***消息中心***/
    public function message()
    {

        /*$data = Message::where('uid',session('uid'))->order('create_time','desc')->paginate([
            'list_rows'=>20,
            'query' => request()->param()
        ]);*/
        Message::update(['status'=>1],['uid'=>session('uid')]);

        return view('message',[
           'msglist'=>  Message::where('uid',session('uid'))->order('create_time','desc')->paginate([
               'list_rows'=>10,
               'query' => request()->param()
           ])
        ]);
    }

    /***清空消息记录***/
    public function delmsg()
    {
        Message::where('uid',session('uid'))->delete();

        $msg['msg']='操作成功！';
        return json($msg);
    }


    /***退出战队方法***/
    public function teamquit()
    {

        TeamusersModel::where('cid',session('uid'))->delete();
        UsersModel::update(['team_id'=>null],['id'=>session('uid')]);


        $msg['msg'] = '您已成功退出该战队~';

        return json($msg);
    }


    /***转让战队***/
    public function transfer(Request $request)
    {
        $data = $request->param();
        $teaminfo = TeamusersModel::where('cid',$data['user_id'])->find();
        TeamusersModel::update(['position'=>2],['cid'=>$data['user_id']]);
        Teams::update(['team_leader'=>$data['user_id']],['id'=>$teaminfo['zid']]);
        TeamusersModel::where('cid',session('uid'))->delete();
        UsersModel::update(['team_id'=>null],['id'=>session('uid')]);

        Message::create(['uid'=>$data['user_id'],'content'=>'恭喜您，您已成为<span class="text-primary">'.$teaminfo['team_id'].'</span>战队的队长！','url'=>'/users/myteam','type'=>0]);


        $msg['msg'] = '操作成功！';
        return json($msg);

    }


    /***将成员从战队中移除***/
    public function removeUser(Request $request)
    {
        $data = $request->param();
        $info = TeamusersModel::where('cid',$data['user_id'])->find();
        UsersModel::update(['team_id'=>null],['id'=>$data['user_id']]);
        TeamusersModel::where('cid',$data['user_id'])->delete();

        Message::create(['uid'=>$data['user_id'],'content'=>'抱歉，您已被'.$info['team_id'].'战队移出该队伍！','url'=>'/users/myteam','type'=>0]);

        $msg['msg']='操作成功！';
        return json($msg);

    }


    /***获取申请入队的留言***/
    public function applylist()
    {
        //$data = $request->param();

        $team = TeamusersModel::where('cid',session('uid'))->find();
        if($team != null){
            if($team->getData('position') == 2) {
                $apply = TeamusersModel::where(['zid' => $team['zid'], 'status' => 0])->select();
                $data = [];
                for ($i = 0; $i < count($apply); $i++) {
                    $data[$i] = $apply[$i]->getData();

                }
                return json($data);
            }
        }else{

            return '';
        }
    }


    /***修改用户资料***/
    public function edtinfo(Request $request)
    {
        $key = json($request->param());
        $data = json_decode($key->getContent(),true);
        //dd($data);
        $checkname = UsersModel::where('username',$data['nickname'])->find();
        if($checkname != null && $checkname['id'] != session('uid'))
        {
            $msg['code']=0;
            $msg['msg']='该用户名已存在了呢~';
            return json($msg);
        }else{
            UsersModel::update(['username'=>$data['nickname'],'email'=>$data['email'],'phone'=>$data['phone'],'frame_img'=>$data['border_img']],['id'=>session('uid')]);
            $msg['code']=1;
            $msg['msg']='编辑成功~';
            return json($msg);
        }
    }


    /***更新用户banner图片***/
    public function uploadBg(Request $request)
    {
        $file = $request->file('file');

        $result=validate(['file' => [
            'fileSize'=>1048576,
            'fileExt'=>'gif,jpg,png,jpeg',
            'fileMime'=>'image/jpeg,image/png,image/gif',
        ]])->check(['file' => $file]);


        if ($result) {
            //上传到服务器,
            $path = Filesystem::disk('local')->putFile('uploads', $file);
            //结果是 $path = upload/20200825\***.jpg

            //图片路径，Filesystem::getDiskConfig('public','url')功能是获取public目录下的storage，
            $picCover = Filesystem::getDiskConfig('local', 'url') . str_replace('\\', '/', $path);
            //结果是 $picCover = storage/upload/20200825/***.jpg

            //获取图片名称
            //$fileName = $file->getOriginalName();

            UsersModel::update(['banner_img'=>$picCover],['id'=>session('uid')]);
            $msg['code'] = 1;
            $msg['url'] = $picCover;
            $msg['msg'] = '操作成功~';
            return json($msg);
        }else{
            $msg['code'] = 0;
            $msg['msg'] = '头像文件类型好像不符，或图片太大！';
            return json($msg);

        }

    }


    /**更新用户头像**/
    public function uploadAvatar(Request $request)
    {
        $file = $request->file('file');

        $result=validate(['file' => [
            'fileSize'=>1048576,
            'fileExt'=>'gif,jpg,png,jpeg',
            'fileMime'=>'image/jpeg,image/png,image/gif',
        ]])->check(['file' => $file]);


        if ($result) {
            //上传到服务器,
            $path = Filesystem::disk('local')->putFile('uploads', $file);
            //结果是 $path = upload/20200825\***.jpg

            //图片路径，Filesystem::getDiskConfig('public','url')功能是获取public目录下的storage，
            $picCover = Filesystem::getDiskConfig('local', 'url')  . str_replace('\\', '/', $path);
            //结果是 $picCover = storage/upload/20200825/***.jpg

            //获取图片名称
            //$fileName = $file->getOriginalName();

            UsersModel::update(['img'=>$picCover],['id'=>session('uid')]);
            session('img',$picCover);

            $msg['code'] = 1;
            $msg['url'] = $picCover;
            $msg['msg'] = '操作成功~';
            return json($msg);
        }else{
            $msg['code'] = 0;
            $msg['msg'] = '头像文件类型好像不符，或图片太大！';
            return json($msg);

        }

    }

    /**設置密码方法**/
    public function edtpass(Request $request)
    {
        $key = json($request->param());
        $data = json_decode($key->getContent(),true);
        $oldpass = UsersModel::where(['id'=>session('uid'),'password'=>md5($data['oldpass'])])->find();
        if($oldpass != null)
        {
            if($data['password']!=$data['repassword'])
            {
                $msg['code'] = 2;
                $msg['msg'] = '两次新密码不一致';
                return json($msg);
            }else{
                UsersModel::update(['password'=>md5($data['repassword'])],['id'=>session('uid')]);
                $this->logout();
                $msg['code']=1;
                $msg['msg']='密码更新成功';
                return json($msg);
            }
        }else{
            $msg['code']=0;
            $msg['msg']='原密码不正确！';
            return json($msg);
        }
    }

    /**我的订单页面方法**/
    public function order()
    {

        return view('order',[
            'list'  => Order::where('uid',session('uid'))->find(),
           'orderlist'  => Order::where('uid',session('uid'))->order('create_time','desc')->paginate([
               'list_rows' => 10,
               'query' => request()->param()
           ])

        ]);
    }

    /**情况订单记录方法**/
    public function delorder()
    {

        Order::where('uid',session('uid'))->delete();
        $msg['msg']='操作成功~';
        return json($msg);
    }


    /**文章投稿页面渲染**/
    public function contribute()
    {
        $zblog = Config::field('value')->where('name','zblog_status')->find();
        if($zblog['value'] == 1)
        {
            return view('contribute',[
                'typelist' => Articletype::select()
            ]);
        }elseif ($zblog['value']==0)
        {
            $msg['msg']='博客功能暂未开放，禁止投稿！';
            $msg['url']='/';
            return view('public/sikp',[
                'msg'=>$msg
            ]);
        }

    }

    /**获取第一张图片**/
    public function get_pic($str_img)
    {
        preg_match_all("/<img.*\>/isU", $str_img, $ereg); //正则表达式把图片的整个都获取出来了
        $img = $ereg[0][0]; //图片
        $p = "#src=('|\")(.*)('|\")#isU"; //正则表达式
        preg_match_all($p, $img, $img1);
        $img_path = $img1[2][0]; //获取第一张图片路径
        return $img_path;
    }

    /**文章投稿方法**/
    public function contributepost(Request $request)
    {
        $data = $request->param();
        /**获取文章第一张图片**/
        $info=$this->get_pic($data['content']);


        $article = new Article();
        $article->uid = session('uid');
        $article->img  = $info;
        $article->title = $data['title'];
        $article->content = $data['content'];
        $article->type    = $data['type'];
        $article->create_time = date('Y-m-d H:i:s',time());
        $article->update_time = null;
        $article->save();

        $msg['url']= "/article/detail/id/".$article->id;
        $msg['msg']='文章发布成功~';
        return json($msg);

    }






    /**题目投稿页渲染**/
    public function challengeup()
    {

        return view('challengeup',[
            'types' => Chatype::select(),
            'info'  => UsersModel::field('id,username')->where('id',session('uid'))->find(),
        ]);

    }


    /**题目投稿方法**/
    public function challengespost(Request $request)
    {
        $data = $request->param();
        //{title: "标题", type: "1", remarks: "备注", name: "challengespost"}

        $file = $request->file('file');

        $result=validate(['file' => [
            'fileSize'=>31457280,
            'fileExt'=>'zip,rar,7z,gz,tar,tar.gz',
            'fileMime'=>'application/zip,application/octet-stream,application/x-gzip,application/x-tar,application/x-gtar,application/x-rar-compressed,application/x-zip-compressed',
        ]])->check(['file' => $file]);


        if ($result) {
            //上传到服务器,
            $path = Filesystem::disk('local')->putFile('uploads', $file);
            //结果是 $path = upload/20200825\***.jpg

            //图片路径，Filesystem::getDiskConfig('public','url')功能是获取public目录下的storage，
            $picCover = Filesystem::getDiskConfig('local', 'url')  . str_replace('\\', '/', $path);
            //结果是 $picCover = storage/upload/20200825/***.jpg

            //获取图片名称
            //$fileName = $file->getOriginalName();

            /*UsersModel::update(['img'=>$picCover],['id'=>session('uid')]);
            session('img',$picCover);*/
            $topics = new Topics();
            $topics->uid = $data['uid'];
            $topics->type = $data['type'];
            $topics->title = $data['title'];
            $topics->topic_file = $picCover;
            $topics->remarks = $data['remarks'];
            $topics->save();

            $msg['code'] = 1;
            $msg['msg'] = '投稿成功~';
            return json($msg);
        }else{
            $msg['code'] = 0;
            $msg['msg'] = '文件太大，请上传到云盘后备注下载链接！';
            return json($msg);

        }
    }



    /**wangedit编辑器上传图片**/
    public function uploadimg(Request $request)
    {

        $file = $request->file('image');

        $result=validate(['file' => [
            'fileSize'=>1048576,
            'fileExt'=>'gif,jpg,png,jpeg',
            'fileMime'=>'image/jpeg,image/png,image/gif',
        ]])->check(['file' => $file]);
        $data=[];

        if ($result) {
            //上传到服务器,
            $path = Filesystem::disk('local')->putFile('uploads', $file);
            //结果是 $path = upload/20200825\***.jpg

            //图片路径，Filesystem::getDiskConfig('public','url')功能是获取public目录下的storage，
            $picCover = Filesystem::getDiskConfig('local', 'url') . str_replace('\\', '/', $path);
            //结果是 $picCover = storage/upload/20200825/***.jpg

            //获取图片名称
            //$fileName = $file->getOriginalName();

            UsersModel::update(['banner_img'=>$picCover],['id'=>session('uid')]);
            $msg['errno'] = 0;
            $msg['data'][0] = $picCover;
            //$msg['msg'] = '操作成功~';
            return json($msg);
        }else{
            $msg['errno'] = 1;
            $msg['msg'] = '头像文件类型好像不符，或图片太大！';
            return json($msg);

        }

    }


    /**办赛方法**/
    public function Competition(Request $request)
    {
        $data = $request->param();      //获取前端传过来的参数

        if(empty($data['tid']))  //判断前端传参如果没有tid参数和值执行以下，tid参数主要用于判断题目分类筛选
        {

            return view('competition',[

                'tmlist'    => ChallengeModel::field(['id,topic_name,topic_type,topic_difficulty,topic_score,topic_succeed,topic_file,top_1,top_2,top_3'])->where('competition_id',$data['id'])->order('create_time','desc')->paginate([
                    'list_rows' => 20,
                    'query' => request()->param()
                ]), //使用Challenge模型查询所有题目数据，分页显示，每页展示20个题目

                'answerlist' => AnswerModel::order('create_time','desc')->limit(10)->select(),  //获取最近10条解题记录
                'answer' => AnswerModel::where('uid',session('uid'))->select(),  //answer变量获取的值主要用于前端判断当前用户是否完成了该题
                'typelist'  => ChatypeModel::select()           //查询题目类型
            ]);

        }else    //判断如果前端传参tid且值为1则执行以下，该值对应web类型题目
        {
            return view('challenge',[
                'tmlist'   =>ChallengeModel::field(['id,topic_name,topic_difficulty,topic_score,topic_succeed,top_1,top_2,top_3'])->where(['competition_id'=>$data['id'],'topic_type'=>$data['tid']])->order('create_time','desc')->paginate([
                    'list_rows' => 20,
                    'query' => request()->param()
                ]),
                'answerlist' => AnswerModel::order('create_time','desc')->limit(10)->select(),
                'toplist' => AnswerModel::order('create_time','asc')->limit(3)->select(),
                'answer' => AnswerModel::where('uid',session('uid'))->select(),  //answer变量获取的值主要用于前端判断当前用户是否完成了该题
                'typelist'  => ChatypeModel::select()           //查询题目类型
            ]);

        }

    }






}