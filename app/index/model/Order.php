<?php


namespace app\index\model;


use think\Model;

class Order extends Model
{
    public function users()
    {
        return $this->hasOne('Users','id','uid')->bind(['username']);
    }
    public function getCreatetimeAttr($value)
    {
        date_default_timezone_set("Asia/Shanghai");
        $the_time = $value;
        $now_time = date("Y-m-d H:i:s", time());
        //echo $now_time;
        $now_time = strtotime($now_time);
        $show_time = strtotime($the_time);
        $dur = $now_time - $show_time;
        if ($dur < 0) {
            return $the_time;
        } else {
            if ($dur < 60) {
                return '刚刚';
            } else {
                if ($dur < 3600) {
                    return floor($dur / 60) . '分钟前';
                } else {
                    if ($dur < 86400) {
                        return floor($dur / 3600) . '小时前';
                    } else {
                        if ($dur < 2592000) {//30天内
                            return floor($dur / 86400) . '天前';
                        } else {
                            if ($dur < 31536000) {//12个月内
                                return floor($dur / 2592000) . '月前';
                            } else {
                                if ($dur > 31536000) {//大于12个月
                                    return floor($dur / 31536000) . '年前';
                                } else {
                                    return $the_time;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

}