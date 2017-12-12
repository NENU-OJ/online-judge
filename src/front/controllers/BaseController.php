<?php
/**
 * Created by PhpStorm.
 * User: shihao
 * Date: 17-8-15
 * Time: 下午11:02
 */

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

    //初始化函数，渲染通用常量到前端
    public function init() {
        parent::init();

        $this->smarty = \Yii::$app->smarty;
        //向前端渲染域名（方便跳页和行为触发）
        $this->smarty->assign('website', "http://".$_SERVER['HTTP_HOST']);
        //向前端渲染资源目录
        $this->smarty->assign('staticWebsite',
            "http://".\Yii::$app->request->serverName.":".\Yii::$app->request->serverPort."/assets/resources");
        $this->smarty->assign('uploadsDir',
            "http://".\Yii::$app->request->serverName.":".\Yii::$app->request->serverPort."/uploads");
        $this->smarty->assign('time', date("Y-m-d H:i:s"));
        //若用户已登录，向前端渲染用户名方便判断哪些按钮显示和哪些不显示

        if (isset(\Yii::$app->session['user_id'])) {
            $isRoot = User::find()
                ->select('is_root')
                ->where(['id' => \Yii::$app->session['user_id']])
                ->one()
                ->is_root;

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