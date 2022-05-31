<?php


namespace app\index\model;

use think\Model;
use app\index\model\Chatype;

class Challenge extends Model
{

    public function getTopicdifficultyAttr($value)
    {
        $data=[1=>'★☆☆',2=>'★★☆',3=>'★★★'];
        return $data[$value];

    }
    public function getTopictypeAttr($value)
    {
        if($value != null) {
            $data = Chatype::field('type')->where('id', $value)->find();
            return $data['type'];
        }
    }

    public function getTop1Attr($value)
    {

            $data = Users::field('username,img')->where('id',$value)->find();

            return $data;

    }

    public function getTop2Attr($value)
    {

            $data = Users::field('username,img')->where('id',$value)->find();

            return $data;
    }

    public function getTop3Attr($value)
    {

            $data = Users::field('username,img')->where('id',$value)->find();

            return $data;
    }

}