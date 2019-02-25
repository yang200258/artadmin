<?php

namespace app\models;


use Yii;
use yii\base\InvalidParamException;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property string $id
 * @property string $token
 * @property string $username 登录账号
 * @property string $password 登录密码
 * @property string $name 姓名/联系人
 * @property string $sex 性别
 * @property string $phone 联系电话（多个电话逗号隔开）
 * @property string $openid 微信openid
 * @property string $union_id 微信union_id
 * @property resource $nick_name 微信昵称
 * @property string $avatar 微信头像链接
 * @property string $organ_name 机构名称
 * @property string $organ_address 机构地址
 * @property string $organ_uid 该老师所属机构
 * @property string $organ_area 所属地区0=海口市1=其他市县
 * @property int $type 0=普通用户1=老师2=机构
 * @property string $create_at 创建时间
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    const TYPE_COMMON = 0;  //普通用户
    const TYPE_TEACHER = 1;  //老师
    const TYPE_ORGAN = 2;  //机构
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
        return 'user';
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
//        if (YII_ENV == 'prod')
//        {
        //生产环境需要更新auth_key
        $this->token = Yii::$app->security->generateRandomString();
//        }
//        else
//        {
//            //测试环境不需要更新auth_key
//            if (!$this->auth_key)
//            {
//                $this->auth_key = Yii::$app->security->generateRandomString();
//            }
//        }
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'organ_uid', 'organ_area'], 'integer'],
            [['create_at'], 'required'],
            [['create_at'], 'safe'],
            [['token', 'username', 'name', 'union_id', 'nick_name'], 'string', 'max' => 32],
            [['password', 'organ_name', 'organ_address'], 'string', 'max' => 64],
            [['sex'], 'string', 'max' => 16],
            [['phone', 'avatar'], 'string', 'max' => 128],
            [['openid'], 'string', 'max' => 28],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'token' => 'Token',
            'username' => '登录账号',
            'password' => '登录密码',
            'name' => '姓名/联系人',
            'sex' => '性别',
            'phone' => '联系电话（多个电话逗号隔开）',
            'openid' => '微信openid',
            'union_id' => '微信union_id',
            'nick_name' => '微信昵称',
            'avatar' => '微信头像链接',
            'organ_name' => '机构名称',
            'organ_address' => '机构地址',
            'organ_uid' => '该老师所属机构',
            'organ_area' => '所属地区0=海口市1=其他市县',
            'type' => '0=普通用户1=老师2=机构',
            'create_at' => '创建时间',
        ];
    }
    public static function getUserByOpenid($openid)
    {
        if (empty($openid)){
            return null;
        }
        return User::findOne(['openid' => $openid]);
    }
}
