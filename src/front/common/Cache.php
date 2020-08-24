<?php
/**
 * Created by PhpStorm.
 * User: torapture
 * Date: 18-2-12
 * Time: 下午2:44
 */

namespace app\common;


use Memcached;

class Cache {

    static public function set($key, $value, $expire = 0) {
        $memcache = new Memcached();
        if ($memcache->addServer(\Yii::$app->params['memcached']['host'], \Yii::$app->params['memcached']['port'])) {
            $memcache->set($key, $value, $expire);
        }
    }

    static public function get($key) {
        $memcache = new Memcached();
        if ($memcache->addServer(\Yii::$app->params['memcached']['host'], \Yii::$app->params['memcached']['port'])) {
            return $memcache->get($key);
        }
        return false;
    }
}