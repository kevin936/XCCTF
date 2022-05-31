<?php


namespace app\common\model;
use think\Model;

class Teams extends Model
{

    //关联teamusers模型查询内容
    public function teamusers()
    {
        return $this->hasMany(Teamusers::class,'zid');

    }

    //status获取器
    public function getTeamstatusAttr($value)
    {

        $status = [0 => '待审核',1 => '正常',2 =>'封禁'];
        return $status[$value];
    }

    //team_name搜索器
    public function searchTeamnameAttr($query,$value)
    {
        return $value ? $query->where('team_name','like','%'.$value.'%'):'';
    }

    //badge获取器（虚拟字段）
    public function getBadgeAttr($value,$data)  //$data可以获取所有数据
    {
        //return $data['status'] ? 'success' ? 'secondary':'danger';
        if($data['team_status']==0)
        {
            $tag = 'secondary';

        }elseif($data['team_status']==1)
        {
            $tag = 'success';
        }elseif($data['team_status']==2){
            $tag = 'danger';
        }
        return $tag;
    }
    public function getTeamleaderAttr($value)
    {
        $data = Users::field('username')->where('id',$value)->find();
        return $data['username'];
    }

    public function getTeam1Attr($value)
    {
        $data = Users::field('username')->where('id',$value)->find();
        return $data['username'];
    }
    public function getTeam2Attr($value)
    {
        $data = Users::field('username')->where('id',$value)->find();
        return $data['username'];
    }
    public function getTeam3Attr($value)
    {
        $data = Users::field('username')->where('id',$value)->find();
        return $data['username'];
    }
    public function getTeam4Attr($value)
    {
        $data = Users::field('username')->where('id',$value)->find();
        return $data['username'];
    }
    public function getTeam5Attr($value)
    {
        $data = Users::field('username')->where('id',$value)->find();
        return $data['username'];
    }
    public function getTeam6Attr($value)
    {
        $data = Users::field('username')->where('id',$value)->find();
        return $data['username'];
    }
    public function getTeam7Attr($value)
    {
        $data = Users::field('username')->where('id',$value)->find();
        return $data['username'];
    }
    public function getTeam8Attr($value)
    {
        $data = Users::field('username')->where('id',$value)->find();
        return $data['username'];
    }
    public function getTeam9Attr($value)
    {
        $data = Users::field('username')->where('id',$value)->find();
        return $data['username'];
    }
    public function getTeam10Attr($value)
    {
        $data = Users::field('username')->where('id',$value)->find();
        return $data['username'];
    }
}