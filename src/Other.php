<?php

namespace xihrni\tools;

/**
 * 其他类
 *
 * Class Other
 * @package xihrni\tools
 */
class Other
{
    /**
     * 浏览器友好的变量输出
     *
     * @param  mixed  $var             变量
     * @param  bool   [$echo = true]   是否输出，如果为false，则返回输出字符串
     * @param  string [$label = null]  标签
     * @param  bool   [$strict = true] 是否严谨
     * @return bool|string 空或字符串
     */
    public static function dump($var, $echo = true, $label = null, $strict = true)
    {
        $label = ($label === null) ? '' : rtrim($label) . ' ';
        if (!$strict) {
            if (ini_get('html_errors')) {
                $output = print_r($var, true);
                $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
            } else {
                $output = $label . print_r($var, true);
            }
        } else {
            ob_start();
            var_dump($var);
            $output = ob_get_clean();
            if (!extension_loaded('xdebug')) {
                $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
                $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
            }
        }

        if ($echo) {
            echo $output;
            return null;
        } else {
            return $output;
        }
    }
}
