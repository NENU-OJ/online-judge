<?php

namespace app\controllers;

use app\common\Cache;
use app\models\Contest;
use app\models\ContestUser;
use app\models\Discuss;
use app\models\User;
use Memcached;


class IndexController extends BaseController {

    public function actionIndex() {
        $users = User::find()
            ->select('username, solved_problem')
            ->orderBy('solved_problem DESC, total_submit, username')
            ->limit(10)
            ->all();

        $discussList = Discuss::getDiscussList(1, 8, ["contest_id" => 0, "priority" => 0],[]);

        $contests = Contest::find()
            ->select('*')
            ->orderBy('id DESC')
            ->where(['is_private' => 0])
            ->limit(5)
            ->all();

        $contestList = [];
        foreach ($contests as $contest) {
            $record = [];
            $record['id'] = $contest->id;
            $record['title'] = $contest->title;
            $duration = strtotime($contest->end_time) - strtotime($contest->start_time);
            $record['duration'] = sprintf("%.0f小时", $duration / 3600);
            $record['people'] = ContestUser::find()->where(['contest_id' => $contest->id])->count();
            $record['startTime'] = $contest->start_time;
            if (time() < strtotime($contest->start_time))
                $record['status'] = 'Pending';
            else if (time() > strtotime($contest->end_time))
                $record['status'] = 'Ended';
            else
                $record['status'] = 'Running';
            $contestList[] = $record;
        }

        $this->smarty->assign('blogList', \Yii::$app->params['blogList']);
        $this->smarty->assign('ojList', \Yii::$app->params['ojList']);
        $this->smarty->assign('users', $users);
        $this->smarty->assign('contestList', $contestList);
        $this->smarty->assign('discussList', $discussList);
        $this->smarty->display('index.html');
    }


    public function actionWarn($msg) {
        $this->smarty->assign('msg', $msg);
        return $this->smarty->display('common/error.html');
    }

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }
}