<?php
/**
 * Created by PhpStorm.
 * User: shihao
 * Date: 17-8-15
 * Time: 下午7:48
 */

namespace app\controllers;


use yii\web\Controller;

class IndexController extends CController
{
    // 加载函数
    public function actionIndex()
    {
        //return $this->render('index');
        $this->smarty->assign('name',"holy shit!");
        $str=dirname(__FILE__).DIRECTORY_SEPARATOR.'..';
        $this->smarty->assign('str',$str);
        $this->smarty->display('index.html');
    }
}