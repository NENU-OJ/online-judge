<?php

namespace app\controllers;

use app\common\Util;

class ContestController extends BaseController {

    public function actionIndex() {
        return $this->smarty->display('contest/contest.html');
    }

    public function actionAdd() {
        if (!Util::isLogin()) {
            $this->smarty->assign('msg', "请先登录");
            return $this->smarty->display('common/error.html');
        }
        return $this->smarty->display('contest/add.html');
    }

    public function actionDoAdd() {
        return "do-add";
    }

}