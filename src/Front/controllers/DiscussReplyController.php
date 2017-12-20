<?php
/**
 * Created by PhpStorm.
 * User: torapture
 * Date: 17-12-20
 * Time: 下午5:19
 */

namespace app\controllers;

use app\models\DiscussReply;
use yii\db\Exception;

class DiscussReplyController extends BaseController {
    public function actionDelete() {

        if (!\Yii::$app->request->isPost)
            return json_encode(["code" => 1, "data" => "请求方式错误"]);

        if (!isset(\Yii::$app->session['user_id']))
            return json_encode(["code" => 1, "data" => "请先登录"]);

        $id = \Yii::$app->request->post('id', 0);

        $discussReply = DiscussReply::find()
            ->select('id, username')
            ->where(["id" => $id])
            ->one();

        if (!$discussReply)
            return json_encode(["code" => 1, "data" => "reply $id 不存在"]);

        if ($discussReply->username != \Yii::$app->session['username'])
            return json_encode(["code" => 1, "data" => "你可删不了"]);

        $discussReply->delete();
        return json_encode(["code" => 0, "data" => ""]);
    }

    public function actionUpdate() {

        if (!\Yii::$app->request->isPost)
            return json_encode(["code" => 1, "data" => "请求方式错误"]);

        if (!isset(\Yii::$app->session['user_id']))
            return json_encode(["code" => 1, "data" => "请先登录"]);

        $id = \Yii::$app->request->post('id', 0);
        $content = \Yii::$app->request->post('content');

        $discussReply = DiscussReply::find()
            ->select('*')
            ->where(["id" => $id])
            ->one();

        if (!$discussReply)
            return json_encode(["code" => 1, "data" => "reply $id 不存在"]);

        if ($discussReply->username != \Yii::$app->session['username'])
            return json_encode(["code" => 1, "data" => "你可改不了"]);

        try {
            $discussReply->content = $content;
            $discussReply->update();
            return json_encode(["code" => 0, "data" => ""]);
        } catch (Exception $e) {
            return json_encode(["code" => 1, "data" => $e->getMessage()]);
        }
    }

    public function actionGet($id = 0) {
        $discussReply = DiscussReply::find()
            ->select('content')
            ->where(['id' => $id])
            ->one();

        if (!$discussReply)
            return json_encode(["code" => 1, "data" => "reply $id 不存在"]);

        return json_encode(["code" => 0, "data" => $discussReply->content]);
    }
}