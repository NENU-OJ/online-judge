<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\User;

/**
 * Class BaseController
 * @package app\controllers
 * 继承自Controller，加入控制器通用特性
 */

class BaseController extends Controller {
    protected $smarty = null;
    private $startTime = null;

    //初始化函数，渲染通用常量到前端
    public function init() {
        parent::init();

        $this->startTime = microtime(true);

        $this->smarty = \Yii::$app->smarty;
        //向前端渲染域名（方便跳页和行为触发）
        $this->smarty->assign('website', "//".$_SERVER['HTTP_HOST']);
        //向前端渲染资源目录
        $this->smarty->assign('staticWebsite', "//".$_SERVER['HTTP_HOST']."/assets/resources");
        $this->smarty->assign('uploadsDir', "//".$_SERVER['HTTP_HOST']."/uploads");

        $timeNow = time();
        $this->smarty->assign('time', date("Y-m-d H:i:s", $timeNow));
        $this->smarty->assign('timeNowSecond', $timeNow);
        $this->smarty->assign('startTime', $this->startTime);

        //若用户已登录，向前端渲染用户名方便判断哪些按钮显示和哪些不显示
        if (isset(\Yii::$app->session['user_id'])) {
            $isRoot = User::isRoot(\Yii::$app->session['user_id']);

            $this->smarty->assign("isRoot", $isRoot);
            $this->smarty->assign('user_id', \Yii::$app->session['user_id']);
            $this->smarty->assign('username', \Yii::$app->session['username']);
            $this->smarty->assign('nickname', \Yii::$app->session['nickname']);
            $this->smarty->assign('avatar', \Yii::$app->session['avatar']);
            $this->smarty->assign('email', \Yii::$app->session['email']);
            $this->smarty->assign('school', \Yii::$app->session['school']);
        }
    }
}