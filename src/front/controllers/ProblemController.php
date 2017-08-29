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
    public function actionIndex(){
        $problem = Problem::find()->all();
        var_dump($problem);
        //$this->smarty->display('problems/problem.html');
    }
}