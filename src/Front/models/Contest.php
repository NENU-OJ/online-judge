<?php
/**
 * Created by PhpStorm.
 * User: torapture
 * Date: 18-2-6
 * Time: 下午2:32
 */

namespace app\models;


use yii\db\ActiveRecord;
use yii\db\Query;

class Contest extends ActiveRecord {
    public static function tableName() {
        return "{{%contest}}";
    }

    public static function findById($id) {
        return self::find()
            ->select('*')
            ->where(['id' => $id])
            ->one();
    }

    public static function totalPage($whereArray, $pageSize, $andWhereArray = []) {
        $totalCount = self::find()
            ->where($whereArray)
            ->andWhere($andWhereArray)
            ->count();
        $totalPage = ceil($totalCount / $pageSize);
        return $totalPage;
    }

    public static function getContests($pageId, $pageSize, $whereArray, $andWhereArray = []) {
        return (new Query())
            ->select('t_contest.id AS id,
                      t_contest.title AS title,
                      t_contest.start_time AS start_time, 
                      t_contest.end_time AS end_time,
                      t_contest.is_private AS type,
                      t_user.username AS username'
            )
            ->from('t_contest')
            ->join('INNER JOIN', 't_user', 't_contest.owner_id = t_user.id')
            ->where($whereArray)
            ->andWhere($andWhereArray)
            ->orderBy(['id' => SORT_DESC])
            ->offset(($pageId - 1) * $pageSize)
            ->limit($pageSize)
            ->all();
    }

}