<?php
/**
 * Created by PhpStorm.
 * User: 石昊
 * Date: 2017/8/31
 * Time: 9:30
 */

namespace app\controllers\status;

use app\controllers\CController;
use app\models\LanguageType;
use app\models\Status;
use app\models\User;

class StatusController extends CController
{
//    public function behaviors()
//    {
//        return [
//            [
//                'class' => Filter::className(),
//                'only' => ['submit','discuss'],
//            ]
//        ];
//    }

    public function actionIndex()
    {
        $command=Status::find();
        $command->select('t_user.username,t_language_type.language,problem_id,result,time_used,memory_used,submit_time,contest_id,is_shared,t_status.id');
        $command->leftJoin(User::tableName(), 't_user.id=user_id');
        $command->leftJoin(LanguageType::tableName(),'t_language_type.id=language_id');
        $data=$command->asArray()->all();
        $this->smarty->assign('data', $data);
        $this->smarty->display('status/status.html');
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

}