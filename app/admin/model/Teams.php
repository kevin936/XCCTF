<?php

namespace app\admin\model;

use app\common\model\BaseModel;


class Teams extends BaseModel
{

    

    

    // 表名
    protected $name = 'teams';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'team_status_text'
    ];
    

    
    public function getTeamStatusList()
    {
        return ['0' => __('Team_status 0'), '1' => __('Team_status 1'), '2' => __('Team_status 2')];
    }


    public function getTeamStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['team_status']) ? $data['team_status'] : '');
        $list = $this->getTeamStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
