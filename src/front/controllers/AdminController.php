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

        return $this->smarty->display('admin/admin.html');
    }
}