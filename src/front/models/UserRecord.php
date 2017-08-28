<?php

namespace app\models;

use yii\db\ActiveRecord;

class UserRecord extends ActiveRecord {
    public static function tableName() {
        return "t_user";
    }
}