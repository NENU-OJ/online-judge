<?php

namespace app\controllers;

class ContestController extends BaseController {

    public function actionIndex(){
        $this->smarty->display('contest/contest.html');
    }

    public function actionDetail($c_id){
//        print_r($c_id);
        $this->smarty->assign('contestId',$c_id);
        $this->smarty->display('contest/contestDetail.html');
    }
}