<?php

namespace app\controllers;

use app\common\Util;

class ContestController extends BaseController {

    public function actionIndex() {
        $this->smarty->assign('webTitle', "Contest");
        return $this->smarty->display('contest/contest.html');
    }

    public function actionAdd() {
        if (!Util::isLogin()) {
            $this->smarty->assign('msg', "请先登录");
            return $this->smarty->display('common/error.html');
        }

        $problemList = [];
//        $problemList = [11, 22, 33];

        $this->smarty->assign('webTitle', 'Add Contest');
        $this->smarty->assign('problemList', $problemList);
        return $this->smarty->display('contest/add.html');
    }

    public function actionDoAdd() {
        $problemList = \Yii::$app->request->post('pl');
        var_dump($problemList);
        return "do-add";
    }

}