<?php

namespace app\models;


use yii\db\ActiveRecord;
use yii\db\Query;

class Status extends ActiveRecord {
    public static function tableName() {
        return "{{%status}}";
    }

    public static function totalPage($whereArray, $pageSize) {
        $totalCount = self::find()
            ->where($whereArray)
            ->count();
        $totalPage = ceil($totalCount / $pageSize);
        return $totalPage;
    }

    public static function getStatuses($pageId, $pageSize, $whereArray) {
        return (new Query())
            ->select('t_status.id AS runid,
                      t_user.username AS username,
                      t_user.id AS uid, 
                      t_status.problem_id AS pid,
                      t_status.result AS result,
                      t_status.is_shared AS share,
                      t_status.time_used AS time,
                      t_status.memory_used AS memory,
                      t_language_type.language AS lang,
                      length(t_status.source) AS len,
                      t_status.submit_time as submit_time'
            )
            ->from('t_status')
            ->join('INNER JOIN', 't_user', 't_status.user_id = t_user.id')
            ->join('INNER JOIN', 't_language_type', 't_status.language_id = t_language_type.id')
            ->where($whereArray)
            ->orderBy(['runid' => SORT_DESC])
            ->offset(($pageId - 1) * $pageSize)
            ->limit($pageSize)
            ->all();
    }

    public static function findById($id) {
        return self::find()
            ->select("*")
            ->where("id=:id", [":id" => $id])
            ->one();
    }

    public static function getCeinfo($id) {
        return self::find()
            ->select("ce_info")
            ->where("id=:id", [":id" => $id])
            ->one();
    }

    public static function getLeaderPage($pid, $pageSize) {
        $totalCount = (new Query())
            ->from('t_status')
            ->where(['problem_id' => $pid, 'result' => 'Accepted'])
            ->count('distinct user_id');

        $totalPage = ceil($totalCount / $pageSize);
        return $totalPage;
    }

    public static function getLeaderList($pid, $pageSize, $pageNow) {
        return (new Query())
            ->select('t_status.id, username, time_used, memory_used, length(source), language, submit_time')
            ->from('t_status')
            ->join('INNER JOIN', 't_user', 't_status.user_id = t_user.id')
            ->join('INNER JOIN', 't_language_type', 't_status.language_id = t_language_type.id')
            ->where(['problem_id' => $pid, 'result' => 'Accepted'])
            ->groupBy('user_id')
            ->orderBy('time_used, memory_used, length(source), t_status.id')
            ->limit($pageSize)
            ->offset(($pageNow - 1) * $pageSize)
            ->all();
    }
}