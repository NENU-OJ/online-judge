<?php
/**
 * Created by PhpStorm.
 * User: shihao
 * Date: 17-8-15
 * Time: 下午2:53
 */

namespace app\vendor;

define('SMARTY_VIEW_DIR', \Yii::$app->basePath.DIRECTORY_SEPARATOR.'views/smarty/');

class BaseSmarty extends \Smarty {
    function __construct() {

        // Class Constructor.
        // These automatically get set with each new instance.

        parent::__construct();

        $this->setTemplateDir(SMARTY_VIEW_DIR.'templates/');
        $this->setCompileDir(SMARTY_VIEW_DIR.'templates_c/');
        $this->setConfigDir(SMARTY_VIEW_DIR.'configs/');
        $this->setCacheDir(SMARTY_VIEW_DIR.'cache/');

        $this->caching = false;
        $this->left_delimiter  =  '<{';
        $this->right_delimiter =  '}>';
        $this->cache_lifetime = 3600;
    }

    function init() {}

}