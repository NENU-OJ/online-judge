<?php

namespace app\models;

use yii\db\ActiveRecord;

class User extends ActiveRecord {
    public static function tableName() {
        return "{{%user}}";
    }

    public static function findByUsername($username, $columns = '*') {
        return self::find()
            ->select($columns)
            ->where('username=:username', [':username' => $username])
            ->one();
    }

    public static function findById($id, $columns = '*') {
        return self::find()
            ->select($columns)
            ->where("id=:id", [":id" => $id])
            ->one();
    }

    public static function isRoot($id) {
        $ret = self::find()
            ->select('is_root')
            ->where(['id' => $id])
            ->one();

        if ($ret && $ret->is_root)
            return true;
        else
            return false;
    }

    public static function addTotalSubmit($id) {
        \Yii::$app->db
            ->createCommand("UPDATE t_user SET total_submit=total_submit+1 WHERE id=$id")
            ->execute();
    }

    public static function addTotalAC($id, $val = 1) {
        \Yii::$app->db->createCommand("UPDATE t_user SET total_ac=total_ac+:val WHERE id=:id")
            ->bindValue(':val', $val)
            ->bindValue(':id', $id)
            ->execute();
    }

    public static function totalPage($pageSize, $whereArray = []) {
        $totalCount = self::find()
            ->where($whereArray)
            ->count();
        $totalPage = ceil($totalCount / $pageSize);
        return $totalPage;
    }

    public static function addTotalSolved($id, $val) {
        \Yii::$app->db->createCommand("UPDATE t_user SET solved_problem=solved_problem-:val WHERE id=:id")
            ->bindValue(':val', $val)
            ->bindValue(':id', $id)
            ->execute();
    }
}