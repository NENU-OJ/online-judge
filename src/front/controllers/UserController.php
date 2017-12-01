<?php
/**
 * Created by PhpStorm.
 * User: shihao
 * Date: 17-8-15
 * Time: 下午7:48
 */

namespace app\controllers;

use app\models\User;
use yii\web\NotFoundHttpException;
use app\common\Util;

class UserController extends BaseController {

    public function actionDetail() {

        $id = \Yii::$app->request->get('id', -1);
        if ($id == -1) {
            if (isset(\Yii::$app->session['user_id']))
                $id = \Yii::$app->session['user_id'];
        }
        $user = User::findById($id);
        if (!$user)
            throw new NotFoundHttpException("无效的user_id");

        $solved = [1, 2, 3, 4, 5];
        $unsolved = [6, 7, 8, 9, 10];
        $submissions = 64;

        $this->smarty->assign('user', $user);
        $this->smarty->assign('solved', $solved);
        $this->smarty->assign('unsolved', $unsolved);
        $this->smarty->assign('submissions', $submissions);

        return $this->smarty->display('user/user.html');
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
                \Yii::$app->session['avatar'] = $user->avatar;
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
        if (\Yii::$app->request->isGet) {
            if (isset($_SESSION["user_id"])) {
                $this->smarty->assign('msg', "已经登录了就别注册了!");
                return $this->smarty->display('common/error.html');
            } else {
                return $this->smarty->display('user/register.html');
            }
        } else if (\Yii::$app->request->isAjax) {
            $username = trim(\Yii::$app->request->post('username'));

            // 用户名是否重复
            if (User::findByUsername($username))
                return json_encode(["code" => 1, "data" => "该用户名已被使用"]);

            $user = new User();
            $user->username = $username;
            $user->nickname = trim(\Yii::$app->request->post('nickname'));

            $defaultAvatars = Util::getDirs(\Yii::$app->params['uploadsDir'].'/avatar/default');
            $user->avatar = 'default/'.$defaultAvatars[array_rand($defaultAvatars, 1)];

            $user->password = md5(trim(\Yii::$app->request->post('password')));
            $user->email = trim(\Yii::$app->request->post('email'));
            $user->school = trim(\Yii::$app->request->post('school'));
            $user->signature = trim(\Yii::$app->request->post('signature'));
            $user->register_time = date("Y-m-d H:i:s");
            $user->ip_addr = $_SERVER['REMOTE_ADDR'];
            $code = 0;
            $data = "";
            try {
                // 注册并且保留登录状态
                $user->save();
                \Yii::$app->session['user_id'] = $user->id;
                \Yii::$app->session['username'] = $user->username;
                \Yii::$app->session['nickname'] = $user->nickname;
                \Yii::$app->session['avatar'] = $user->avatar;
                \Yii::$app->session['email'] = $user->email;
                \Yii::$app->session['school'] = $user->school;
            } catch (\yii\db\Exception $e) {
                $code = 1;
                $data = "超长";
            }
            return json_encode(["code" => $code, "data" => $data]);
        } else {
            return json_encode(["code" => 1, "data" => "请求方式错误"]);
        }
    }

    public function actionUpdate() {
        if (!isset(\Yii::$app->session["user_id"]))
            return json_encode(["code" => 1, "data" => "未知用户"]);
        $id = \Yii::$app->session["user_id"];
        $user = User::findById($id);

        if ($user->password != md5(\Yii::$app->request->post('old_password')))
            return json_encode(["code" => 1, "data" => "旧密码错误"]);

        if (\Yii::$app->request->post('new_password') != "")
            $user->password = md5(\Yii::$app->request->post('new_password'));

        $user->nickname = \Yii::$app->request->post('nickname');
        $user->school = \Yii::$app->request->post('school');
        $user->email = \Yii::$app->request->post('email');
        $user->signature = \Yii::$app->request->post('signature');

        try {
            $user->update();
            return json_encode(["code" => 0, "data" => ""]);
        } catch (\yii\db\Exception $e) { // 上传的数据过大
            return json_encode(["code" => 1, "data" => "超长"]);
        }
    }

    public function actionAvatar() {
        if (\Yii::$app->request->isGet) {
            if (!isset(\Yii::$app->session['user_id'])) {
                $this->smarty->assign('msg', "请先登录");
                return $this->smarty->display('common/error.html');
            }
            else {
                return $this->smarty->display('user/avatar.html');
            }
        } else if (\Yii::$app->request->isPost) {
            return "avatar post";
        }
    }

}