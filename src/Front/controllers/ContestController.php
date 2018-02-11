<?php

namespace app\controllers;

use app\common\Util;
use app\models\Contest;
use app\models\ContestProblem;
use app\models\ContestUser;
use app\models\Discuss;
use app\models\LanguageType;
use app\models\Problem;
use app\models\Status;
use app\models\User;
use yii\db\Exception;
use yii\web\NotFoundHttpException;

class ContestController extends BaseController {

    public function actionIndex($id = 1) {
        $pageSize = \Yii::$app->params['queryPerPage'];

        $whereArray = [];
        $andWhereArray = [];
        $search = \Yii::$app->request->get('search');
        if ($search) {
            $andWhereArray = ['or', ['like', 'title', '%'.$search.'%', false], ['like', 'manager', '%'.$search.'%', false]];
        }

        $totalPage = Contest::totalPage($whereArray, $pageSize, $andWhereArray);
        $contests = Contest::getContests($id, $pageSize, $whereArray, $andWhereArray);

        $pageArray = Util::getPaginationArray($id, 8, $totalPage);

        foreach($contests as &$contest) {
            $contest['duration'] = Util::getDuration($contest['start_time'], $contest['end_time']);
            $length = strtotime($contest['end_time']) - strtotime($contest['start_time']);
            $now = time();
            if ($now < strtotime($contest['start_time']))
                $percent = 0;
            else if ($now > strtotime($contest['end_time']))
                $percent = 100;
            else
                $percent = floor(($now - strtotime($contest['start_time'])) / $length * 100);
            $contest['percent'] = $percent;
        }

        $this->smarty->assign('webTitle', "Contest");
        $this->smarty->assign('contests', $contests);
        $this->smarty->assign('search', $search);
        $this->smarty->assign('pageArray', $pageArray);
        $this->smarty->assign('totalPage', $totalPage);
        $this->smarty->assign('pageNow', $id);
        return $this->smarty->display('contest/contest.html');
    }

    public function actionView($id = 0) {
        $contest = Contest::findById($id);
        if (!$contest) {
            throw new NotFoundHttpException("$id 这个比赛不存在！");
        }

        $canNotView = json_decode($this->actionCanView($id))->code;
        if ($canNotView != 0) {
            $this->smarty->assign('canNotView', $canNotView);
            $this->smarty->assign('contest', $contest);

            return $this->smarty->display('contest/view.html');
            $this->smarty->assign('msg', "你还没有访问比赛 $id 的权限");
            return $this->smarty->display('common/error.html');
        }

        $problems = ContestProblem::find()
            ->select('*')
            ->where(['contest_id' => $id])
            ->orderBy('lable')
            ->all();

        $problemList = [];

        $acArray = $this->getAcArray($id);

        foreach ($problems as $problem) {
            $record = [];
            $record['id'] = $problem->problem_id;
            $record['lable'] = $problem->lable;
            $record['total_submit'] = $problem->total_submit;
            $record['total_ac'] = $problem->total_ac;
            $record['name'] = Problem::find()->select('title')->where(['id' => $problem->problem_id])->one()->title;
            $problemList[] = $record;
        }

        $this->smarty->assign('canNotView', $canNotView);
        $this->smarty->assign('contest', $contest);
        $this->smarty->assign('problemList', $problemList);
        $this->smarty->assign('acArray', $acArray);
        $this->smarty->assign('webTitle', "Contest $id");
        return $this->smarty->display('contest/view.html');
    }

    public function actionProblem($id, $page = 'A') {


        $contest = Contest::findById($id);
        if (!$contest) {
            throw new NotFoundHttpException("$id 这个比赛不存在！");
        }

        $canNotView = json_decode($this->actionCanView($id))->code;
        if ($canNotView != 0) {
            return $this->redirect("http://".$_SERVER['HTTP_HOST']."/contest/$id");
            $this->smarty->assign('msg', "你还没有访问比赛 $id 的权限");
            return $this->smarty->display('common/error.html');
        }

        $pid = ContestProblem::find()->select('problem_id')->where(['contest_id' => $id, 'lable' => $page])->one();
        if (!pid)
            throw new NotFoundHttpException("$page 这个题目不存在！");
        $pid = $pid->problem_id;
        $problem = Problem::findById($pid);
        if (!$problem) {
            throw new NotFoundHttpException("不存在这个题目");
        }

        $lables = ContestProblem::find()->select('lable')->where(['contest_id' => $id])->orderBy('lable')->all();

        $this->smarty->assign('vmMultiplier', \Yii::$app->params['vmMultiplier']);
        $this->smarty->assign('problem', $problem);
        $this->smarty->assign('contestId', $id);
        $this->smarty->assign('languageTypeList', LanguageType::find()->all());
        $this->smarty->assign('lables', $lables);
        $this->smarty->assign('page', $page);

        $this->smarty->assign('contest', $contest);
        $this->smarty->assign('webTitle', "Contest $id");
        return $this->smarty->display('contest/problem.html');
    }

    public function actionStatus($id, $page = 1) {
        $contest = Contest::findById($id);
        if (!$contest) {
            throw new NotFoundHttpException("$id 这个比赛不存在！");
        }

        $canNotView = json_decode($this->actionCanView($id))->code;
        if ($canNotView != 0) {
            return $this->redirect("http://".$_SERVER['HTTP_HOST']."/contest/$id");
            $this->smarty->assign('msg', "你还没有访问比赛 $id 的权限");
            return $this->smarty->display('common/error.html');
        }

        $pidToLable = [];
        $lableToPid = [];
        $titleList = [];


        $lableList = ContestProblem::find()
            ->select('problem_id, lable')
            ->where(['contest_id' => $id])
            ->orderBy('lable')
            ->all();
        foreach ($lableList as $lable) {
            $pidToLable[$lable->problem_id] = $lable->lable;
            $lableToPid[$lable->lable] = $lable->problem_id;

            $record = [];
            $record['lable'] = $lable->lable;
            $record['title'] = Problem::find()->select('title')->where(['id' => $lable->problem_id])->one()->title;
            $titleList[] = $record;
        }


        $pageSize = \Yii::$app->params['queryPerPage'];

        $langList = LanguageType::getLangList();

        $whereArray = [];
        $whereArray["contest_id"] = $id;
        if ($prob = \Yii::$app->request->get('prob'))
            $whereArray['problem_id'] = $lableToPid[$prob];
        if ($name = \Yii::$app->request->get('name')) {
            $user = User::findByUsername($name);
            if ($user)
                $whereArray['user_id'] = $user->id;
            else
                $whereArray['user_id'] = 0;
        }
        if ($lang = \Yii::$app->request->get('lang'))
            $whereArray['language_id'] = $lang;
        if ($result = \Yii::$app->request->get('result'))
            $whereArray['result'] = $result;

        if ($contest->hide_others && time() < strtotime($contest->end_time)) {
            if ($contest->manager != Util::getUserName()) {
                if (isset($whereArray['user_id'])) {
                    if ($whereArray['user_id'] != Util::getUser())
                        $whereArray['user_id'] = 0;
                }
                else
                    $whereArray['user_id'] = Util::isLogin() ? Util::getUser() : 0;
            }
        }

        $totalPage = Status::totalPage($whereArray, $pageSize);

        $statuses = Status::getStatuses($page, $pageSize, $whereArray);

        $pageArray = Util::getPaginationArray($page, 8, $totalPage);

        $this->smarty->assign('prob', $prob);
        $this->smarty->assign('name', $name);
        $this->smarty->assign('lang', $lang);
        $this->smarty->assign('result', $result);

        $this->smarty->assign('langList', $langList);
        $this->smarty->assign('webTitle', 'Status');
        $this->smarty->assign('pageArray', $pageArray);
        $this->smarty->assign('totalPage', $totalPage);
        $this->smarty->assign('pageNow', $page);
        $this->smarty->assign('statuses', $statuses);
        $this->smarty->assign('pidToLable', $pidToLable);
        $this->smarty->assign('titleList', $titleList);

        $this->smarty->assign('contest', $contest);
        $this->smarty->assign('webTitle', "Contest $id");
        return $this->smarty->display('contest/status.html');
    }

    public function actionRank($id) { // TODO
        $contest = Contest::findById($id);
        if (!$contest) {
            throw new NotFoundHttpException("$id 这个比赛不存在！");
        }

        $canNotView = json_decode($this->actionCanView($id))->code;
        if ($canNotView != 0) {
            return $this->redirect("http://".$_SERVER['HTTP_HOST']."/contest/$id");
            $this->smarty->assign('msg', "你还没有访问比赛 $id 的权限");
            return $this->smarty->display('common/error.html');
        }

        $problems = ContestProblem::getProblemLableId($id);

        $this->smarty->assign('contest', $contest);
        $this->smarty->assign('problems', $problems);
        $this->smarty->assign('webTitle', "Contest $id");
        return $this->smarty->display('contest/rank.html');
    }

    public function actionDiscuss($id, $page = 1) {
        $contest = Contest::findById($id);
        if (!$contest) {
            throw new NotFoundHttpException("$id 这个比赛不存在！");
        }

        $canNotView = json_decode($this->actionCanView($id))->code;
        if ($canNotView != 0) {
            return $this->redirect("http://".$_SERVER['HTTP_HOST']."/contest/$id");
            $this->smarty->assign('msg', "你还没有访问比赛 $id 的权限");
            return $this->smarty->display('common/error.html');
        }


        $lables = ContestProblem::find()->select('lable')->where(['contest_id' => $id])->orderBy('lable')->all();


        $pageSize = \Yii::$app->params['queryPerPage'];

        $whereArray = ["contest_id" => $id];
        $andWhereArray = [];
        $discussList = Discuss::getDiscussList($page, $pageSize, $whereArray, $andWhereArray);

        $totalPage = Discuss::totalPage($whereArray, $andWhereArray, $pageSize);
        $pageArray = Util::getPaginationArray($page, 8, $totalPage);

        $this->smarty->assign('discussList', $discussList);

        $this->smarty->assign('pageArray', $pageArray);
        $this->smarty->assign('totalPage', $totalPage);
        $this->smarty->assign('pageNow', $page);


        $this->smarty->assign('contest', $contest);
        $this->smarty->assign('lables', $lables);
        $this->smarty->assign('webTitle', "Contest $id");
        return $this->smarty->display('contest/discuss.html');
    }

    /** 判断是否有查看这个比赛的权限,
     * @param $id
     * @return string 若可以查看则code为0，否则code为1
     */
    public function actionCanView($id) {
        $contest = Contest::findById($id);
        if (!$contest)
            return json_encode(["code" => 1, "data" => "所查比赛不存在"]);

        if (!$contest->is_private)
            return json_encode(["code" => 0, "data" => ""]);

        if (isset(\Yii::$app->session["cid:$id"]) && \Yii::$app->session["cid:$id"]) {
            return json_encode(["code" => 0, "data" => ""]);
        } else {
            if (Util::isLogin()) { // 登录后比赛创建者和正确输入密码的人可以访问比赛
                if ($contest->owner_id == Util::getUser() || ContestUser::haveUser($id, Util::getUser()))
                    return json_encode(["code" => 0, "data" => ""]);
                else
                    return json_encode(["code" => 1, "data" => ""]);
            } else {
                return json_encode(["code" => 1, "data" => ""]);
            }
        }
    }

    public function actionLogin() {
        $contestId = \Yii::$app->request->post('contestId', 0);
        $password = \Yii::$app->request->post('password', '');
        $contest = Contest::findById($contestId);

        if (!$contest)
            return json_encode(["code" => 1, "data" => "比赛不存在"]);
        if ($contest->password != $password)
            return json_encode(["code" => 1, "data" => "密码错误"]);

        // 比赛存在且密码输入正确
        \Yii::$app->session["cid:$contestId"] = true;
        if (Util::isLogin()) {
            ContestUser::addUser($contestId, Util::getUser());
        }
        return json_encode(["code" => 0, "data" => ""]);
    }

    public function actionAdd($id = 0) {

        if (!Util::isLogin()) {
            $this->smarty->assign('msg', "请先登录");
            return $this->smarty->display('common/error.html');
        }

        $contestId = null;
        $problemList = [];

        if ($id != 0) {
            $contest = Contest::findById($id);
            if (!$contest)
                throw new NotFoundHttpException("$id 这个比赛不存在！");
            if ($contest->owner_id != Util::getUser()) {
                $this->smarty->assign('msg', "比赛 $id 不是你创建的，你无法修改！");
                return $this->smarty->display('common/error.html');
            }
            $problemList = ContestProblem::getProblemList($id);
            $contestId = $id;

            $startSecond = strtotime($contest->start_time); // 比赛开始的时间戳

            $startDate = date("Y-m-d", $startSecond); // 比赛开始的日期
            $startHour = floor(($startSecond - strtotime($startDate)) / 3600);
            $startMin = floor(($startSecond - strtotime($startDate)) % 3600 / 60);

            $endSecond = strtotime($contest->end_time);
            $length = $endSecond - $startSecond;

            $lengthDate = floor($length / 86400);
            $lengthHour = floor($length % 86400 / 3600);
            $lengthMin = floor($length % 3600 / 60);

            $lockSecond = strtotime($contest->lock_board_time);
            $lockLength = $lockSecond - $startSecond;

            $lockLengthDate = floor($lockLength / 86400);
            $lockLengthHour = floor($lockLength % 86400 / 3600);
            $lockLengthMin = floor($lockLength % 3600 / 60);

            $this->smarty->assign('contest', $contest);
            $this->smarty->assign('startDate', $startDate);
            $this->smarty->assign('startHour', $startHour);
            $this->smarty->assign('startMin', $startMin);
            $this->smarty->assign('lengthDate', $lengthDate);
            $this->smarty->assign('lengthHour', $lengthHour);
            $this->smarty->assign('lengthMin', $lengthMin);
            $this->smarty->assign('lockLengthDate', $lockLengthDate);
            $this->smarty->assign('lockLengthHour', $lockLengthHour);
            $this->smarty->assign('lockLengthMin', $lockLengthMin);
        }
        $this->smarty->assign('webTitle', 'Add Contest');
        $this->smarty->assign('problemList', $problemList);
        $this->smarty->assign('contestId', $contestId);
        return $this->smarty->display('contest/add.html');
    }

    public function actionDoAdd() {
        if (!Util::isLogin())
            json_encode(['code' => 1, 'data' => '请先登录']);

        $title = \Yii::$app->request->post('title');
        $beginTime = (int)\Yii::$app->request->post('beginTime');
        $length = (int)\Yii::$app->request->post('length');
        $lockBoardTime = (int)\Yii::$app->request->post('lockBoardTime');
        $password = \Yii::$app->request->post('password');
        $penalty = \Yii::$app->request->post('penalty');
        $hideOthers = (int)\Yii::$app->request->post('hideOthers');
        $description = \Yii::$app->request->post('description');
        $announcement = \Yii::$app->request->post('announcement');
        $problemList = \Yii::$app->request->post('problemList');
        $gold = (int)\Yii::$app->request->post('gold');
        $silver = (int)\Yii::$app->request->post('silver');
        $bronze = (int)\Yii::$app->request->post('bronze');

        $contestId = \Yii::$app->request->post('contestId');


        try {
            if ($contestId) {
                $contest = Contest::findById($contestId);
                if (!$contest)
                    return json_encode(['code' => 1, 'data' => '比赛不存在']);
                if ($contest->owner_id != Util::getUser())
                    return json_encode(['code' => 1, 'data' => '无法修改，比赛非你建']);
            } else {
                $contest = new Contest();
            }
            $contest->title = $title;
            $contest->description = $description;
            $contest->announcement = $announcement;
            $contest->is_private = $password ? true : false;
            $contest->start_time = date("Y-m-d H:i:s", $beginTime);
            $contest->end_time = date("Y-m-d H:i:s", $beginTime + $length);
            $contest->penalty = $penalty;
            $contest->lock_board_time = date("Y-m-d H:i:s", $beginTime + $lockBoardTime);
            $contest->hide_others = $hideOthers;
            $contest->owner_id = Util::getUser();
            $contest->manager = Util::getUserName();
            $contest->password = $password;
            $contest->gold = $gold;
            $contest->silver = $silver;
            $contest->bronze = $bronze;

            $contest->save();

            ContestProblem::updateProblemList($contest->id, $problemList);

            if ($contestId)
                $msg = "比赛 $contest->id 修改成功";
            else
                $msg = "比赛 $contest->id 创建成功";
            return json_encode(['code' => 0, 'data' => ["msg" => $msg, "id" => $contest->id]]);
        } catch (Exception $e) {
            return json_encode(['code' => 1, 'data' => $e->getMessage()]);
        }
    }

    public function actionStar() {
        $contestId = \Yii::$app->request->post('contestId', 0);
        $userList = \Yii::$app->request->post('userList', []);
        $star = (int)\Yii::$app->request->post('star');

        $starStr = $star ? "打星" : "取消打星";
        $contest = Contest::findById($contestId);
        if (!$contest)
            return json_encode(['code' => 1, "data" => "没有 $contestId 这个比赛"]);
        if ($contest->manager != Util::getUserName())
            return json_encode(['code' => 1, "data" => "没有权限 $starStr"]);

        $failList = [];
        foreach ($userList as $username) {
            $user = User::find()->select('id')->where(['username' => $username])->one();
            if ($user && ContestUser::haveUser($contestId, $user->id))
                ContestUser::starUser($contestId, $user->id, $star);
            else
                $failList[] = $username;
        }

        if (count($failList) == 0) {
            $data = "全部 $starStr 成功";
            return json_encode(['code' => 0, 'data' => $data]);
        } else {
            $data = "这些用户：".join(",", $failList)." $starStr 失败";
            return json_encode(['code' => 1, 'data' => $data]);
        }

    }

    private function getAcArray($id) {
        $problems = ContestProblem::find()
            ->select('*')
            ->where(['contest_id' => $id])
            ->orderBy('lable')
            ->all();

        $acArray = [];
        if (Util::isLogin()) {
            foreach ($problems as $problem) {
                $acStatus = Status::find()
                    ->select('id')
                    ->where(['contest_id' => $id, 'problem_id' => $problem->problem_id, 'user_id' => Util::getUser(), 'result' => 'Accepted'])
                    ->one();
                if ($acStatus) {
                    $acArray[] = $problem->problem_id;
                }
            }
        }
        return $acArray;
    }

    private function getRankStauts() {

    }
}