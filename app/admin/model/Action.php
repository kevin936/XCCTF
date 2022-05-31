<?php

namespace app\admin\model;

use app\common\model\BaseModel;


class Action extends BaseModel
{

    

    

    // 表名
    protected $name = 'action';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'action_type_text'
    ];
    

    
    public function getActionTypeList()
    {
        return ['0' => __('Action_type 0'), '1' => __('Action_type 1')];
    }


    public function getActionTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['action_type']) ? $data['action_type'] : '');
        $list = $this->getActionTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
