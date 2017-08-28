<?php
/**
 * Created by PhpStorm.
 * User: shihao
 * Date: 17-8-15
 * Time: 下午7:48
 */

namespace app\controllers;

use Yii;
use app\models\User;

class UserController extends CController
{
    // 加载函数
    public function actionIndex() {
//        return User::attributes();
        return print_r(User::findBySql("select * t_user")->all());
//        $this->smarty->assign('isGuest', Yii::$app->user->isGuest);
//        $this->smarty->display('user/user.html');
    }
}