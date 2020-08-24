<?php

namespace app\models;

use yii\db\ActiveRecord;

class DiscussReply extends ActiveRecord {
    public static function tableName() {
        return "{{%discuss_reply}}";
    }

    public static function findFirstReply($discussId) {
        $firstReplyId = self::find()
            ->select('id')
            ->where(['discuss_id' => $discussId])
            ->min('id');

        return self::find()
            ->select('*')
            ->where(['id' => $firstReplyId])
            ->one();
    }
}