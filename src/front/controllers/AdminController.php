<?php
/**
 * Created by PhpStorm.
 * User: torapture
 * Date: 17-12-7
 * Time: 下午7:25
 */

namespace app\controllers;


use app\models\LanguageType;

class AdminController extends BaseController {
    public function actionIndex() {
        $languageList = LanguageType::find()
            ->select('*')
            ->all();

        $this->smarty->assign('languageList', $languageList);
        return $this->smarty->display('admin/admin.html');
    }
}