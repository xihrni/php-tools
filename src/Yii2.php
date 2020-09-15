<?php

namespace xihrni\tools;

use Yii;
use yii\db\Query;

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
                    static::getFullModuleId($module->module, $ids);
                }
            }
        }

        return $ids;
    }

    /**
     * 备份数据库表为sql文件
     *
     * @param  string $table 表名
     * @return bool|int
     */
    public static function backupDataTableForSql($table)
    {
        // 备份当前表数据
        $rawTable = Yii::$app->db->schema->getRawTableName($table);
        $query    = (new Query)->from($table)->orderBy('id');

        // 无数据则不备份
        if (!$query->count()) {
            return true;
        }

        // 批量插入语句
        $insert   = '#' . "\r\n" . '# Data for table "' . $rawTable . '"' . "\r\n" . '#' . "\r\n\r\n" . 'INSERT INTO `'
                  . $rawTable . '` VALUES ';

        $values   = '';
        foreach ($query->each() as $row) {
            $value = '(';
            foreach ($row as $v) {
                switch (gettype($v)) {
                    // TODO：其他类型
                    case 'integer': $value .= $v . ','       ; break;
                    case 'string' : $value .= '"' . $v . '",'; break;
                    case 'NULL'   : $value .= 'NULL,'        ; break;
                }
            }
            $value   = rtrim($value, ',');
            $value  .= '),';
            $values .= $value;
        }

        $insert  .= rtrim($values, ',') . ';';

        // 文件目录和命名
        $filePath = Yii::getAlias('@runtime') . DIRECTORY_SEPARATOR . 'backup' . DIRECTORY_SEPARATOR;
        $fileName = $rawTable . '_' . date('YmdHis') . '.sql';
        // 目录不存在则创建
        !is_dir($filePath) && mkdir($filePath, 0777, true);
        // 创建文件
        return file_put_contents($filePath . $fileName, $insert);
    }
}
