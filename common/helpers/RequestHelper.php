<?php
/**
 * Created by PhpStorm.
 * User: lbc
 * Date: 15/3/17
 * Time: 上午10:42
 */

namespace common\helpers;


class RequestHelper extends BaseRequestHelps
{
    /**
     * 简介：无限分类返回一唯数组
     * @author  lichenjun@iyangpin.com。
     * @param array  $cate   数组
     * @param string $html   格式
     * @param array  $select xx
     * @param int    $pid    父ID
     * @param int    $level  等级
     * @return array
     */
    public static function unlimitedForLevel($cate, $html = '|--', $select = array(), $pid = 0, $level = 0)
    {
        $arr = array();
        foreach ($cate as $v) {
            if (!empty($select)) {
                $v['select'] = in_array($v['id'], $select) ? 1 : 0;
            }
            if ($v['pid'] == $pid) {
                $v['level'] = $level + 1;
                $v['html'] = str_repeat($html, $level);
                $arr[] = $v;
                $arr = array_merge($arr, self::unlimitedForLevel($cate, $html, $select, $v['id'], $level + 1));

            }
        }
        return $arr;
    }
}