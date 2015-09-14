<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Admin
 * @filename  User.php
 * @author    xxx <xxx@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/13 上午10:07
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 * property integer $id
 * property string $username
 * property string $password_hash
 * property string $password_reset_token
 * property string $email
 * property string $auth_key
 * property integer $status
 * property integer $created_at
 * property integer $updated_at
 * property string $password write-only password
 *
 * Class      Module
 *
 * @category  PHP
 * @package   Module
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;

    /**
     * 简介：
     * @return null
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * 简介：
     * @return null
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * 简介：
     * @return null
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * 简介：
     * @param int|string $id id
     * @return null|static
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * 简介：
     * @param mixed $token x
     * @param null  $type  x
     * @return null|static
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * 简介：Finds user by username
     * @param string $username username
     * @return null|static
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * 简介：Finds user by password reset token
     * @param string $token password reset token
     * @return null|static
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne(
            [
                'password_reset_token' => $token,
                'status' => self::STATUS_ACTIVE,
            ]
        );
    }

    /**
     * 简介：Finds out if password reset token is valid
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int)end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * 简介：
     * @return null
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * 简介：
     * @return null
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * 简介：
     * @param string $authKey Key
     * @return bool
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * 简介：Generates password hash from password and sets it to the model
     * @param string $password password
     * @return null
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * 简介：Generates "remember me" authentication key
     * @return null
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * 简介：Generates new password reset token
     * @return null
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * 简介：Removes password reset token
     * @return null
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * 简介：Validates password
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        if (md5($this->salt . md5($password)) != $this->password) {
            return false; //密码不正确
        } elseif ($this->status == 1) {
            return false; //用户未激活
        } else {
            return true; //验证成功
        }
    }

    /**
     * 简介：
     * @return mixed
     */
    public static function getDB()
    {
        return \Yii::$app->db_500m;
    }
}
