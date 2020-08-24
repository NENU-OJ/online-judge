<?php

namespace app\models;

use yii\db\ActiveRecord;

class LanguageType extends ActiveRecord {
    public static function tableName() {
        return "{{%language_type}}";
    }

    public static function getLangList() {
        return self::find()
            ->select('*')
            ->all();
    }
}