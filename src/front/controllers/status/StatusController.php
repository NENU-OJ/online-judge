<?php
/**
 * Created by PhpStorm.
 * User: 石昊
 * Date: 2017/8/31
 * Time: 9:30
 */

namespace app\controllers\status;

use app\controllers\CController;
use app\models\Status;
use app\models\User;

class StatusController extends CController
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

    public function actionIndex()
    {
        $this->smarty->display('status/status.html');
    }

    public function actionList()
    {
        //获取数据
        if (isset($_GET['currentPage']) && $_GET['currentPage'] != 0) {
            $page = $_GET['currentPage'];
        } else {
            $page = 0;
        }
        if (isset($_GET['keyword']) && trim($_GET['keyword']) != "") {
            $keyword = trim($_GET['keyword']);
        } else {
            $keyword = "";
        }
        if (isset($_GET['problemId']) && $_GET['problemId'] != 0) {
            $problemId = $_GET['problemId'];
        } else {
            $problemId = 0;
        }
        if (isset($_GET['languageId']) && $_GET['languageId'] != 0) {
            $languageId = $_GET['languageId'];
        } else {
            $languageId = 0;
        }
        if (isset($_GET['result']) && trim($_GET['result']) != "") {
            $result = trim($_GET['result']);
        } else {
            $result = "";
        }

        $command = Status::find();
        $command->limit(20);
        $command->offset(($page - 1) * 10);
        $command->select('*');
        $command->leftJoin(User::tableName(), 't_user.id=user_id');
        $conditions = "";
        $params = array();
        if ($problemId != 0) {
            $conditions .= " and id=:problemId ";
            $params[':problemId'] = $problemId;
        }
        if ($keyword != "") {
            $conditions .= " and t_user.username LIKE :keyword ";
            $params[':keyword'] = '%' . $keyword . '%';
        }
        if ($languageId != 0) {
            $conditions .= "and language_id=:languageId";
            $params[':languageId'] = $languageId;
        }
        if ($result != "") {
            $conditions .= "and result LIKE :result";
            $params[':result'] = '%' . $result . '%';
        }
        $command->where($conditions,$params);
        $command->orderBy('submit_time DESC');

        //查询数据
        $recordCount = $command->count();
        $record = $command->all();

        print_r($recordCount);
        foreach ($record as $item){
            print_r($item->problem_id);
        }
    }
}