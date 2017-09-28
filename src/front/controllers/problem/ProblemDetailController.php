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
use app\models\Status;

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

    public function actionDetail($p_id)
    {
//        $record = Problem::find()->where('id=:problemId and is_hide=0', [':problemId' => $problemId])->one();
//
//
//        $this->smarty->assign('title', $record->title);
//        $this->smarty->assign('description', $record->description);
//        $this->smarty->assign('input', $record->input);
//        $this->smarty->assign('output', $record->output);
//        $this->smarty->assign('sampleIn', $record->sample_in);
//        $this->smarty->assign('sampleOut', $record->sample_out);
//        $this->smarty->assign('hint', $record->hint);
//        $this->smarty->assign('source', $record->source);
//        $this->smarty->assign('author', $record->author);
//        $this->smarty->assign('timeLimit', $record->time_limit);
//        $this->smarty->assign('memoryLimit', $record->memory_limit);
//        $this->smarty->assign('isSpecialJudge', $record->is_special_judge);
        $this->smarty->display('problems/problemDetail.html');
//        print_r($p_id);
    }

    public function actionToSubmit(){
        $this->smarty->display('problems/problemSubmit.html');
    }

    public function actionSubmit()
    {
        $record = new Status();
        $record->problem_id=$_GET['problemId'];
        $record->source=$_GET['source'];
        $record->submit_time=date("Y-m-d h:i:sa");
        if (isset($_GET['contestId'])){
            $record->contest_id=$_GET['contestId'];
        }else{
            $record->contest_id=0;
        }
        $record->user_id=\Yii::$app->session['user_id'];
        $record->language_id=$_GET['languageId'];
        $record->is_shared=$_GET['isShared'];
        $record->save();
        $list='[{"code":0,"data":""}]';
        print $list;
    }

    public function actionToDiscuss(){
        $this->smarty->display('problems/problemDiscuss.html');
    }
}