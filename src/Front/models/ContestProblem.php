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

    public static function cleanContest($contestId) {
        self::deleteAll(['contest_id' => $contestId]);
    }

    public static function addProblemList($contestId, $problemList) {
        $len = count($problemList);
        for ($i = 0; $i < $len; ++$i) {
            $contestProblem = new ContestProblem();
            $contestProblem->contest_id = $contestId;
            $contestProblem->problem_id = $problemList[$i];
            $contestProblem->lable = chr(ord('A') + $i);
            $contestProblem->save();
        }
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
}