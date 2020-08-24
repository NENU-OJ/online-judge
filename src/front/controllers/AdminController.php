<?php
/**
 * Created by PhpStorm.
 * User: torapture
 * Date: 17-12-7
 * Time: 下午7:25
 */

namespace app\controllers;


use app\common\Util;
use app\models\Contest;
use app\models\ContestProblem;
use app\models\LanguageType;
use app\models\Problem;
use app\models\Status;
use app\models\User;

class AdminController extends BaseController {
    public function actionProblem() {
        $canView = false;
        if (Util::isRoot())
            $canView = true;

        if (!$canView) {
            $this->smarty->assign('msg', "管不了，管不了.jpg");
            return $this->smarty->display('common/error.html');
        }

        $languageList = LanguageType::find()
            ->select('*')
            ->all();

        $this->smarty->assign('languageList', $languageList);
        $this->smarty->assign('webTitle', 'Admin');
        return $this->smarty->display('admin/admin.html');
    }

    public function actionRejudge() {
        $canView = false;
        if (Util::isRoot())
            $canView = true;

        if (!$canView) {
            $this->smarty->assign('msg', "管不了，管不了.jpg");
            return $this->smarty->display('common/error.html');
        }

        $this->smarty->assign('webTitle', 'ReJudge');
        return $this->smarty->display('admin/rejudge.html');
    }

    public function actionRejudgeStatus() {

        if (\Yii::$app->request->isGet)
            return json_encode(['code' => 1, 'data' => '请求方式错误']);
        if (!Util::isRoot())
            return json_encode(['code' => 1, 'data' => '没有权限']);

        $runid = \Yii::$app->request->post('runid', 0);

        $status = Status::find()
            ->select('id, user_id, contest_id, problem_id, result')
            ->where(['id' => $runid])
            ->one();

        if (!$status)
            return json_encode(['code' => 1, 'data' => "没有 $runid 这个提交"]);

        if ($status->contest_id) {
            if ($status->result == 'Accepted')
                ContestProblem::addTotalAC($status->contest_id, $status->problem_id, -1);
        } else {
            if ($status->result == 'Accepted') {
                User::addTotalAC($status->user_id, -1);
                $ACCount = Status::find()
                    ->where(['contest_id' => 0, 'user_id' => $status->user_id, 'problem_id' => $status->problem_id, 'result' => 'Accepted'])
                    ->count();
                if ($ACCount == 1)
                    User::addTotalSolved($status->user_id, -1);
            }

            $field = '';
            if ($status->result == 'Accepted')
                $field = 'total_ac';
            else if ($status->result == 'Wrong Answer')
                $field = 'total_wa';
            else if ($status->result == 'Presentation Error')
                $field = 'total_pe';
            else if ($status->result == 'Time Limit Exceeded')
                $field = 'total_tle';
            else if ($status->result == 'Memory Limit Exceeded')
                $field = 'total_mle';
            else if ($status->result == 'Output Limit Exceeded')
                $field = 'total_ole';
            else if ($status->result == 'Runtime Error')
                $field = 'total_re';
            else if ($status->result == 'Compile Error')
                $field = 'total_ce';
            else if ($status->result == 'Restricted Function')
                $field = 'total_rf';

            if ($field)
                Problem::addResult($status->problem_id, $field, -1);

        }
        $status->result = 'Send to Rejudge';
        $status->update();
        Util::sendRunIdToRejudge($status->id);
        return json_encode(['code' => 0, 'data' => "ReJudge $runid 成功"]);
    }

    public function actionRejudgeContest() {
        if (\Yii::$app->request->isGet)
            return json_encode(['code' => 1, 'data' => '请求方式错误']);
        if (!Util::isRoot())
            return json_encode(['code' => 1, 'data' => '没有权限']);

        $contestId = \Yii::$app->request->post('contestId', 0);
        $prob = \Yii::$app->request->post('prob');

        $contest = Contest::findById($contestId, 'id');
        if (!$contest)
            return json_encode(['code' => 1, 'data' => "没有 $contestId 这个比赛"]);
        $problem = ContestProblem::find()->select('problem_id')->where(['contest_id' => $contestId, 'lable' => $prob])->one();
        if (!$problem)
            return json_encode(['code' => 1, 'data' => "比赛 $contestId 没有 $prob 这个题目"]);
        else
            $pid = $problem->problem_id;

        $statusList = Status::find()->select('id, result')->where(['contest_id' => $contestId, 'problem_id' => $pid])->all();
        foreach ($statusList as $status) {
            if ($status->result == 'Accepted')
                ContestProblem::addTotalAC($contestId, $pid, -1);
            $status->result = 'Send to Rejudge';
            $status->update();
            Util::sendRunIdToRejudge($status->id);
        }
        return json_encode(['code' => 0, 'data' => "Rejudge 比赛:$contestId, 题目:$prob 成功"]);
    }
}