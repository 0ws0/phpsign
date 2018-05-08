<?php
namespace SignLib;

use GuzzleHttp\Client;

class ApiSign
{
    const APP_SECRET    = '1-13y!';
    const SIGN_LIFETIME = 1000;

    public function test()
    {
        $client = new Client();
        $res    = $client->request('GET', 'https://api.github.com/repos/guzzle/guzzle');
        echo $res->getStatusCode();
    }

    /**
     * 生成签名算法
     * @param  array $data 生成签名数据
     * @return string      签名串
     */
    public function createSign($data)
    {
        // 1. 对加密数组进行字典排序
        ksort($data); //字典排序的作用就是防止因为参数顺序不一致而导致下面拼接加密不同

        // 2. 将Key和Value拼接
        $str = "";
        foreach ($data as $k => $v) {
            $str .= $k . $v;
        }

        //3. 通过sha1加密并转化为大写
        //4. 大写获得签名
        $restr = $str . self::APP_SECRET;
        $sign  = strtoupper(sha1($restr));

        return $sign;
    }

    /**
     * 校验签名
     * @param  [type]  $data       校验传参数据
     * @param  [type]  $sign       签名
     * @param  boolean $signEnable 是否校验签名
     * @return [type]              是否校验成功
     */
    public function checkSign($data, $sign, $signEnable = true)
    {
        $result = false;

        if ($signEnable == false) {
            return true;
        }

        if (!is_array($data) || !$sign) {
            return false;
        }

        if (time() - $data['timestamp'] > self::SIGN_LIFETIME) //同一签名调用时间限制
        {
            return false;
        }

        $createSign = self::createSign($data);
        if ($createSign == $sign) {
            $result = true;
        }

        return $result;
    }
}
