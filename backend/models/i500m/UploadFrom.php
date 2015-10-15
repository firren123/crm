<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Crm
 * @filename  UploadFrom.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/9/28 上午10:03
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\models\i500m;

use common\helpers\FastDFSHelper;
use yii\base\Model;


/**
 * Class UploadFrom
 * @category  PHP
 * @package   Crm
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class UploadFrom extends Model
{
    public $product_img = '';
    public $product_logo_img = '';
    public $shop_logo_img = '';
    public $brand_logo = '';
    /**
     * 简介：定义过滤规则
     * @author  lichenjun@iyangpin.com。
     * @return array
     */
    public function rules()
    {
        return [
            //不可为空的字段
            [['product_img'], 'image', 'skipOnEmpty' => false, 'extensions' => 'png, jpg', 'maxFiles' => 4],
            [['product_logo_img'], 'image', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, gif'],
            [['shop_logo_img'], 'image', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, gif'],
            [['brand_logo'], 'image', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, gif'],
        ];
    }

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @param string $img 图片
     * @return bool|string
     */
    public function upload($img)
    {
        if ($this->validate($this->$img)) {
            $fdfs = new FastDFSHelper();
            $url = [];
            foreach ($this->$img as $k => $file) {
                $filename = dirname($file->tempName) . "/" . $file->name;
                @rename($file->tempName, $filename);
                $ret = $fdfs->fdfs_upload_by_filename($filename);
                if ($ret) {
                    $url[$k] = $ret['group_name'] . '/' . $ret['filename'];
                }
            }
            return implode("###", $url);
        } else {
            return false;
        }
    }

}
