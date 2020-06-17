<?php
declare (strict_types=1);

namespace suframe\oss;

use think\Route;

class OssService extends \think\Service
{

    /**
     * 执行服务
     *
     * @return mixed
     */
    public function boot()
    {
        $adminMiddleware = config('oss.middleware');
        $url = config('oss.url', 'oss/upload');
        $route = \think\facade\Route::post($url, function () {
            return Oss::execute();
        });
        if (!$adminMiddleware) {
            return true;
        }
        if (is_callable($adminMiddleware)) {
            $adminMiddleware = $adminMiddleware ? $adminMiddleware() : [];
        }
        $route->middleware($adminMiddleware);
    }
}
