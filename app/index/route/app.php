<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;


Route::get('hello/:name', 'index/hello');

//index前端路由组


    //Route::rule('/','index/Index/index');
    //Route::rule('/:name$','Index/:name');

    Route::rule('teams','Index/teams');
    Route::rule('usertop','Index/usertop');
    Route::rule('teams','Index/teams');
    Route::rule('teamtop','Index/teamtop');
    Route::rule('login','Login/index');
    Route::rule('teamtopdata','Index/teamtopdata');
    Route::rule('usertopdata','Index/usertopdata');
    Route::rule('noticedetail/id/:id','Index/noticedetail');





//用户组

Route::group(function (){
    //用户模块路由
    //Route::resource('users','Users');
    Route::rule('teamgrid','Index/teamgrid');
    Route::rule('teamlist','Index/teamlist');
    Route::rule('teaminfo/id/:id','Index/teaminfo');
    Route::get('users/:name','Users/:name');
    Route::post('users/:name','Users/:name');
    Route::rule('store/index','Store/index');
    Route::rule('users/challenge/:tid','Users/challenge');
    Route::get('users/info','Users/info');
    Route::get('users/action','Users/action');
    Route::get('users/setinfo','Users/setinfo');
    Route::rule('store/create/id/:id','Store/create');
    Route::rule('users/order','Users/order');
    Route::rule('store/shouhuo','Store/shouhuo');
    Route::get('users/delorder','Users/delorder');


})->middleware(function ($request, Closure $next){
    if(!session('users')){
        return redirect('/login');
    }
    return $next($request);
});

//登入模块路由
Route::group(function(){
    Route::get('login','index/login')->middleware(function ($request,Closure $next){
        if(session('?users')){
            return redirect('/');
        }
        return $next($request);
    });
    Route::post('checklogin','index/Login/checklogin');
});

//注册模块路由
Route::group(function(){
    Route::rule('register','index/Register/index');
    Route::rule('checkusername','index/Register/checkusername');
    Route::rule('createname','index/Register/createname');
});







