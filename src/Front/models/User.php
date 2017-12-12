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
        return self::find()
            ->select('is_root')
            ->where(['id' => \Yii::$app->session['user_id']])
            ->one()
            ->is_root;
    }

    public static function addTotalSubmit($id) {
        $user = self::findById($id);
        $user->total_submit = $user->total_submit + 1;
        $user->update();
    }

    public static function totalPage($pageSize, $whereArray = []) {
        $totalCount = self::find()
            ->where($whereArray)
            ->count();
        $totalPage = ceil($totalCount / $pageSize);
        return $totalPage;
    }
}