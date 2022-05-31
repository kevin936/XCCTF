<?php


namespace app\index\model;
use think\model;

class ArticleType  extends model
{
    public function articles()
    {
        return $this->hasMany('Article','type','id')->limit(6);
    }

    public function articlecount()
    {
        return $this->hasMany('Article','type','id');
    }


}