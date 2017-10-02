<?php
/**
 * Created by PhpStorm.
 * User: 石昊
 * Date: 2017/8/30
 * Time: 16:11
 */

namespace app\controllers;

use yii\base\ActionFilter;

/**
 * Class Filter
 * @package app\controllers
 * 过滤器类，在执行动作前判断是否已经登录，不同的控制器单独设置behavior方法
 */

class Filter extends ActionFilter
{
    public function beforeAction($action)
    {
        if(isset(\Yii::$app->session['user_id'])){
            return parent::beforeAction($action);
        }
        else{

            \Yii::$app->runAction('user/user/to-login');
            return false;
        }
    }

}