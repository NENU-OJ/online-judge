<?php

namespace app\models;

use yii\db\ActiveRecord;

class Problem extends ActiveRecord{
    public static function tableName() {
        return "{{%problem}}";
    }
}