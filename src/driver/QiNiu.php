<?php

namespace suframe\oss\driver;

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use think\facade\Request;

class QiNiu implements OssDriverInterface
{
    protected $token = [];

    /**
     * @param $bucket
     * @return string
     * @throws \Exception
     */
    protected function getToken($bucket)
    {
        if (isset($this->token[$bucket])) {
            return $this->token[$bucket];
        }
        $token = cache('qiNiuToken' . $bucket);
        if (true || !$token) {
            $accessKey = config('oss.qiniu.accessKey');
            $secretKey = config('oss.qiniu.secretKey');
            if (!$accessKey || !$secretKey) {
                throw new \Exception('oss config error');
            }
            $auth = new Auth($accessKey, $secretKey);
            // 生成上传Token
            $token = $auth->uploadToken($bucket);
            cache('qiNiuToken' . $bucket, $token, 3500);
        }
        return $this->token[$bucket] = $token;
    }

    /**
     * @return \think\response\Json
     * @throws \Exception
     */
    public function execute()
    {
        $file = request()->file('file');
        if (!$file) {
            return json_error('upload empty');
        }
        $bucket = \request()->post('bucket', config('oss.qiniu.bucketDefault'));
        $urls = config('oss.qiniu.bucket');
        $bucketUrl = $urls[$bucket] ?? '';
        if (!$bucketUrl) {
            return json_error('need bucket url config');
        }
        $token = $this->getToken($bucket);
        $uploadMgr = new UploadManager();

        $fileName = $file->hashName(function () {
            return date('Ymd') . md5(((string)microtime(true)) . uniqid());
        });
        list($ret, $err) = $uploadMgr->putFile($token, $fileName, $file->getRealPath());
        trace($err);
        if ($err !== null) {
            return json_error($err);
        }
        return json_return([
            'id' => $ret['hash'],
            'filePath' => $bucketUrl . '/' . $fileName,
        ]);
    }

}