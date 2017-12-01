<?php
/**
 * Created by PhpStorm.
 * User: torapture
 * Date: 17-12-1
 * Time: 下午7:28
 */

namespace app\common;


class Util {
    static public function getDirs($target) {
        $ret = [];
        $total = scandir($target);
        foreach ($total as $dir) {
            if ($dir != "." && $dir != "..") {
                $temp = $target.'/'.$dir;
                if (is_dir($temp))
                    array_push($ret, $dir);
            }
        }
        return $ret;
    }
}