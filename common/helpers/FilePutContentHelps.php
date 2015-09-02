<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   crm
 * @filename  FilePutContentHelps.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/9/2 上午10:06
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace common\helpers;


/**
 * Class FilePutContentHelps
 * @category  PHP
 * @package   Crm
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class FilePutContentHelps
{
    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @param string $file x
     * @param string $data x
     * @return null
     */
    public static function writeFile($file,$data)
    {
        file_put_contents('/tmp/' . $file, $data."\r\n", FILE_APPEND);
    }
}