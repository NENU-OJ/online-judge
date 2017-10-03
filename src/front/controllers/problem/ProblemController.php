<?php
/**
 * Created by PhpStorm.
 * User: 石昊
 * Date: 2017/8/29
 * Time: 11:10
 */

namespace app\controllers\problem;


use app\controllers\CController;
use app\models\Problem;
use app\models\Status;

class ProblemController extends CController
{
//    public function behaviors()
//    {
//        return [
//            [
//                'class' => Filter::className(),
//                //控制哪些动作需要过滤器
//                'only' => ['submit','discuss'],
//            ]
//        ];
//    }

    public function actionIndex()
    {
        $command = Problem::find();
        $command->select('id,title,total_submit,total_ac,source');
        $command->where("is_hide=0");
        $data = $command->asArray()->all();
        $dataCount = $command->count();
        $this->smarty->assign('data',$data);
        $this->smarty->assign('dataCount',$dataCount);
        $this->smarty->display('problems/problem.html');
    }

    public function actionList()
    {
        //获取数据
        if (isset($_GET['currentPage']) && $_GET['currentPage'] != 0) {
            $page = $_GET['currentPage'];
        } else {
            $page = 0;
        }
        if (isset($_GET['problemId']) && $_GET['problemId'] != 0) {
            $problemId = $_GET['problemId'];
        } else {
            $problemId = 0;
        }
        if (isset($_GET['keyword']) && trim($_GET['keyword']) != "") {
            $keyword = trim($_GET['keyword']);
        } else {
            $keyword = "";
        }
        if (isset($_GET['status']) && $_GET['status'] != 0) {
            $status = $_GET['status'];
        } else {
            $status = 0;
        }
        if (isset($_GET['order']) && $_GET['order'] != 0) {
            $order = $_GET['order'];
        } else {
            $order = 0;
        }

        //测试数据
//        $keyword=2;
//        $order=3;
//        $status=1;

        //转换为查找条件
        $command = Problem::find();
        $command->limit(10);//页容量
        $command->offset(($page - 1) * 10);//偏移量
        $command->select('{{%problem}}.id,title,total_submit,total_ac');
        $command->leftJoin(Status::tableName(), '{{%problem}}.id = problem_id');
        $conditions = "is_hide=0";
        $params = array();
        if ($problemId != 0) {
            $conditions .= " and id=:problemId ";
            $params[':problemId'] = $problemId;
        }
        if ($keyword != "") {
            $conditions .= " and title LIKE :keyword ";
            $params[':keyword'] = '%' . $keyword . '%';
        }
        if ($status != 0) {
            $conditions .= " and t_status.user_id=:userId and t_status.result NOT LIKE :result  ";
            $params[':userId'] = \Yii::$app->session['user_id'];
            $params[':result'] = '%' . 'Accepted' . '%';
        }
        $command->where($conditions, $params);
        switch ($order) {
            case 1:
                $command->orderBy('total_submit ASC');
                break;
            case 2:
                $command->orderBy('total_ac ASC');
                break;
            case 3:
                $command->orderBy('total_submit DESC');
                break;
            case 4:
                $command->orderBy('total_ac DESC');
                break;
            default:
                $command->orderBy('id ASC');
                break;
        }
        $command->distinct(true);

        //查询数据
        $recordCount = $command->count();
        $record = $command->all();

        print_r($recordCount);
        foreach ($record as $item) {
            print_r($item->id);
        }
    }

}