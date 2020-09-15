<?php

namespace xihrni\tools;

/**
 * 数组类
 *
 * Class Arrays
 * @package xihrni\tools
 */
class Arrays
{
    /**
     * 列表转换成树
     *
     * @param  array  $list                     列表数组
     * @param  string [$id = 'id']              主键
     * @param  string [$parentId = 'parent_id'] 父级标识
     * @param  string [$children = 'children']  子集字段
     * @param  string [$topFlag = '']           顶级标识
     * @return array
     */
    public static function list2Tree($list, $id = 'id', $parentId = 'parent_id', $children = 'children', $topFlag = '')
    {
        $tree = [];

        foreach ($list as $row) {
            $tree[$row[$id]] = $row;
        }

        foreach ($tree as $item) {
            // $tree[$item[$parentId]][$children][$item[$id]] // ID为key，转成JSON为对象
            $tree[$item[$parentId]][$children][] = &$tree[$item[$id]];
        }

        return isset($tree[$topFlag][$children]) ? $tree[$topFlag][$children] : [];
    }

    /**
     * 字符串转换为枚举数组
     *
     * ```
     * $operate = '操作，1=>管理员创建卡券，2=>管理员更新卡券，3=>管理员删除卡券，12=>用户使用卡券券码';
     * Arrays::str2Enum($operate, '，', '=>');
     * ```
     *
     * @param  string $str 字符串
     * @param  string [$separator = '，']  分隔符
     * @param  string [$separator2 = '=>'] 分隔符2
     * @return array
     */
    public function str2Enum($str, $separator = '，', $separator2 = '=>')
    {
        $enum  = [];
        $array = explode($separator, $str);
        foreach ($array as $v) {
            $s = explode($separator2, $v);
            $enum[$s[0]] = $s[1];
        };

        return $enum;
    }
}
