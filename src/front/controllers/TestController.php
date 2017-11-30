<?php
/**
 * Created by PhpStorm.
 * User: torapture
 * Date: 17-11-25
 * Time: 下午3:42
 */

namespace app\controllers;


use app\models\Problem;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class TestController extends BaseController {
    public $shit = "fuck";

    public function actionIndex() {
        if (\Yii::$app->request->isPost) {
            return "[{\"code\":0, \"data\":\"0\"}]";
        } else if (\Yii::$app->request->isAjax) {
            return json_encode(["code" => 1, "data" => $_SESSION["username"]]);
        }
        $this->smarty->assign('_cstf', \Yii::$app->request->csrfToken);
        $this->smarty->display('test.html');

    }
    public function actionView() {
    }
    public function actionShit($id) {
        return $id;
    }
}