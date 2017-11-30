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

class ProblemController extends BaseController {
    public function actionIndex() {
        if (isset(\Yii::$app->session['user_id'])) {
            $userId = \Yii::$app->session['user_id'];
            $command = Problem::find();
            $command->select('id,title,total_submit,total_ac,source');
            $command->where("is_hide=0");
            $data = $command->asArray()->all();
            foreach ($data as $key => $item) {
                $isAC = Status::find()->where(" problem_id=:p_id and user_id=:userId and t_status.result LIKE :result ", [':p_id' => $item['id'], ':userId' => $userId, ':result' => '%' . 'Accepted' . '%'])->count();
                if ($isAC > 0) {
                    $data[$key]['isAC'] = true;
                } else {
                    $data[$key]['isAC'] = false;
                }
            }
            $this->smarty->assign('data', $data);
        } else {
            $command = Problem::find();
            $command->select('id,title,total_submit,total_ac,source');
            $command->where("is_hide=0");
            $data = $command->asArray()->all();
            $this->smarty->assign('data', $data);
        }
        $this->smarty->display('problems/problem.html');
    }
}