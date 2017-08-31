<?php
/**
 * Created by PhpStorm.
 * User: 石昊
 * Date: 2017/8/29
 * Time: 11:10
 */

namespace app\controllers;


use app\models\Problem;

class ProblemController extends CController
{
    public function behaviors()
    {
        return [
            [
                'class' => Filter::className(),
                //控制那些动作需要过滤器
                'only' => ['submit','discuss'],
            ]
        ];
    }

    public function actionIndex(){
        $problem = Problem::find()->all();
        foreach ($problem as $value){
//            print_r($value->id);
//            print_r($value->title);
            $this->smarty->assign('test',$value->title);
        }
        $this->smarty->display('problems/problem.html');
    }
}