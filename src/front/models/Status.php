<?php
/**
 * Created by PhpStorm.
 * User: 石昊
 * Date: 2017/9/2
 * Time: 16:44
 */

namespace app\models;


use yii\db\ActiveRecord;
use yii\db\Query;

class Status extends ActiveRecord {
    public static function tableName() {
        return "{{%status}}";
    }

    public static function totalPage($contestId, $pageSize) {
        $totalCount = self::find()
            ->where(['contest_id' => $contestId])
            ->count();
        $totalPage = ceil($totalCount / $pageSize);
        return $totalPage;
    }

    public static function getStatuses($pageId, $pageSize, $contestId) {
        return (new Query())
            ->select('t_status.id AS runid,
                      t_user.username AS username, 
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
            ->where(['contest_id' => $contestId])
            ->orderBy(['runid' => SORT_DESC])
            ->offset(($pageId - 1) * $pageSize)
            ->limit($pageSize)
            ->all();
    }
}