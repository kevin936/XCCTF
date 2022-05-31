<?php


namespace app\index\model;
use think\Model;
class Teamusers extends Model
{

    public function users()
    {
        return $this->hasOne('Users','username','cid')->bind(['frame_img','img']);
    }

    public function getCidAttr($value)
    {

        $data = Users::field('username')->where('id',$value)->find();
        return $data['username'];
    }
    public function getStatusAttr($value)
    {
        $data = [0=>'待审核',1=>'正常'];
        return $data[$value];
    }
    public function getpositionAttr($value)
    {
        $data = [0=>'成员',1=>'副队长',2=>'队长'];
        return $data[$value];
    }
    public function getScoreAttr($value)
    {
        $data=Users::where('id',$value)->find();
        return $data['score'];
    }

}