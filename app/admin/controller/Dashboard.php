<?php
/**
 * *
 *  * ============================================================================
 *  * Created by PhpStorm.
 *  * User: Ice
 *  * 邮箱: ice@sbing.vip
 *  * 网址: https://sbing.vip
 *  * Date: 2019/9/19 下午3:33
 *  * ============================================================================.
 */

namespace app\admin\controller;

use app\admin\model\Admin;
use app\admin\model\User;
use app\common\model\Attachment;
use fast\Date;
use think\facade\Config;
use app\common\controller\Backend;
use think\facade\Db;

/**
 * 控制台.
 *
 * @icon fa fa-dashboard
 * @remark 用于展示当前系统中的统计数据、统计报表及重要实时数据
 */
class Dashboard extends Backend
{
    /**
     * 查看
     */
    public function index()
    {
        try {
            Db::execute("SET @@sql_mode='';");
        } catch (\Exception $e) {

        }
        $column = [];
        $starttime = Date::unixtime('day', -6);
        $endtime = Date::unixtime('day', 0, 'end');
        $joinlist = Db::name("users")->where('create_time', 'between time', [$starttime, $endtime])
            ->field('create_time, status, COUNT(*) AS nums, DATE_FORMAT(FROM_UNIXTIME(create_time), "%Y-%m-%d") AS join_date')
            ->group('join_date')
            ->select();
        for ($time = $starttime; $time <= $endtime;) {
            $column[] = date("Y-m-d", $time);
            $time += 86400;
        }
        $userlist = array_fill_keys($column, 0);
        foreach ($joinlist as $k => $v) {
            $userlist[$v['join_date']] = $v['nums'];
        }

        $dbTableList = Db::query("SHOW TABLE STATUS");
        $this->view->assign([
            'totaluser'       => \app\admin\model\Users::count(),
            'totalanswer'     => \app\admin\model\Answer::count(),
            'totalteam'       => \app\admin\model\Teams::count(),
            'totalorder'      => \app\admin\model\Order::count(),
            'todayusersignup' => \app\admin\model\Users::whereTime('create_time', 'today')->count(),
            'todayuserlogin'  => \app\admin\model\Users::whereTime('login_time', 'today')->count(),
            'sevendau'        => \app\admin\model\Users::whereTime('create_time|login_time|prev_time', '-7 days')->count(),
            'thirtydau'       => \app\admin\model\Users::whereTime('create_time|login_time|prev_time', '-30 days')->count(),
            'threednu'        => \app\admin\model\Users::whereTime('create_time', '-3 days')->count(),
            'sevendnu'        => \app\admin\model\Users::whereTime('create_time', '-7 days')->count(),
            'dbtablenums'     => count($dbTableList),
            'dbsize'          => array_sum(array_map(function ($item) {
                return $item['Data_length'] + $item['Index_length'];
            }, $dbTableList)),
            'attachmentnums'  => Attachment::count(),
            'attachmentsize'  => Attachment::sum('filesize'),
            'picturenums'     => Attachment::where('mimetype', 'like', 'image/%')->count(),
            'picturesize'     => Attachment::where('mimetype', 'like', 'image/%')->sum('filesize'),
        ]);

        $this->assignconfig('column', array_keys($userlist));
        $this->assignconfig('userdata', array_values($userlist));

        return $this->view->fetch();
    }
}
