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
}
