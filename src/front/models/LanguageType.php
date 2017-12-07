<?php
/**
 * Created by PhpStorm.
 * User: 石昊
 * Date: 2017/10/5
 * Time: 17:08
 */

namespace app\models;

use yii\db\ActiveRecord;

class LanguageType extends ActiveRecord {
    public static function tableName() {
        return "{{%language_type}}";
    }

    public static function getLangList() {
        return self::find()
            ->select('id, language')
            ->all();
    }
}