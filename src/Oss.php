<?php
/**
 * +----------------------------------------------------------------------
 * | summer framework
 * +----------------------------------------------------------------------
 * | Copyright (c) 2020 https://github.com/suframe/think-admin All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: summer <806115620@qq.com>  2020/6/17 8:55
 * +----------------------------------------------------------------------
 */

namespace suframe\oss;

use think\Facade;

/**
 * Class Oss
 * @package app\service
 * @method execute
 */
class Oss extends Facade
{

    protected static function getFacadeClass()
    {
        return config('oss.driver');
    }

}