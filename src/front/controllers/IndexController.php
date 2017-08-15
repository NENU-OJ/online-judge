<?php
/**
 * Created by PhpStorm.
 * User: shihao
 * Date: 17-8-15
 * Time: 下午7:48
 */

namespace app\controllers;


use yii\web\Controller;

class IndexController extends Controller
{
    // 主页
    public function actionIndex()
    {
        //return $this->render('index');
        $smarty = \Yii::$app->smarty;
        $smarty->assign('name',"holy shit!");
        $str="fuck smarty!";
        $smarty->assign('str',$str);
        $smarty->display('index.html');
    }
}