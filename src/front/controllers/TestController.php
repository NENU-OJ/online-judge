<?php
/**
 * Created by PhpStorm.
 * User: bangning.lbn
 * Date: 2017/8/14
 * Time: 15:26
 */

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;


class TestController extends Controller {
    public function actionAqours() {
        return print_r(Yii::$app->request->getUserHostAddress(), true);
    }
}