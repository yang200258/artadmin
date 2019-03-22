<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "apply_pay".
 *
 * @property string $id
 * @property string $apply_id 报名ID
 * @property string $number 缴费单号
 * @property string $price 缴费金额
 * @property int $type 缴费方式1=微信支付2=线下缴费
 * @property int $status 缴费状态1=已缴费
 * @property string prepay_id 预付款id，小程序模板消息用
 * @property string $pay_time 缴费时间
 * @property string $create_at 创建时间
 */
class ApplyPay extends \yii\db\ActiveRecord
{

    //收费标准
    public static $rates = [
        '一级' => 20000,
        '二级' => 24000,
        '三级' => 28000,
        '四级' => 32000,
        '五级' => 36000,
        '六级' => 39000,
        '七级' => 42000,
        '八级' => 45000,
        '九级' => 48000,
        '十级' => 51000,
        '表演文凭级' => 62000
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'apply_pay';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['apply_id', 'price', 'type', 'status'], 'integer'],
            [['create_at'], 'required'],
            [['pay_time', 'create_at'], 'safe'],
            [['number'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'apply_id' => '报名ID',
            'number' => '缴费单号',
            'price' => '缴费金额',
            'type' => '缴费方式1=微信支付2=线下缴费',
            'status' => '缴费状态1=已缴费',
            'pay_time' => '缴费时间',
            'create_at' => '创建时间',
        ];
    }
}
