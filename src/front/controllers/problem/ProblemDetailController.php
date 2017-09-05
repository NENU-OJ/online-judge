<?php
/**
 * Created by PhpStorm.
 * User: 石昊
 * Date: 2017/9/5
 * Time: 10:23
 */

namespace app\controllers\problem;


use app\controllers\CController;
use app\models\Problem;

class ProblemDetailController extends CController
{
//    public function behaviors()
//    {
//        return [
//            [
//                'class' => Filter::className(),
//                //控制哪些动作需要过滤器
//                'only' => ['submit','discuss'],
//            ]
//        ];
//    }

    public function actionDetail($problemId)
    {
        $record = Problem::find()->where('id=:problemId and is_hide=0', [':problemId' => $problemId]);


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
        $this->smarty->display('problem/problemDetail.html');
    }

    public function actionSubmit()
    {

    }

    public function actionDiscuss()
    {

    }

    public function actionStatistic()
    {

    }
}