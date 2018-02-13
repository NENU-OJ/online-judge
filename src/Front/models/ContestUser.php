<?php
/**
 * Created by PhpStorm.
 * User: torapture
 * Date: 18-2-7
 * Time: 下午6:38
 */

namespace app\models;


use yii\db\ActiveRecord;

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
        $recordList = self::find()->select('user_id, is_star')->where(['contest_id' => $contestId])->all();
        foreach ($recordList as $raw) {
            $record = [];
            $record['id'] = $raw->user_id;
            $record['is_star'] = $raw->is_star;
            $user = User::find()->select('username, nickname')->where(['id' => $raw->user_id])->one();
            $record['username'] = $user->username;
            $record['nickname'] = $user->nickname;
            $userList[$record['id']] = $record;
        }
        return $userList;
    }
}