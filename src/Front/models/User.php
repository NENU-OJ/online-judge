<?php

namespace app\models;

use yii\db\ActiveRecord;

class User extends ActiveRecord {
    public static function tableName() {
        return "{{%user}}";
    }

    public static function findByUsername($username) {
        return self::find()
            ->select('*')
            ->where('BINARY username=:username', [':username' => $username])
            ->one();
    }

    public static function findById($id) {
        return self::find()
            ->select("*")
            ->where("id=:id", [":id" => $id])
            ->one();
    }

    public static function isRoot($id) {
        $ret = self::find()
            ->select('is_root')
            ->where(['id' => \Yii::$app->session['user_id']])
            ->one();

        if ($ret && $ret->is_root)
            return true;
        else
            return false;
    }

    public static function addTotalSubmit($id) {
        $user = self::findById($id);
        $user->total_submit = $user->total_submit + 1;
        $user->update();
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
        \Yii::$app->db->createCommand("UPDATE t_user SET solved_problem=solved_problem+:val WHERE id=:id")
            ->bindValue(':val', $val)
            ->bindValue(':id', $id)
            ->execute();
    }
}