<?php
/**
 * Created by PhpStorm.
 * User: torapture
 * Date: 17-12-7
 * Time: 下午7:25
 */

namespace app\controllers;


use app\models\LanguageType;
use app\models\User;

class AdminController extends BaseController {
    public function actionIndex() {

        $canView = false;
        if (isset(\Yii::$app->session['user_id'])) {
            $userId = \Yii::$app->session['user_id'];
            $user = User::findById($userId);
            if ($user && $user->is_root)
                $canView = true;
        }

        if (!$canView) {
            $this->smarty->assign('msg', "管不了 管不了.jpg");
            return $this->smarty->display('common/error.html');
        }

        $languageList = LanguageType::find()
            ->select('*')
            ->all();

        $this->smarty->assign('languageList', $languageList);
        return $this->smarty->display('admin/admin.html');
    }
}