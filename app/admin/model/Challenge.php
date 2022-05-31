<?php

namespace app\admin\model;

use app\common\model\BaseModel;


class Challenge extends BaseModel
{

    

    

    // 表名
    protected $name = 'challenge';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'topic_difficulty_text',
        'topic_status_text'
    ];
    

    
    public function getTopicDifficultyList()
    {
        return ['1' => __('Topic_difficulty 1'), '2' => __('Topic_difficulty 2'), '3' => __('Topic_difficulty 3')];
    }

    public function getTopicStatusList()
    {
        return ['0' => __('Topic_status 0'), '1' => __('Topic_status 1')];
    }


    public function getTopicDifficultyTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['topic_difficulty']) ? $data['topic_difficulty'] : '');
        $list = $this->getTopicDifficultyList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getTopicStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['topic_status']) ? $data['topic_status'] : '');
        $list = $this->getTopicStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
