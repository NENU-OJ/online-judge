<?php
/**
 * Created by PhpStorm.
 * User: 石昊
 * Date: 2017/9/5
 * Time: 10:23
 */

namespace app\controllers;

use app\models\LanguageType;
use app\models\Status;
use app\models\Problem;

class ProblemDetailController extends BaseController {
    public function behaviors() {
        return [['class' => Filter::className(), //控制哪些动作需要过滤器
            'only' => ['to-submit'],]];
    }

    public function actionDetail($p_id, $c_id = 0) {
        $record = Problem::find()->where('id=:problemId and is_hide=0', [':problemId' => $p_id])->one();
        if ($c_id != 0) {
            $this->smarty->assign('contestId', $c_id);
        }
        $this->smarty->assign('problemId', $record->id);
        $this->smarty->assign('title', $record->title);
        $this->smarty->assign('description', $record->description);
        $this->smarty->assign('input', $record->input);
        $this->smarty->assign('output', $record->output);
        $this->smarty->assign('sampleIn', $record->sample_in);
        $this->smarty->assign('sampleOut', $record->sample_out);
        $this->smarty->assign('hint', $record->hint);
        $this->smarty->assign('source', $record->source);
        $this->smarty->assign('author', $record->author);
        $this->smarty->assign('timeLimit', $record->time_limit);
        $this->smarty->assign('memoryLimit', $record->memory_limit);
        $this->smarty->assign('isSpecialJudge', $record->is_special_judge);
        $this->smarty->assign('totalSubmit', $record->total_submit);
        $this->smarty->assign('totalAC', $record->total_ac);
        $this->smarty->assign('totalWA', $record->total_wa);
        $this->smarty->assign('totalRE', $record->total_re);
        $this->smarty->assign('totalCE', $record->total_ce);
        $this->smarty->assign('totalTLE', $record->total_tle);
        $this->smarty->assign('totalMLE', $record->total_mle);
        $this->smarty->assign('totalPE', $record->total_pe);
        $this->smarty->assign('totalOLE', $record->total_ole);
        $this->smarty->assign('totalRF', $record->total_rf);

        $this->smarty->display('problem/problemDetail.html');
    }

    public function actionToSubmit($p_id, $c_id = 0) {
        $languageList = LanguageType::find()->asArray()->all();
        $this->smarty->assign('languageList', $languageList);
        $this->smarty->assign('contestId', $c_id);
        $this->smarty->assign('problemId', $p_id);
        $this->smarty->display('problem/problemSubmit.html');
    }

    public function actionSubmit() {
        $record = new Status();
        $record->problem_id = $_GET['problemId'];
        $record->source = $_GET['sourceCode'];
        $record->submit_time = date("Y-m-d H:i:s"); // TODO date check
        $record->result = "Send to Judge";
        if (isset($_GET['contestId'])) {
            $record->contest_id = $_GET['contestId'];
        } else {
            $record->contest_id = 0;
        }
        $record->user_id = \Yii::$app->session['user_id'];
        $record->language_id = $_GET['languageId'];
        $record->is_shared = $_GET['isShared'] ? 1 : 0;
        //        print_r($record);
        $record->save();
        $list = '[{"code":0,"data":""}]';
        print $list;

        /// TODO fucking dirty
        $runid = strval($record->attributes["id"]);
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_connect($socket, "127.0.0.1", 27015);
        socket_write($socket, $runid, strlen($runid));
        socket_close($socket);

    }

    public function actionToDiscuss() {
        $this->smarty->display('problem/problemDiscuss.html');
    }
}