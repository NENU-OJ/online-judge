<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\db\Query;

class Discuss extends ActiveRecord {
    public static function tableName() {
        return "{{%discuss}}";
    }

    public static function totalPage($whereArray, $andWhereArray, $pageSize) {
        $totalCount = self::find()
            ->where($whereArray)
            ->andWhere($andWhereArray)
            ->count();
        $totalPage = ceil($totalCount / $pageSize);
        return $totalPage;
    }

    public static function getDiscussList($pageId, $pageSize, $whereArray, $andWhereArray) {
        return (new Query())
            ->select('t_discuss.id AS id,
                       t_discuss.created_at AS created_at,
                       t_discuss.username AS username,
                       t_discuss.replied_at AS replied_at,
                       t_discuss.replied_user AS replied_user,
                       t_discuss.replied_num as replied_num,
                       t_discuss.title AS title,
                       t_discuss.priority AS priority,
                       t_user.avatar AS avatar')
            ->from('t_discuss')
            ->join('INNER JOIN', 't_user', 't_discuss.username = t_user.username')
            ->where($whereArray)
            ->andWhere($andWhereArray)
            ->orderBy('priority DESC, replied_at DESC')
            ->offset(($pageId - 1) * $pageSize)
            ->limit($pageSize)
            ->all();
    }
}