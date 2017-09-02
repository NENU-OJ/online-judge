<?php
/**
 * Created by PhpStorm.
 * User: 石昊
 * Date: 2017/9/2
 * Time: 16:44
 */

namespace app\models;


use yii\db\ActiveRecord;

class Status extends ActiveRecord
{
    public static function tableName() {
        return "{{%status}}";
    }
}