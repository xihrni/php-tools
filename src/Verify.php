<?php

namespace xihrni\tools;

/**
 * 验证类
 *
 * Class Verify
 * @package xihrni\tools
 */
class Verify
{
    /**
     * 验证身份证号码
     *
     * @param  string $number 身份证号码
     * @return boolean
     */
    public static function idCardNumber($number)
    {
        if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $number)) {
            return false;
        }

        // 加权因子
        $factor = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
        // 校验码对应值
        $vcode  = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'];
        $length = strlen($number);
        if ($length == 15) {
            // 如果身份证顺序码是 996、997、998、999 这些是为百岁以上老人的特殊编码
            if (array_search(substr($number, 12, 3), ['996', '997', '998', '999']) !== false) {
                $number = substr($number, 0, 6) . '18' . substr($number, 6, 9);
            } else {
                $number = substr($number, 0, 6) . '19' . substr($number, 6, 9);
            }

            $sum    = 0;
            for ($i = 0; $i < strlen($number); $i++) {
                $sum += substr($number, $i, 1) * $factor[$i];
            }

            $number = $number . $vcode[$sum % 11];
        }

        $body = substr($number, 0, 17);
        $code = strtoupper(substr($number, 17, 1));
        $sum  = 0;
        for ($i = 0; $i < strlen($body); $i++) {
            $sum += substr($body, $i, 1) * $factor[$i];
        }

        if ($code != $vcode[$sum % 11]) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 是否为微信客户端（Windows Phone 暂无法识别）
     *
     * @return bool
     */
    public static function isWechat()
    {
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);

        if (strpos($agent, 'micromessenger')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 是否为手机客户端
     *
     * @return bool
     */
    public static function isMobile()
    {
        // 如果有 HTTP_X_WAP_PROFILE 则一定是移动设备
        if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }

        // 如果 via 信息含有 wap 则一定是移动设备,部分服务商会屏蔽该信息
        if (isset($_SERVER['HTTP_VIA'])) {
            // 找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], 'wap') ? true : false;
        }

        // 判断手机发送的客户端标志,兼容性有待提高
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = [
                'nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'mobile'
            ];

            // 从 HTTP_USER_AGENT 中查找手机浏览器的关键字
            if (preg_match('/(' . implode('|', $clientkeywords) . ')/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }

        // 协议法，因为有可能不准确，放到最后判断
        if (isset($_SERVER['HTTP_ACCEPT'])) {
            // 如果只支持 wml 并且不支持 html 那一定是移动设备
            // 如果支持 wml 和 html 但是 wml 在 html 之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            }
        }

        return false;
    }
}
