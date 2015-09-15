<?php
/**
 * 分类树行结构类库
 *
 * PHP Version 5
 *
 * @category  WAP
 * @package   CategoryTree
 * @author    linxinliang <linxinliang@iyangpin.com>
 * @time      15/4/20 下午4:25
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      linxinliang@iyangpin.com
 */

namespace backend\models\i500m;

/**
 * CategoryTree
 *
 * @category ADMIN
 * @package  CategoryTree
 * @author   linxinliang <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     linxinliang@iyangpin.com
 */

class CategoryTree
{

    private $_lists;                                                           //分类的数据表模型
    private $_rawList = array();                                              //原始的分类数据
    private $_formatList = array();                                           //格式化后的分类
    private $_icon = array('&nbsp;&nbsp;│', '&nbsp;&nbsp;├ ', '&nbsp;&nbsp;└ ');//格式化的字符
    private $_fields = array();//字段映射，分类id，上级分类fid,分类名称name,格式化后分类名称fullname

    /**
     * 构造函数
     * @param string $lists  全部数据
     * @param array  $fields 字段名
     */
    public function __construct($lists = '', $fields = array())
    {
        $this->_lists              = $lists;
        $this->_fields['cid']      = $fields['0'] ? $fields['0'] : 'cid';
        $this->_fields['fid']      = $fields['1'] ? $fields['1'] : 'fid';
        $this->_fields['name']     = $fields['2'] ? $fields['2'] : 'name';
        $this->_fields['fullname'] = $fields['3'] ? $fields['3'] : 'fullname';
    }

    /**
     * 获取所有的信息
     * @return array
     */
    private function _findAllCat()
    {
        $this->_rawList = $this->_lists;
    }

    /**
     * 返回给定上级分类$fid的所有同一级子分类
     * @param int $fid 传入要查询的fid
     * @return array
     */
    public function getChild($fid=0)
    {
        $childs = array();
        foreach ($this->_rawList as $Category) {
            if ($Category[$this->_fields['fid']] == $fid) {
                $childs[] = $Category;
            }
        }
        return $childs;
    }

    /**
     * 递归格式化分类前的字符
     * @param int    $cid   分类ID
     * @param string $space 空格
     * @return string
     */
    private function _searchList($cid = 0, $space = "")
    {
        $childs = $this->getChild($cid);
        //下级分类的数组
        //如果没下级分类，结束递归
        if (!($n = count($childs))) {
            return;
        }
        $m = 1;
        //循环所有的下级分类
        for ($i = 0; $i < $n; $i++) {
            $pre = "";
            $pad = "";
            if ($n == $m) {
                $pre = $this->_icon[2];
            } else {
                $pre = $this->_icon[1];
                $pad = $space ? $this->_icon[0] : "";
            }
            $childs[$i][$this->_fields['fullname']] = ($space ? $space . $pre : "") . $childs[$i][$this->_fields['name']];
            $this->_formatList[] = $childs[$i];
            $this->_searchList($childs[$i][$this->_fields['cid']], $space . $pad . "&nbsp;&nbsp;"); //递归下一级分类
            $m++;
        }
    }

    /**
     * 不采用数据模型时，可以从外部传递数据，得到递归格式化分类
     * @param int $cid 分类ID
     * @return array
     */
    public function getList($cid = 0)
    {
        unset($this->_rawList, $this->_formatList);
        $this->_findAllCat();
        $this->_searchList($cid);
        return $this->_formatList;
    }
}
