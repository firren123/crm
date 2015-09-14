<?php
/**
 * Class Admin
 * PHP Version 5
 * @category  PHP
 * @package   Admin
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @filename  ${FILE_NAME}
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @datetime  ${DATE} ${TIME}
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
 * Class      Admin
 *
 * @category  PHP
 * @package   Admin
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class Admin extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 2;

    /**
     * 简介：
     * @return mixed
     */
    public static function getDB()
    {
        return \Yii::$app->db_500m;
    }
    /**
     * 简介：
     * @return mixed
     */
    public static function tableName()
    {
        return '{{%crm_admin}}';
    }

    /**
     * 简介：
     * @return mixed
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * 简介：
     * @return mixed
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            //['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
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
     * 简介：x
     * @param mixed $token x
     * @param null $type   x
     * @return null
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     * @param string $username x
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     * @param string $token password reset token
     * @return static|null
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
     * Finds out if password reset token is valid
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * 简介：
     * @return mixed
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * 简介：
     * @return mixed
     */
    public function getAuthKey()
    {
        return $this->salt;
    }

    /**
     * 简介：
     * @param string $authKey x
     * @return bool
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }


    /**
     * 简介：
     * @param string $password x
     * @return bool
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * 简介：Generates "remember me" authentication key
     * @return bool
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * 简介：Generates new password reset token
     * @return bool
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * 简介：Removes password reset token
     * @return bool
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    /**
     * Validates password
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        if (md5($this->salt . md5($password)) != $this->password) {
            //return 100; //密码不正确
            return false;
        } elseif ($this->status == 1) {
            return false;
        } else {
            return true;
            return 200; //验证成功
        }
    }
}
