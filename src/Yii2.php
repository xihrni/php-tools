<?php

namespace xihrni\tools;

use Yii;

/**
 * Yii2框架类
 *
 * Class Yii2
 * @package xihrni\tools
 */
class Yii2
{
    /**
     * 获取完整模块ID
     *
     * @param  object $module 模块对象
     * @param  array  $ids    临时存放当前已获取的模块ID
     * @return array
     */
    public static function getFullModuleId($module, array &$ids)
    {
        if (isset($module->id)) {
            $ids[] = $module->id;

            if ($module->module) {
                // 过滤框架ID
                if ($module->module->id != Yii::$app->id) {
                    $this->getFullModuleId($module->module, $ids);
                }
            }
        }

        return $ids;
    }
}
