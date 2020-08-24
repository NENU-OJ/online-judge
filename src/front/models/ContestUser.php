<?php
/**
 * Created by PhpStorm.
 * User: torapture
 * Date: 18-2-7
 * Time: 下午6:38
 */

namespace app\models;


use yii\db\ActiveRecord;
use yii\db\Query;

class ContestUser extends ActiveRecord {

    public static function tableName() {
        return "{{%contest_user}}";
    }

    public static function haveUser($contestId, $userId) {
        $query = self::find()
            ->select('*')
            ->where(["contest_id" => $contestId, "user_id" => $userId])
            ->one();
        if ($query)
            return true;
        else
            return false;
    }

    public static function addUser($contestId, $userId) {
        $record = new ContestUser();
        $record->contest_id = $contestId;
        $record->user_id = $userId;
        $record->save();
    }

    public static function starUser($contestId, $userId, $star) {
        \Yii::$app->db->createCommand("UPDATE t_contest_user SET is_star=:star WHERE contest_id=:contest_id AND user_id=:user_id")
            ->bindValue(':star', $star)
            ->bindValue(':contest_id', $contestId)
            ->bindValue(':user_id', $userId)
            ->execute();
    }

    public static function getUserList($contestId) {
        $userList = [];

        $records = (new Query())
            ->select('user_id as id, is_star, username, nickname')
            ->from('t_contest_user')
            ->join('INNER JOIN', 't_user', 't_contest_user.user_id = t_user.id')
            ->where(['t_contest_user.contest_id' => $contestId])
            ->all();

        foreach ($records as $record) {
            $record['is_star'] = (int)$record['is_star'];
            $userList[$record['id']] = $record;
        }

        return $userList;
    }
}