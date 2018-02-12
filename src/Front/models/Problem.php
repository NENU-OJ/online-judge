<?php

namespace app\models;

use yii\db\ActiveRecord;

class Problem extends ActiveRecord {
    public static function tableName() {
        return "{{%problem}}";
    }

    public static function findById($id) {
        return self::find()
            ->select("*")
            ->where("id=:id", [":id" => $id])
            ->one();
    }

    public static function addTotalSubmit($id) {
        $problem = self::findById($id);
        $problem->total_submit += 1;
        $problem->update();
    }

    public static function totalPage($pageSize, $whereArray, $andWhereArray = []) {
        $totalCount = self::find()
            ->where($whereArray)
            ->andWhere($andWhereArray)
            ->count();
        $totalPage = ceil($totalCount / $pageSize);
        return $totalPage;
    }

    public static function findByWhere($pageSize, $pageNow, $whereArray, $andWhereArray = []) {
        return self::find()
            ->where($whereArray)
            ->andWhere($andWhereArray)
            ->orderBy('id')
            ->offset(($pageNow - 1) * $pageSize)
            ->limit($pageSize)
            ->all();
    }

    public static function addResult($id, $field, $val) {
        if (!$field)
            return;
        \Yii::$app->db->createCommand("UPDATE t_problem SET $field=$field+:val WHERE id=:id")
            ->bindValue(':val', $val)
            ->bindValue(':id', $id)
            ->execute();
    }
}