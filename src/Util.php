<?php
/**
 * Created by PhpStorm.
 * User: jonas
 * Date: 03.11.2016
 * Time: 20:32
 */

namespace JGerdes\SchauBot;


class Util {

    static function contains($haystack, $needle) {
        return (strpos($haystack, $needle) !== false);
    }

}