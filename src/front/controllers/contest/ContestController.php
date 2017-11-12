<?php
/**
 * Created by PhpStorm.
 * User: 石昊
 * Date: 2017/8/31
 * Time: 9:28
 */

namespace app\controllers\contest;

use app\controllers\CController;

class ContestController extends CController
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

    public function actionIndex(){
        $this->smarty->display('contest/contest.html');
    }

    public function actionDetail($c_id){
//        print_r($c_id);
        $this->smarty->assign('contestId',$c_id);
        $this->smarty->display('contest/contestDetail.html');
    }
}