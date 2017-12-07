<?php
/**
 * Created by PhpStorm.
 * User: 石昊
 * Date: 2017/8/31
 * Time: 9:31
 */

namespace app\controllers;

use app\models\User;
use app\common\Util;

class RankController extends BaseController {
    public function actionIndex($id = 1) {
        $pageSize = \Yii::$app->params['queryPerPage'];

        $totalPage = User::totalPage($pageSize);

        $pageArray = Util::getPaginationArray($id, 8, $totalPage);

        $rankStart = ($id - 1) * $pageSize;

        $users = User::find()
            ->select('id, username, nickname, signature, solved_problem, total_submit')
            ->orderBy('solved_problem DESC, total_submit, username')
            ->offset(($id - 1) * $pageSize)
            ->limit($pageSize)
            ->all();


        $this->smarty->assign('webTitle', 'Rank');
        $this->smarty->assign('pageArray', $pageArray);
        $this->smarty->assign('rankStart', $rankStart);
        $this->smarty->assign('totalPage', $totalPage);
        $this->smarty->assign('pageNow', $id);
        $this->smarty->assign('users', $users);
        return $this->smarty->display('rank/rank.html');
    }
}