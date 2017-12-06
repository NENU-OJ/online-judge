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
use app\models\User;
use yii\web\NotFoundHttpException;

class ProblemController extends BaseController {
    public function actionList($id = 1) {
        $totalCount = Problem::find()
            ->where(['is_hide' => 0])
            ->count();
        $pageSize = \Yii::$app->params['queryPerPage'];
        $totalPage = ceil($totalCount / $pageSize);

        $problems = Problem::find()
            ->where(['is_hide' => 0])
            ->orderBy('id')
            ->offset(($id - 1) * $pageSize)
            ->limit($pageSize)
            ->all();

        $acArray = [];
        if (isset(\Yii::$app->session['user_id'])) {
            foreach ($problems as $problem) {
                $acStatus = Status::find()
                    ->select('id')
                    ->where(['problem_id' => $problem->id, 'user_id' => \Yii::$app->session['user_id'], 'result' => 'Accepted'])
                    ->one();
                if ($acStatus) {
                    $acArray[] = $problem->id;
                }
            }
        }

        $pageArray = Util::getPaginationArray($id, 6, $totalPage);

        $this->smarty->assign('webTitle', 'Problem List');

        $this->smarty->assign('pageArray', $pageArray);
        $this->smarty->assign('totalPage', $totalPage);
        $this->smarty->assign('pageNow', $id);
        $this->smarty->assign('acArray', $acArray);
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

    public function actionSubmit() {
        if (!isset(\Yii::$app->session['user_id']))
            return json_encode(["code" => 2, "data" => "请先登录"]);

        if (\Yii::$app->request->isPost) {
            try {
                $status = new Status();
                $status->user_id = \Yii::$app->session['user_id'];
                $status->problem_id = \Yii::$app->request->post('problemId');
                $status->language_id = \Yii::$app->request->post('languageId');
                $status->source = \Yii::$app->request->post('sourceCode');
                $status->is_shared = intval(\Yii::$app->request->post('isShared'));
                $status->contest_id = \Yii::$app->request->post('contestId');
                $status->result = "Send to Judge";
                $status->submit_time = date("Y-m-d H:i:s");
                $status->save();

                User::addTotalSubmit($status->user_id);
                Problem::addTotalSubmit($status->problem_id);

                $host = \Yii::$app->params['judger']['host'];
                $port = \Yii::$app->params['judger']['port'];

                Util::sendToJudgeBySocket($status->id, $host, $port);

                return json_encode(["code" => 0, "data" => ["result" => "Send to Judge", "id" => $status->id]]);
            } catch (\Exception $e) {
                return json_encode(["code" => 1, "data" => $e->getMessage()]);
            }

        } else {
            return json_encode(["code" => 1, "data" => "请求方式错误"]);
        }
    }
}