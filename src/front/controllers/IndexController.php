<?php
/**
 * Created by PhpStorm.
 * User: shihao
 * Date: 17-8-15
 * Time: 下午7:48
 */

namespace app\controllers;

class IndexController extends BaseController {
    // 加载函数
    public function actionIndex() {
        $this->smarty->display('index.html');
    }

    public function actionWarn($msg) {
        $this->smarty->assign('msg', $msg);
        $this->smarty->display('common/error.html');
    }

    public function actions() {
        return ['error' => ['class' => 'yii\web\ErrorAction',]];
    }
}