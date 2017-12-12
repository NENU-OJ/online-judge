<?php

namespace app\models;

use yii\db\ActiveRecord;

class DiscussReply extends ActiveRecord {
    public static function tableName() {
        return "{{%discuss_reply}}";
    }
}