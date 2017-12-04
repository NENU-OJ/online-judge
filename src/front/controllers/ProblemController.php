<?php
/**
 * Created by PhpStorm.
 * User: 石昊
 * Date: 2017/8/29
 * Time: 11:10
 */

namespace app\controllers;


use app\models\Problem;
use app\models\Status;
use app\common\Util;

class ProblemController extends BaseController {
    public function actionList($id = 1) {
        $totalCount = Problem::find()
            ->where(['is_hide' => 0])
            ->count();
        $pageSize = \Yii::$app->params['problemsPerPage'];
        $totalPage = ceil($totalCount / $pageSize);

        $problems = Problem::find()
            ->where(['is_hide' => 0])
            ->orderBy('id')
            ->offset(($id - 1) * $pageSize)
            ->limit($pageSize)
            ->all();

        $pageArray = Util::getPaginationArray($id, 6, $totalPage);

        $this->smarty->assign('pageArray', $pageArray);
        $this->smarty->assign('totalPage', $totalPage);
        $this->smarty->assign('problems', $problems);
        return $this->smarty->display('problem/problem.html');
    }
}