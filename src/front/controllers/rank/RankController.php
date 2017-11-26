<?php
/**
 * Created by PhpStorm.
 * User: 石昊
 * Date: 2017/8/31
 * Time: 9:31
 */

namespace app\controllers\rank;

use app\controllers\BaseController;
use app\models\User;


class RankController extends BaseController
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
        $command=User::find();
        $command->select('id,username,nickname,school,total_ac,total_submit');
        $command->orderBy('total_ac DESC');
        $data=$command->asArray()->all();
        foreach ($data as $key=>$val){
            $data[$key]['rank']=$key+1;
        }
        $this->smarty->assign('data',$data);
        $this->smarty->display('rank/rank.html');
    }

}