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
}