<?php
/**
 * Created by PhpStorm.
 * User: 石昊
 * Date: 2017/8/31
 * Time: 9:28
 */

namespace app\controllers;


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
}