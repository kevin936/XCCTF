<?php

namespace app\admin\model;

use app\common\model\BaseModel;


class Article extends BaseModel
{

    

    

    // 表名
    protected $name = 'article';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'status_text',
        'top_text'
    ];
    

    
    public function getStatusList()
    {
        return ['0' => __('Status 0'), '1' => __('Status 1')];
    }

    public function getTopList()
    {
        return ['0' => __('Top 0'), '1' => __('Top 1'), '2' => __('Top 2')];
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getTopTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['top']) ? $data['top'] : '');
        $list = $this->getTopList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
