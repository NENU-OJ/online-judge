<?php
/**
 * Created by PhpStorm.
 * User: shihao
 * Date: 17-8-15
 * Time: 下午7:48
 */

namespace app\controllers;

class IndexController extends BaseController {

    public function actionIndex() {
        $this->smarty->display('index.html');
    }

    public function actionError() {
        return $this->smarty->display('common/error.html');
    }
}