<?php
/**
 * Created by PhpStorm.
 * User: shihao
 * Date: 17-8-15
 * Time: 下午7:48
 */

namespace app\controllers\user;

use app\controllers\CController;
use app\models\Status;
use app\models\User;

class UserController extends CController
{

//    public function behaviors()
//    {
//        return [
//            [
//                'class' => Filter::className(),
//                //控制哪些动作需要过滤器
//                'only' => ['submit','discuss'],
//            ]
//        ];
//    }

    public function actionDetail($userId = 0)
    {
//        print_r($userId);
        $isSelf = false;
        if ($userId == 0) {
            $userId = \Yii::$app->session['user_id'];
            $isSelf = true;
        } else if ($userId == \Yii::$app->session['user_id']) {
            $isSelf = true;
        }
        $basicInfo = User::findOne($userId);
        $acList = Status::find()
            ->select('problem_id')
            ->where('user_id=:userId and result LIKE :result ', [':userId' => $userId, ':result' => '%' . 'Accepted' . '%'])
            ->distinct()
            ->asArray()
            ->all();
        $this->smarty->assign('basicInfo', $basicInfo);
        $this->smarty->assign('acList', $acList);
        $this->smarty->assign('isSelf', $isSelf);
        $this->smarty->display('user/user.html');
    }

    public function actionLogin()
    {
        $username = trim($_GET['username']);
        $password = md5(trim($_GET['password']));
        $_user = User::findByUsername($username);
        if ($_user->password == $password) {
            \Yii::$app->session['user_id'] = $_user->id;
            \Yii::$app->session['username'] = $_user->username;
            \Yii::$app->session['nickname'] = $_user->nickname;
            \Yii::$app->session['ip_addr'] = $_user->ip_addr;
            \Yii::$app->session['email'] = $_user->email;
            \Yii::$app->session['school'] = $_user->school;
            $_user->ip_addr = $_SERVER['REMOTE_ADDR'];
            $_user->update();
            $list = '[{"code":0,"data":""}]';
            print $list;
        } else {
            $list = '[{"code":1,"data":""}]';
            print $list;
        }
    }

    public function actionLogout()
    {
        unset(\Yii::$app->session['user_id']);
        unset(\Yii::$app->session['username']);
        unset(\Yii::$app->session['nickname']);
        unset(\Yii::$app->session['ip_addr']);
        unset(\Yii::$app->session['email']);
        unset(\Yii::$app->session['school']);
        $list = '[{"code":0,"data":""}]';
        print $list;
    }

    public function actionRegister()
    {
        if (User::findByUsername(trim($_GET['username']))) {
            $list = '[{"code":1,"data":""}]';
            print $list;
        } else {
            $user = new User();
            $user->username = trim($_GET['username']);
            $user->nickname = trim($_GET['nickname']);
            $user->password = md5(trim($_GET['password']));
            $user->register_time = date("Y-m-d h:i:sa");
            $user->ip_addr = $_SERVER['REMOTE_ADDR'];
            $user->email = $_GET['email'];
            $user->school = $_GET['school'];
            $user->save();
            $list = '[{"code":0,"data":""}]';
            print $list;
        }
    }

    public function actionUpdate()
    {
        $user = User::findByUsername(trim($_GET['username']));
        if (isset($_GET['oldPassword'])) {
            $oldPassword = md5(trim($_GET['oldPassword']));
            if ($user->password == $oldPassword) {
                $user->nickname = trim($_GET['nickname']);
                $user->email = trim($_GET['email']);
                $user->school = trim($_GET['school']);
                $user->password = md5(trim($_GET['newPassword']));
                $user->update();
                $list = '[{"code":0,"data":""}]';
                print $list;
            } else {
                $list = '[{"code":1,"data":""}]';
                print $list;
            }
        } else {
            $user->nickname = trim($_GET['nickname']);
            $user->email = trim($_GET['email']);
            $user->school = trim($_GET['school']);
            $user->update();
            $list = '[{"code":0,"data":""}]';
            print $list;
        }
    }
}