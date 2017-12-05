<?php

namespace app\controllers;

use app\models\LanguageType;
use app\models\Status;
use app\models\User;
use app\common\Util;
use yii\db\Query;

class StatusController extends BaseController {
    public function actionList($id = 1) {

        $pageSize = \Yii::$app->params['queryPerPage'];
        $totalPage = Status::totalPage(0, $pageSize);


        $statuses = Status::getStatuses($id, $pageSize, 0);

//
//        foreach ($statuses[0] as $key => $value) {
//            var_dump($key);
//            echo " -> ";
//            var_dump($value);
//            echo "<br>.....<br>";
//        }
//        return;

        $pageArray = Util::getPaginationArray($id, 8, $totalPage);


        $this->smarty->assign('webTitle', 'Status');
        $this->smarty->assign('pageArray', $pageArray);
        $this->smarty->assign('totalPage', $totalPage);
        $this->smarty->assign('pageNow', $id);
        $this->smarty->assign('statuses', $statuses);
        return $this->smarty->display('status/status.html');
    }

    public function actionViewSource($s_id){
        $command=Status::find();
        $command->select('t_user.username,t_language_type.language,problem_id,result,source');
        $command->leftJoin(User::tableName(), 't_user.id=user_id');
        $command->leftJoin(LanguageType::tableName(),'t_language_type.id=language_id');
        $command->where("t_status.id=:s_id",[':s_id'=>$s_id]);
        $data=$command->asArray()->one();
        $this->smarty->assign('_username',$data['username']);
        $this->smarty->assign('problemId',$data['problem_id']);
        $this->smarty->assign('language',$data['language']);
        $this->smarty->assign('result',$data['result']);
        $this->smarty->assign('code',htmlentities($data['source']));
        $this->smarty->display('status/view.html');
    }

    public function actionCompileError($s_id){
        $command=Status::find();
        $command->select('t_user.username,t_language_type.language,problem_id,ce_info');
        $command->leftJoin(User::tableName(), 't_user.id=user_id');
        $command->leftJoin(LanguageType::tableName(),'t_language_type.id=language_id');
        $command->where("t_status.id=:s_id",[':s_id'=>$s_id]);
        $data=$command->asArray()->one();
        $this->smarty->assign('_username',$data['username']);
        $this->smarty->assign('problemId',$data['problem_id']);
        $this->smarty->assign('language',$data['language']);
        $this->smarty->assign('ceInfo',htmlentities($data['ce_info']));
        $this->smarty->display('status/ceInfo.html');
    }

    public function actionResult($id) {
        $status = Status::find()->select('result, time_used, memory_used')
                                ->where("id=:id", [":id" => $id])
                                ->one();
        if ($status) {
            $finished = in_array($status->result, ["Restricted Function", "Wrong Answer", "Presentation Error", "Accepted",
                "Output Limit Exceeded", "Runtime Error", "Memory Limit Exceeded", "Time Limit Exceeded", "Compile Error", "Judge Error"]);
            return json_encode([
                "code" => 0,
                "data" => [
                    "result" => $status->result,
                    "timeUsed" => $status->time_used,
                    "memoryUsed" => $status->memory_used,
                    "finished" => $finished,
                ],
            ]);
        } else {
            return json_encode(["code" => 1, "data" => "fucking not exists $id"]);
        }
    }
}