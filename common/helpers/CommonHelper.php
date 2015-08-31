<?php
/**
 * 共同函数库
 *
 * PHP Version 5
 * 一些共同函数接口
 *
 * @category  I500M
 * @package   Admin
 * @author    renyineng <renyineng@iyangpin.com>
 * @time      15/4/1 下午2:57
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      renyineng@iyangpin.com
 */
namespace common\helpers;

/**
 * Class CommonHelper
 * @category  PHP
 * @package   CommonHelper
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class CommonHelper extends BaseCommonHelps
{

    /**
     * Ajax 返回数据格式
     *
     * @param string $code 代码
     * @param string $msg  提示信息
     * @param array  $data 数组
     *
     * @author linxinliang@iyangpin.com
     * @return array
     */
    static function ajaxReturn($code = '', $msg = '', $data = [])
    {
        $rs = [];
        $rs['code'] = !empty($code) ? $code : 'ok';
        $rs['msg'] = !empty($msg) ? $msg : '';
        $rs['data'] = !empty($data) ? $data : [];
        die(json_encode($rs));
    }

    /**
     * 简介：
     * @param int $length 长度
     * @return string
     */
    static function generate_password($length = 8)
    {
        // 密码字符集，可任意添加你需要的字符
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        $password = '';
        for ($i = 0; $i < $length; $i++) {
            // 这里提供两种字符获取方式
            // 第一种是使用 substr 截取$chars中的任意一位字符；
            // 第二种是取字符数组 $chars 的任意元素
            // $password .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
            $password .= $chars[mt_rand(0, strlen($chars) - 1)];
        }

        return $password;
    }

    /**
     * 简介：
     * @param string $str 字符串
     * @param string $s   字符串
     * @return mixed
     */
    public static function replace_space($str, $s = ",")
    {
        $qian = array(" ", "　", "\t", "\n", "\r", "\r\n", " ");
        return str_replace($qian, $s, $str);
    }

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @param string $str 字符串
     * @return string
     */
    public static function semiangle($str)
    {
        $arr = array('０' => '0', '１' => '1', '２' => '2', '３' => '3', '４' => '4',
            '５' => '5', '６' => '6', '７' => '7', '８' => '8', '９' => '9',
            'Ａ' => 'A', 'Ｂ' => 'B', 'Ｃ' => 'C', 'Ｄ' => 'D', 'Ｅ' => 'E',
            'Ｆ' => 'F', 'Ｇ' => 'G', 'Ｈ' => 'H', 'Ｉ' => 'I', 'Ｊ' => 'J',
            'Ｋ' => 'K', 'Ｌ' => 'L', 'Ｍ' => 'M', 'Ｎ' => 'N', 'Ｏ' => 'O',
            'Ｐ' => 'P', 'Ｑ' => 'Q', 'Ｒ' => 'R', 'Ｓ' => 'S', 'Ｔ' => 'T',
            'Ｕ' => 'U', 'Ｖ' => 'V', 'Ｗ' => 'W', 'Ｘ' => 'X', 'Ｙ' => 'Y',
            'Ｚ' => 'Z', 'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd',
            'ｅ' => 'e', 'ｆ' => 'f', 'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i',
            'ｊ' => 'j', 'ｋ' => 'k', 'ｌ' => 'l', 'ｍ' => 'm', 'ｎ' => 'n',
            'ｏ' => 'o', 'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r', 'ｓ' => 's',
            'ｔ' => 't', 'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x',
            'ｙ' => 'y', 'ｚ' => 'z',
            '（' => '(', '）' => ')', '〔' => '[', '〕' => ']', '【' => '[',
            '】' => ']', '〖' => '[', '〗' => ']', '“' => '[', '”' => ']',
            '‘' => '[', '’' => ']', '｛' => '{', '｝' => '}', '《' => '<', '》' => '>',
            '％' => '%', '＋' => '+', '—' => '-', '－' => '-', '～' => '-',
            '：' => ':', '。' => '.', '、' => ',', '，' => '.', '、' => '.',
            '；' => ',', '？' => '?', '！' => '!', '…' => '-', '‖' => '|',
            '”' => '"', '’' => '`', '‘' => '`', '｜' => '|', '〃' => '"',
            '　' => ' ');

        return strtr($str, $arr);
    }

    /**
     * 获取用户主机系统
     * @return string
     */
    public static function getOS()
    {
        $agent = \Yii::$app->request->getUserAgent();
        if (stripos($agent, 'win') && stripos($agent, 'nt 6.1')) {
            $os = 'Windows 7';
        } else if (stripos($agent, 'win') && (stripos($agent, 'nt 6.3') || stripos($agent, 'nt 6.2'))) {
            $os = 'Windows 8';
        } else if (stripos($agent, 'win') && stripos($agent, 'nt 6.0')) {
            $os = 'Windows Vista';
        } else if (stripos($agent, 'win') && stripos($agent, 'nt 5.2')) {
            $os = 'Windows Server 2003';
        } else if (stripos($agent, 'win') && stripos($agent, 'nt 5.1')) {
            $os = 'Windows XP';
        } else if (stripos($agent, 'win') && stripos($agent, 'nt 5')) {
            $os = 'Windows Server 2000';
        } else if (stripos($agent, 'win') && stripos($agent, 'nt')) {
            $os = 'Windows NT';
        } else if (stripos($agent, 'win') && stripos($agent, '32')) {
            $os = 'Windows 32';
        } else if (stripos($agent, 'linux')) {
            $os = 'Linux';
        } else if (stripos($agent, 'unix')) {
            $os = 'Unix';
        } else if (stripos($agent, 'Mac') && stripos($agent, 'PC')) {
            $os = 'Macintosh';
        } else if (stripos($agent, 'Mac') && stripos($agent, 'os')) {
            $os = 'Mac OS';
        } else if (stripos($agent, 'FreeBSD')) {
            $os = 'FreeBSD';
        } else if (stripos($agent, 'offline')) {
            $os = 'offline';
        } else {
            $os = 'Unknown';
        }
        return $os;
    }

    /**
     * 获取客户端浏览器
     * @return string
     */
    public static function getBrowser()
    {
        $agent = \Yii::$app->request->getUserAgent();
        if (strpos($agent, "Maxthon 2.0")) {
            return "Maxthon 2.0";
        }
        if (strpos($agent, "MSIE 9.0")) {
            return "ie9";
        }
        if (strpos($agent, "MSIE 8.0")) {
            return "ie8";
        }
        if (strpos($agent, "MSIE 7.0")) {
            return "ie7";
        }
        if (strpos($agent, "Firefox") || substr($agent, 0, 7) == 'Firefox') {
            return "Firefox";
        }
        if (strpos($agent, "Chrome") || substr($agent, 0, 6) == 'Chrome') {
            return "Chrome";
        }
        if (strpos($agent, "Safari") || substr($agent, 0, 6) == 'Safari') {
            return "Safari";
        }
        if (strpos($agent, "Opera") || substr($agent, 0, 5) == 'Opera') {
            return "Opera";
        }
        if (strpos($agent, "MSIE 10")) {
            return "ie10";
        }
        if (strpos($agent, "MSIE 11")) {
            return "ie11";
        }
        if (strpos($agent, "rv:11")) {
            return "ie11";
        }
        if (strpos($agent, "AppleWebKit")) {
            return "AppleWebKit";
        }
        return "unknown";

    }

    /**
     * 获取客户端ip地址
     * @return string
     */
    public static function getIp()
    {
        return \Yii::$app->request->getUserIP();
    }

    /**
     * 记录日志
     * @param int    $shop_id  商家id
     * @param string $info     日志内容
     * @param int    $log_type 日志类型
     * @return bool|mixed
     */
    public static function recordLog($shop_id, $info = '', $log_type = 1)
    {
        if (empty($shop_id)) {
            return false;
        }
        $browser = self::getBrowser();
        $os = self::getOS();
        $ip = self::getIp();
        $data = [
            'ip_address' => $ip,
            'browser' => $browser,
            'os' => $os,
            'log_info' => $info,
            'shop_id' => $shop_id,
            'log_type' => $log_type
        ];
        $re = CurlHelper::post('shop/shops/log', $data, 'api');
        return $re;
    }

    /**
     * 简介：UTF8转换成GBK格式
     * @author  lichenjun@iyangpin.com。
     * @param string $data 字符串
     * @return string
     */
    public static function utf8ToGbk($data)
    {
        $ret = iconv("UTF-8", "GB2312//IGNORE", $data);
        return $ret;
    }

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @param array $cate 分类arr
     * @param int   $pid  父ID
     * @return array
     */
    public static function getChildCateId($cate, $pid)
    {
        $arr = array();
        foreach ($cate as $v) {
            if ($v['pid'] == $pid) {
                $arr[] = $v['id'];
                $arr = array_merge($arr, self::getChildCateId($cate, $v['id']));
            }
        }
        return $arr;
    }
}
