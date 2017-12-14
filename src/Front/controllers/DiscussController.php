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
use yii\db\Query;
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

        $this->smarty->assign('webTitle', 'Discuss List');
        return $this->smarty->display('discuss/discuss.html');
    }


    public function actionView($id) {

        $discuss = Discuss::findById($id);

        if (!$discuss)
            throw new NotFoundHttpException("没有 $id 这个讨论");

        $replyList = (new Query())
            ->select('t_discuss_reply.id AS id,
                        t_discuss_reply.discuss_id AS discuss_id,
                        t_discuss_reply.parent_id AS parent_id,
                        t_discuss_reply.created_at AS created_at,
                        t_discuss_reply.content AS content,
                        t_discuss_reply.username AS username,
                        t_user.avatar AS avatar')
            ->from('t_discuss_reply')
            ->join('INNER JOIN', 't_user', 't_discuss_reply.username = t_user.username')
            ->where(['discuss_id' => $discuss->id, "parent_id" => 0])
            ->orderBy('id')
            ->all();

        foreach ($replyList as &$reply) {
            $reply['subReply'] = (new Query())
                ->select('t_discuss_reply.id AS id,
                        t_discuss_reply.discuss_id AS discuss_id,
                        t_discuss_reply.parent_id AS parent_id,
                        t_discuss_reply.created_at AS created_at,
                        t_discuss_reply.content AS content,
                        t_discuss_reply.username AS username,
                        t_discuss_reply.reply_at AS reply_at,
                        t_user.avatar AS avatar')
                ->from('t_discuss_reply')
                ->join('INNER JOIN', 't_user', 't_discuss_reply.username = t_user.username')
                ->where(['discuss_id' => $discuss->id, "parent_id" => $reply['id']])
                ->orderBy('id')
                ->all();
        }

        $first = true;

        $this->smarty->assign('first', $first);
        $this->smarty->assign('discuss', $discuss);
        $this->smarty->assign('replyList', $replyList);
        $this->smarty->assign('discussId', $id);

        $this->smarty->assign('webTitle', $discuss->title);
        return $this->smarty->display('discuss/view.html');
    }

    public function actionAdd() {
        if (!isset(\Yii::$app->session['user_id'])) {
            $this->smarty->assign('msg', "请先登录");
            return $this->smarty->display('common/error.html');
        }

        $this->smarty->assign('webTitle', 'Add Discuss');
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
    
    public function actionReply() {
        if (!\Yii::$app->request->isPost)
            return json_encode(["code" => 1, "data" => "请求方式错误"]);

        if (!isset(\Yii::$app->session['user_id']))
            return json_encode(["code" => 1, "data" => "请先登录"]);

        $discussId = \Yii::$app->request->post('discuss_id');
        $discuss = Discuss::findById($discussId);
        $createdAt = date("Y-m-d H:i:s");

        if (!$discuss)
            return json_encode(["code" => 1, "data" => "discuss $discussId 不存在"]);

        try {
            $discussReply = new DiscussReply();

            $discussReply->discuss_id = $discussId;
            $discussReply->parent_id = \Yii::$app->request->post('parent_id', 0);
            $discussReply->created_at = $createdAt;
            $discussReply->content = \Yii::$app->request->post('content');
            $discussReply->reply_at = \Yii::$app->request->post('reply_at', '');
            $discussReply->username = \Yii::$app->session['username'];

            $discussReply->save();

            $discuss->replied_at = $createdAt;
            $discuss->replied_user = \Yii::$app->session['username'];
            $discuss->replied_num += 1;
            $discuss->save();


            return json_encode(["code" => 0, "data" => ""]);
        } catch (Exception $e) {
            return json_encode(["code" => 1, "data" => $e->getMessage()]);
        }
    }
    
}