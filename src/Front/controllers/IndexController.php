<?php

namespace app\controllers;

use app\models\Discuss;
use app\models\User;

class IndexController extends BaseController {

    public function actionIndex() {
        $users = User::find()
            ->select('username, solved_problem')
            ->orderBy('solved_problem DESC, total_submit, username')
            ->limit(10)
            ->all();

        $discussList = Discuss::getDiscussList(1, 8, ["contest_id" => 0, "priority" => 0],[]);

        $this->smarty->assign('users', $users);
        $this->smarty->assign('discussList', $discussList);
        $this->smarty->display('index.html');
    }

    public function actionWarn($msg) {
        $this->smarty->assign('msg', $msg);
        return $this->smarty->display('common/error.html');
    }
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }
}