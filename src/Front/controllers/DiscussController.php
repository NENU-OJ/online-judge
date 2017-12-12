<?php
/**
 * Created by PhpStorm.
 * User: torapture
 * Date: 17-12-12
 * Time: 下午12:08
 */

namespace app\controllers;

use app\models\Discuss;
use app\models\DiscussReply;
use app\models\User;
use app\common\Util;
use yii\db\Exception;
use yii\web\NotFoundHttpException;

class DiscussController extends BaseController {
    public function actionList($id = 1) {
        $pageSize = \Yii::$app->params['queryPerPage'];

        $whereArray = ["contest_id" => 0];
        $andWhereArray = [];
        $search = \Yii::$app->request->get('search');
        if ($search) {
            $andWhereArray = ['or',
                ['like', 't_discuss.title', '%'.$search.'%', false],
                ['like', 't_discuss.username', '%'.$search.'%', false]];
        }

        $discussList = Discuss::getDiscussList($id, $pageSize, $whereArray, $andWhereArray);

        $totalPage = Discuss::totalPage($whereArray, $andWhereArray, $pageSize);
        $pageArray = Util::getPaginationArray($id, 8, $totalPage);

        $this->smarty->assign('discussList', $discussList);

        $this->smarty->assign('search', $search);
        $this->smarty->assign('pageArray', $pageArray);
        $this->smarty->assign('totalPage', $totalPage);
        $this->smarty->assign('pageNow', $id);

        return $this->smarty->display('discuss/discuss.html');
    }


    public function actionView($id) {

        $discuss = Discuss::findById($id);

        if (!$discuss)
            throw new NotFoundHttpException("没有 $id 这个讨论");

        $replyList = DiscussReply::find()
            ->select('*')
//            ->where(['discuss_id' => $discuss->id])
            ->orderBy('id DESC')
            ->all();

        $first = true;


        $this->smarty->assign('first', $first);
        $this->smarty->assign('discuss', $discuss);
        $this->smarty->assign('replyList', $replyList);

        return $this->smarty->display('discuss/view.html');
    }

    public function actionAdd() {
        if (!isset(\Yii::$app->session['user_id'])) {
            $this->smarty->assign('msg', "请先登录");
            return $this->smarty->display('common/error.html');
        }

        return $this->smarty->display('discuss/add.html');
    }

    public function actionCreate() {
        if (!\Yii::$app->request->isPost)
            return json_encode(["code" => 1, "data" => "请求方式错误"]);

        if (!isset(\Yii::$app->session['user_id']))
            return json_encode(["code" => 1, "data" => "请先登录"]);

        $userId = \Yii::$app->session['user_id'];
        $username = \Yii::$app->session['username'];
        $priority = (int)\Yii::$app->request->post('priority');
        if ($priority && !User::isRoot($userId))
            return json_encode(["code" => 1, "data" => "管理员才可以置顶"]);

        $createdAt = date("Y-m-d H:i:s");

        try {
            $discuss = new Discuss();
            $discuss->contest_id = \Yii::$app->request->post('contestId', 0);
            $discuss->created_at = $createdAt;
            $discuss->replied_at = $createdAt;
            $discuss->username = $username;
            $discuss->title = \Yii::$app->request->post('title');
            $discuss->priority = $priority;
            $discuss->replied_num = 1;
            $discuss->save();

            $discussReply = new DiscussReply();
            $discussReply->discuss_id = $discuss->id;
            $discussReply->created_at = $createdAt;
            $discussReply->content = \Yii::$app->request->post('content');
            $discussReply->username = $username;
            $discussReply->save();

            return json_encode(["code" => 0, "data" => $discuss->id]);
        } catch (Exception $e) {
            return json_encode(["code" => 1, "data" => $e->getMessage()]);
        }
    }
}