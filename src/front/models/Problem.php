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
}