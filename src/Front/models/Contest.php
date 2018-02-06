<?php
/**
 * Created by PhpStorm.
 * User: torapture
 * Date: 18-2-6
 * Time: 下午2:32
 */

namespace app\models;


use yii\db\ActiveRecord;

class Contest extends ActiveRecord {
    public static function tableName() {
        return "{{%contest}}";
    }

    public static function findById($id) {
        return self::find()
            ->select('*')
            ->where(['id' => $id])
            ->one();
    }
}