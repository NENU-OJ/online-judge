<?php
/**
 * Created by PhpStorm.
 * User: shihao
 * Date: 17-8-15
 * Time: 下午7:48
 */

namespace app\controllers;

use app\models\Status;
use app\models\User;

class UserController extends BaseController {

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

    public function actionDetail($id = 0) {
        //        print_r($userId);
        $isSelf = false;
        if ($id == 0) {
            $id = \Yii::$app->session['user_id'];
            $isSelf = true;
        } else if ($id == \Yii::$app->session['user_id']) {
            $isSelf = true;
        }
        $basicInfo = User::findOne($id);
        $acList = Status::find()->select('problem_id')->where('user_id=:userId and result LIKE :result ', [':userId' => $id, ':result' => '%' . 'Accepted' . '%'])->distinct()->asArray()->all();
        $this->smarty->assign('basicInfo', $basicInfo);
        $this->smarty->assign('acList', $acList);
        $this->smarty->assign('isSelf', $isSelf);
        $this->smarty->display('user/user.html');
    }

    public function actionLogin() {
        $code = 0;
        $data = "";

        if (\Yii::$app->request->isAjax) {
            $username = trim(\Yii::$app->request->post('username'));
            $password = md5(trim(\Yii::$app->request->post('password')));
            $user = User::findByUsername($username);
            if (!$user) {
                $code = 1;
                $data = "没有这个用户";
            } else if($user->password != $password) {
                $code = 1;
                $data = "密码错误";
            } else {
                $user->ip_addr = $_SERVER['REMOTE_ADDR'];
                \Yii::$app->session['user_id'] = $user->id;
                \Yii::$app->session['username'] = $user->username;
                \Yii::$app->session['nickname'] = $user->nickname;
                \Yii::$app->session['email'] = $user->email;
                \Yii::$app->session['school'] = $user->school;
                $user->update();
            }
        } else {
            $code = 1;
            $data = "请求方式错误";
        }
        return json_encode(["code" => $code, "data" => $data]);
    }

    public function actionLogout() {
        unset(\Yii::$app->session['user_id']);
        unset(\Yii::$app->session['username']);
        unset(\Yii::$app->session['nickname']);
        unset(\Yii::$app->session['ip_addr']);
        unset(\Yii::$app->session['email']);
        unset(\Yii::$app->session['school']);
        return json_encode(["code" => 0, "data" => ""]);
    }

    public function actionRegister() {
        if (User::findByUsername(trim($_GET['username']))) {
            $list = '[{"code":1,"data":""}]';
            print $list;
        } else {
            $user = new User();
            $user->username = trim($_GET['username']);
            $user->nickname = trim($_GET['nickname']);
            $user->password = md5(trim($_GET['password']));
            $user->register_time = date("Y-m-d H:i:s"); // TODO date check
            $user->ip_addr = $_SERVER['REMOTE_ADDR'];
            $user->email = $_GET['email'];
            $user->school = $_GET['school'];
            $user->save();
            $list = '[{"code":0,"data":""}]';
            print $list;
        }
    }

    public function actionUpdate() {
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