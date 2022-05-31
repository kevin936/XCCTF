<?php

namespace app\admin\model;

use app\common\model\BaseModel;


class Store extends BaseModel
{

    

    

    // 表名
    protected $name = 'store';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'tip_text',
        'type_text'
    ];
    

    
    public function getTipList()
    {
        return ['正品保证' => __('正品保证'), '顺丰包邮' => __('顺丰包邮'), '闪电发货' => __('闪电发货'), '包邮到家' => __('包邮到家'), '七天无理由退货' => __('七天无理由退货')];
    }

    public function getTypeList()
    {
        return ['0' => __('Type 0'), '1' => __('Type 1'), '2' => __('Type 2')];
    }


    public function getTipTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['tip']) ? $data['tip'] : '');
        $valueArr = explode(',', $value);
        $list = $this->getTipList();
        return implode(',', array_intersect_key($list, array_flip($valueArr)));
    }


    public function getTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['type']) ? $data['type'] : '');
        $list = $this->getTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    protected function setTipAttr($value)
    {
        return is_array($value) ? implode(',', $value) : $value;
    }


}
