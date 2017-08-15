<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        //return $this->render('index');
        $smarty = \Yii::$app->smarty;
        $smarty->assign('name',"holy shit!");
        $str="fuck smarty!";
        $smarty->assign('str',$str);
        $smarty->display('index.html');
    }
}
