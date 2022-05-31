<?php


namespace app\index\model;

use app\index\model\Teams as TeamsModel;
use think\Model;

class Users extends Model
{
    /*
    public function teamusers()
    {

        return $this->hasOne(Teamusers::class,$this->getData('cid'));
    }*/

    public function getCreatetimeAttr($value)
    {
        return date("Y-m-d", strtotime($value) );
    }

    public function getTeamidAttr($value)
    {
        $data = TeamsModel::where('id',$value)->find();
        return $data['team_name'];

    }

}