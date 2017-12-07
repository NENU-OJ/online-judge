<?php
/**
 * Created by PhpStorm.
 * User: torapture
 * Date: 17-12-7
 * Time: 下午7:25
 */

namespace app\controllers;


class AdminController extends BaseController {
    public function actionIndex() {

        $this->smarty->assign('msg', "还没写");
        return $this->smarty->display('common/error.html');
    }
}