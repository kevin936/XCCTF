<?php


namespace app\index\controller;
use app\BaseController;
use app\index\model\ArticleType;
use app\index\model\Comment;
use app\index\model\CommentReply;
use app\index\model\FriendLinks;
use app\index\model\Message;
use app\index\validate\Rigister as Rgevalidate;
use app\Request;
use app\index\model\Article as ArticleModel;
use think\exception\ErrorException;

use app\index\model\Users as UsersModel;
use think\exception\ValidateException;

class Article extends BaseController
{

    /**文章列表页**/
    public function articlelist()
    {
        $data['id']=array(2,3,4);

        return view('articlelist',[
            'Recommend' => ArticleModel::where('top',2)->order('create_time','desc')->limit(6)->with('Types')->select(),
            'mulu'  => ArticleType::with('articlecount')->select(),     //分类目录
            'typelist'=> ArticleType::with('articles')->where($data)->select(),  //分类展示模块数据，分别展示id为2，3，4的分类
            'list'   => ArticleModel::where('status',1)->order('top desc,create_time desc')->with('Types')->paginate([
                'list_rows' =>  20,
                'query' => request()->param()
            ]), //文章垂直列表数据
            'toplist'   => ArticleModel::where('top',1)->order('create_time','desc')->select(),
            'commentlist'   =>  Comment::order('create_time','desc')->limit(8)->with(['users','cids'])->select(),
            'articlerep'   => ArticleModel::order('comment_count','desc')->limit(5)->select(),  //热评文章top5
            'articletop'    => ArticleModel::order('browsing','desc')->limit(3)->select(),  //热门文章top3
            'friendlink'    => FriendLinks::select()
        ]);
    }
    /**文章详情页渲染**/
    public function detail(Request $request)
    {

        $data = $request->param();

        $check = ArticleModel::where(['id'=>$data['id'],'status'=>1])->find();
        if($check != null)
        {
            ArticleModel::where('id',$data['id'])->inc('browsing',1)->update();
            return view('index',[
                'article' => $check,
                'commentlist'  => Comment::where('cid',$data['id'])->with(['users','replys'])->order('create_time','desc')->paginate([
                    'list_rows' => 20,
                    'query' => request()->param()
                ]),
                'myscore'   => UsersModel::field('score')->where('id',session('uid'))->find()
            ]);
        }else{
            abort(404, '未找到资源');
        }
    }

    /**文章编辑页面渲染**/
    public function editcontribute(Request $request)
    {
        $data = $request->param();

        return view('editcontribute',[
            'detail' => ArticleModel::where('id',$data['id'])->find(), //查询当前文章ID的内容及信息
            'typelist'=>ArticleType::select()       //查询文章类型列表
        ]);
    }

    /**文章编辑更新**/
    public function contributeedit(Request $request)
    {
        $data = $request->param();
        $zuozhe = ArticleModel::where('id',$data['id'])->find();
        if($zuozhe->getData('uid') == session('uid'))       //判断当前用户是否为该文章的作者，如果是则更新文章
        {
            ArticleModel::update(['title'=>$data['title'],'content'=>$data['content'],'type'=>$data['type'],'update_time'=>date('Y-m-d H:i:s',time())],['id'=>$data['id']]);
            $msg['code']=1;
            $msg['url']='/article/detail/id/'.$data['id'];
            $msg['msg']='文章编辑成功！';
            return json($msg);
        }else{
            $msg['code']=0;
            $msg['msg']='你不是该篇文章的作者，您尝试越权修改？';
            return json($msg);
        }
    }

    /**新增评论方法**/
    public function addComment(Request $request)
    {

        if(!empty(session('uid')))
        {
            if(isset($request->param()['reply_id']))        //判断前端传过来的参数是否呦reply_id参数，因为携带reply_id参数的为回复内容。否则就是评论内容
            {
                $data = $request->param();
                $toid = Comment::field('from_uid')->where('id',$data['reply_id'])->find();
                $comment_reply = new CommentReply();
                $comment_reply->reply_id = $data['reply_id'];
                $comment_reply->from_id = session('uid');
                $comment_reply->to_id = $toid['from_uid'];
                $comment_reply->content = $data['content'];
                $comment_reply->save();

                Message::create(['uid'=>$comment_reply['to_id'],'content'=>'您的评论收到了一条新回复','url'=>'/article/detail/id/'.$data['id'],'type'=>0]);
                $msg['code']=1;
                $msg['msg']='回复成功~';
                return json($msg);
            }else{
                $data = $request->param();
                $zuozhe = ArticleModel::where('id',$data['id'])->find();
                $comment = new Comment();
                $comment->cid = $data['id'];
                $comment->from_uid = session('uid');
                $comment->to_uid = $zuozhe->getData('uid');
                $comment->content = $data['content'];
                $comment->save();
                Message::create(['uid'=>$zuozhe->getData('uid'),'content'=>'有人对您的文章进行了评论！','url'=>'/article/detail/id/'.$data['id'],'type'=>0]);
                $msg['code']=1;
                $msg['msg']='评论成功~';
                return json($msg);
            }
        }else{

            $msg['code']=0;
            $msg['msg']='抱歉，您还未登入！';
            $msg['url']='/login';
            return json($msg);
        }
    }

    /**新增回复**/
    /*public function addCommentreply(Request $request)
    {
        $data = $request->param();

        $toid = Comment::field('from_id')->where('id',$data['reply_id'])->find();     //查询回复的用户uid，用于后面消息通知对方收到新回复。
        $comment_reply = new CommentReply();
        $comment_reply->reply_id = $data['reply_id'];
        $comment_reply->from_id = session('uid');
        $comment_reply->to_id = $toid['from_uid'];
        $comment_reply->content = $data['content'];
        $comment_reply->save();

        Message::create(['uid'=>$comment_reply['to_id'],'content'=>'您的评论收到了一条新回复','url'=>'/article/detail/id/'.$data['id'],'type'=>0]);

        $msg['msg']='回复成功~';
        return json($msg);
    }*/

    /**删除评论**/
    public function delComment(Request $request)
    {
        $data = $request->param();
        $check = Comment::where('id',$data['id'])->find();
        if($check['from_uid']==session('uid'))      //判断该条评论是否为当前用户发表
        {
            Comment::where('id',$data['id'])->delete();
            CommentReply::where('reply_id',$data['id'])->delete();
            $msg['status']=1;
            $msg['msg']='删除成功';
            return json($msg);
        }else{
            $msg['status']=0;
            $msg['msg']='未知错误~';
            return json($msg);
        }
    }

    /**删除回复**/
    public function delCommentreply(Request $request)
    {
        $data = $request->param();
        $check = CommentReply::where('id',$data['id'])->find();

        if($check->getData('from_id') == session('uid'))        //判断该条回复是否为当前用户回复
        {
            CommentReply::where('id', $data['id'])->delete();
            $msg['status']=1;
            $msg['msg']='删除成功！';
            return json($msg);
        }else{
            $msg['status']=0;
            $msg['msg']='删除失败~';
            return json($msg);
        }
    }

    /**评论点赞方法**/
    /*public function addLike(Request $request)
    {
        $data = $request->param();
        $pk = Comment::where('like_count','in',session('uid'))->find();

        Comment::where('id',$data['id'])->inc('like_count',",session('uid')")->update();
        $msg['status']=1;
    }*/


    /**打赏文章作者**/
    public function PlayTour(Request $request)
    {
        $data = $request->param();
        $formcheck = UsersModel::where('id',session('uid'))->find();
        $tocheck = UsersModel::where('id',$data['uid'])->find();

        try {
            validate(Rgevalidate::class)->batch(true)->check($request->param());

            if($formcheck['score'] >= $data['score'] && $data['score'] >0)       //判断当前用户的积分余额是否足够进行打赏
            {
                UsersModel::where('id',session('uid'))->dec('score',$data['score'])->update();
                UsersModel::where('id',$data['uid'])->inc('score',$data['score'])->update();
                $msg['code']=1;
                $msg['msg']='打赏成功!';
                return json($msg);
            }else{
                $msg['code']=0;
                $msg['msg']='积分不足！';
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


    /**文章点赞**/
    public function likeadd(Request $request)
    {
        $data = $request->param();

        if(isset($request->param()['id']))
        {
            $res = ArticleModel::where('id',$data['id'])->inc('like_count',1)->update();

            if($res){
                $msg['code']=1;
                return json($msg);
            }else{
                $msg['code']=0;
                return json($msg);
            }
        }elseif (isset($request->param()['cid']))
        {
            $res = Comment::where('id',$data['cid'])->inc('like_count',1)->update();

            if($res){
                $msg['code']=1;
                return json($msg);
            }else{
                $msg['code']=0;
                return json($msg);
            }

        }elseif (isset($request->param()['rid']))
        {
            $res = CommentReply::where('id',$data['rid'])->inc('like_count',1)->update();

            if($res){
                $msg['code']=1;
                return json($msg);
            }else{
                $msg['code']=0;
                return json($msg);
            }


        }


    }

    /**文章分类页**/
    public function types(Request $request)
    {


        if(isset($request->param()['type']) && isset($request->param()['order'])) {
            $data = $request->param();
            if($data['order']=='time')
            {
                $list = ArticleModel::where(['status'=>1,'type'=>$data['type']])->order('top desc,create_time desc')->with('Types')->paginate([
                    'list_rows' =>  10,
                    'query' => request()->param()
                ]);


            }elseif ($data['order']=='view')
            {
                $list = ArticleModel::where(['status'=>1,'type'=>$data['type']])->order('top desc,browsing desc')->with('Types')->paginate([
                    'list_rows' =>  10,
                    'query' => request()->param()
                ]);


            }elseif ($data['order']=='comment')
            {
                $list = ArticleModel::where(['status'=>1,'type'=>$data['type']])->order('top desc,comment_count desc')->with('Types')->paginate([
                    'list_rows' =>  10,
                    'query' => request()->param()
                ]);

            }

        }elseif(isset($request->param()['type'])) {
            $data = $request->param();
            $list = ArticleModel::where(['status'=>1,'type'=>$data['type']])->order('top desc,create_time desc')->with('Types')->paginate([
                'list_rows' =>  10,
                'query' => request()->param()
            ]);
        }else{
            if(isset($request->param()['order']))
            {
                $data=$request->param();

                if($data['order']=='time')    //当没有选择类型而选择了评论排序的情况
                {
                    $list = ArticleModel::where('status',1)->order('top desc,create_time desc')->with('Types')->paginate([
                        'list_rows' =>  10,
                        'query' => request()->param()
                    ]);


                }elseif ($data['order']=='view')    //当没有选择类型而选择了浏览排序的情况
                {

                    $list = ArticleModel::where('status',1)->order('top desc,browsing desc')->with('Types')->paginate([
                        'list_rows' =>  10,
                        'query' => request()->param()
                    ]);


                }elseif ($data['order']=='comment')   //当没有选择类型而选择了评论排序的情况
                {
                    $list = ArticleModel::where('status',1)->order('top desc,comment_count desc')->with('Types')->paginate([
                        'list_rows' =>  10,
                        'query' => request()->param()
                    ]);

                }
            }else{
                $list = ArticleModel::where('status',1)->order('top desc,create_time desc')->with('Types')->paginate([
                    'list_rows' =>  10,
                    'query' => request()->param()
                ]);
            }
        }


        return view('types',[
            'Recommend' => ArticleModel::where('top',2)->order('create_time','desc')->limit(6)->with('Types')->select(),
            'mulu'  => ArticleType::with('articlecount')->select(),     //分类目录

            'list'   => $list,
            'newslist' =>   ArticleModel::order('create_time','desc')->with('Types')->limit(7)->select(),
            'toplist'   => ArticleModel::where('top',1)->order('create_time','desc')->select(),
            'commentlist'   =>  Comment::order('create_time','desc')->limit(8)->with(['users','cids'])->select(),
            'articlerep'   => ArticleModel::order('comment_count','desc')->limit(4)->select(),  //热评文章top5
            'articletop'    => ArticleModel::order('browsing','desc')->limit(3)->select(),  //热门文章top3
            'friendlink'    => FriendLinks::select()
        ]);
    }


    /**搜索结果展示**/
    public function searchlist(Request $request)
    {
        $data = $request->param();

        $res['id']=array(2,3,4);
        return view('searchlist',[
            'Recommend' => ArticleModel::where('top',2)->order('create_time','desc')->limit(6)->with('Types')->select(),
            'mulu'  => ArticleType::with('articlecount')->select(),     //分类目录
            'typelist'=> ArticleType::with('articles')->where($res)->select(),  //分类展示模块数据，分别展示id为2，3，4的分类
            'list'   => ArticleModel::withSearch('title',[
                'title' => request()->param('key')
            ])->order('top desc,create_time desc')->with('Types')->paginate([
                'list_rows' =>  10,
                'query' => request()->param()
            ]), //文章垂直列表数据
            'toplist'   => ArticleModel::where('top',1)->order('create_time','desc')->select(),
            'commentlist'   =>  Comment::order('create_time','desc')->limit(8)->with(['users','cids'])->select(),
            'articlerep'   => ArticleModel::order('comment_count','desc')->limit(5)->select(),  //热评文章top5
            'articletop'    => ArticleModel::order('browsing','desc')->limit(3)->select(),  //热门文章top3
            'friendlink'    => FriendLinks::select(),
            'searchkey'           => $data['key']
        ]);
    }

    /**删除文章**/
    public function delarticle(Request $request)
    {
        $data = $request->param();

        $sul = ArticleModel::where('id',$data['id'])->find();

        if($sul->getData('uid') == session('uid'))
        {
            ArticleModel::where('id',$data['id'])->delete();
            $msg['code'] = 1;
            $msg['msg'] = '删除成功!';
            return json($msg);
        }else{
            $msg['code']=0;
            $msg['msg']='调皮了嗷，怎么能删除别人的文章呢！';
            return json($msg);
        }
    }



}