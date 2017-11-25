<?php
/**
 * Created by PhpStorm.
 * User: torapture
 * Date: 17-11-25
 * Time: 下午3:42
 */

namespace app\controllers;


use app\models\Problem;

class TestController extends CController {
    public function actionIndex() {
        $data = \app\models\Problem::find()->all()[0];
//        foreach ($data as $key => $value) {
//            echo $key . " -> " . $value . "<br>";
//        }

    }
}