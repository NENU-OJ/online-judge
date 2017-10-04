<?php
/**
 * Created by PhpStorm.
 * User: shihao
 * Date: 17-8-15
 * Time: 下午7:48
 */

namespace app\controllers;


class IndexController extends CController
{
    // 加载函数
    public function actionIndex()
    {
        $this->smarty->display('index.html');
    }

    public function actionError($msg){
        $this->smarty->assign('msg',$msg);
        $this->smarty->display('common/error.html');
    }
}