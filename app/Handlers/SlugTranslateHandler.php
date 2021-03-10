<?php


namespace App\Handlers;

use GuzzleHttp\Client;
use Overtrue\Pinyin\Pinyin;

/**
 * 话题标题转换
 * Class SlugTranslateHandler
 * @package App\Handlers
 */
class SlugTranslateHandler
{


    //中文->英文翻译
    public function translate($text)
    {
        // 实例化 HTTP 客户端
        $http = new Client;

        // 初始化配置信息
        $api   = 'http://api.fanyi.baidu.com/api/trans/vip/translate?';
        $appid = config('services.baidu_translate.appid');
        $key   = config('services.baidu_translate.key');
        $salt  = time();

        // 如果没有配置百度翻译，自动使用兼容的拼音方案
        if (empty($appid) || empty($key)) {
            return $this->pinyin($text);
        }
        $sign = $this->buildSign($appid, $text, $salt, $key);

        // 构建请求参数
        $query = http_build_query([
            "q"     => $text,
            "from"  => "zh",
            "to"    => "en",
            "appid" => $appid,
            "salt"  => $salt,
            "sign"  => $sign,
        ]);

        // 发送 HTTP Get 请求
        $response = $http->get($api . $query);

        $result = json_decode($response->getBody(), true);

        // 尝试获取获取翻译结果
        if (isset($result['trans_result'][0]['dst'])) {
            return \Str::slug($result['trans_result'][0]['dst']);
        } else {
            // 如果百度翻译没有结果，使用拼音作为后备计划。
            return $this->pinyin($text);
        }
    }

    /**
     * 中文转拼音
     * @param $text
     * @return mixed
     */
    public function pinyin($text)
    {
        return \Str::slug(app(Pinyin::class)->permalink($text));
    }

    /**
     *  根据文档，生成 sign
     * @param $query
     * @param $appID
     * @param $salt
     * @param $secKey
     * @return string
     */
    protected function buildSign($appid, $text, $salt, $key)
    {
        //根据文档，生成 sign
        //http://api.fanyi.baidu.com/api/trans/product/apidoc
        // appid+q+salt+密钥 的MD5值
        $str  = $appid . $text . $salt . $key;
        $sign = md5($str);
        return $sign;
    }
}