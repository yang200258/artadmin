<?php

namespace app\models;

use Yii;
use yii\base\InvalidParamException;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "admin".
 *
 * @property string $id
 * @property string $name 用户名字
 * @property string $identity 用户身份
 * @property string $username 登录账号
 * @property string $password 登录密码
 * @property string $token
 * @property int $apply 报名管理权限
 * @property int $exam 考试管理权限
 * @property int $msg 信息管理权限
 * @property int $inform 通知管理权限
 * @property int $admin 管理员管理权限
 * @property string $create_at 创建时间
 */
class Admin extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }


    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin';
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->token;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        try
        {
            return Yii::$app->security->validatePassword($password, $this->password);
        }
        catch (InvalidParamException $e)
        {
            return false;
        }
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password, 7);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        if (YII_ENV == 'prod')
        {
        //生产环境需要更新auth_key
        $this->token = Yii::$app->security->generateRandomString();
        }
        else
        {
            //测试环境不需要更新auth_key
            if (!$this->auth_key)
            {
                $this->auth_key = Yii::$app->security->generateRandomString();
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'create_at'], 'required'],
            [['id', 'apply', 'exam', 'msg', 'inform', 'admin'], 'integer'],
            [['create_at'], 'safe'],
            [['name', 'identity', 'username', 'token'], 'string', 'max' => 32],
            [['password'], 'string', 'max' => 64],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '用户名字',
            'identity' => '用户身份',
            'username' => '登录账号',
            'password' => '登录密码',
            'token' => 'Token',
            'apply' => '报名管理权限',
            'exam' => '考试管理权限',
            'msg' => '信息管理权限',
            'inform' => '通知管理权限',
            'admin' => '管理员管理权限',
            'create_at' => '创建时间',
        ];
    }
}