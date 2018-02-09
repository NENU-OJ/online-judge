<?php
/**
 * Created by PhpStorm.
 * User: torapture
 * Date: 18-2-6
 * Time: 下午2:32
 */

namespace app\models;


use yii\db\ActiveRecord;

class ContestProblem extends ActiveRecord {
    public static function tableName() {
        return "{{%contest_problem}}";
    }

    public static function getProblemList($contestId) {
        $problemList = [];

        $resultList = self::find()
            ->select('*')
            ->where(['contest_id' => $contestId])
            ->orderBy('lable')
            ->all();

        foreach ($resultList as $result)
            $problemList[] = $result->problem_id;

        return $problemList;
    }

    public static function updateProblemList($contestId, $problemList) {
        $beforeList = self::find()
            ->select('*')
            ->where(['contest_id' => $contestId])
            ->orderBy('lable')
            ->all();
        foreach ($beforeList as $before) {
            if (($key = array_search($before->problem_id, $problemList)) !== false) {
                $record = self::find()->select('*')->where(['contest_id' => $contestId, 'problem_id' => $before->problem_id])->one();
                $record->lable = chr(ord('A') + $key);
                $record->save();
            } else {
                self::deleteAll(['contest_id' => $before->contest_id, 'problem_id' => $before->problem_id]);
            }
        }
        $len = count($problemList);
        for ($i = 0; $i < $len; ++$i) {
            $before = self::find()
            ->where(['contest_id' => $contestId, 'problem_id' => $problemList[$i]])
            ->one();
            if (!$before) {
                $record = new ContestProblem();
                $record->contest_id = $contestId;
                $record->problem_id = $problemList[$i];
                $record->lable = chr(ord('A') + $i);
                $record->save();
            }
        }
    }
}