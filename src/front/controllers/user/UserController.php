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

    public function actionIndex() {
        $userId=\Yii::$app->session['user_id'];
        $basicInfo=User::findOne($userId);
        $acList=Status::find()
            ->select('problem_id')
            ->where('id=:userId and result LIKE %Accepted% ',['userId'=>$userId])
            ->distinct()
            ->asArray()
            ->all();
        $this->smarty->assign('basicInfo',$basicInfo);
        $this->smarty->assign('acList',$acList);
        $this->smarty->display('user/user.html');
    }

    public function actionToLogin(){
        $this->smarty->display('user/login.html');
    }

    public function actionLogin(){
        $username=trim($_GET['username']);
        $password=md5(trim($_GET['password']));
        $_user=User::findByUsername($username);
        if($_user->password==$password){
            \Yii::$app->session['user_id']=$_user->id;
            \Yii::$app->session['nickname']=$_user->nickname;
            \Yii::$app->session['ip_addr']=$_user->ip_addr;
            $_user->ip_addr=$_SERVER['REMOTE_ADDR'];
            $_user->update();
            $list='[{"code":0,"data":""}]';
            print $list;
        }else{
            $list = '[{"code":1,"data":""}]';
            print $list;
        }
    }

    public function actionLogout(){
        unset(\Yii::$app->session['user_id']);
        unset(\Yii::$app->session['nickname']);
        $list='[{"code":0,"data":""}]';
        print $list;
    }

    public function actionToRegister(){
        $this->smarty->display('user/register.html');
    }

    public function actionRegister(){
        if (User::findByUsername(trim($_GET['username']))){
            $list = '[{"code":1,"data":""}]';
            print $list;
        }else {
            $user = new User();
            $user->username=trim($_GET['username']);
            $user->nickname=trim($_GET['nickname']);
            $user->password=md5(trim($_GET['password']));
            $user->register_time=date("Y-m-d h:i:sa");
            $user->ip_addr=$_SERVER['REMOTE_ADDR'];
            if (isset($_GET['email'])){
                $user->email=$_GET['email'];
            }
            if (isset($_GET['school'])){
                $user->school=$_GET['school'];
            }
            $user->save();
            $list='[{"code":0,"data":""}]';
            print $list;
        }
    }

    public function actionUpdate(){
        $user=User::findByUsername(\Yii::$app->session['username']);
        if ($_GET['nickname']!=$user->nickname){
            $user->nickname=$_GET['nickname'];
        }
        if ($_GET['email']!=$user->email){
            $user->email=$_GET['email'];
        }
        if ($_GET['school']!=$user->school){
            $user->school=$_GET['school'];
        }
        $user->update();
        $list='[{"code":0,"data":""}]';
        print $list;
    }

    public function actionDetail(){
        $this->smarty->display('user/user.html');
    }

}