<?php
/**
 * Created by PhpStorm.
 * User: 石昊
 * Date: 2017/8/29
 * Time: 11:10
 */

namespace app\controllers;


use app\models\LanguageType;
use app\models\Problem;
use app\models\Status;
use app\common\Util;
use yii\web\NotFoundHttpException;

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
    public function actionView($id) {
        $problem = Problem::findById($id);
        if (!$problem) {
            throw new NotFoundHttpException("不存在这个题目");
        }
        $this->smarty->assign('vmMultiplier', \Yii::$app->params['vmMultiplier']);
        $this->smarty->assign('problem', $problem);
        $this->smarty->assign('languageTypeList', LanguageType::find()->all());
        $this->smarty->assign('webTitle', "Problem $id");
        return $this->smarty->display('problem/problemView.html');
    }
}