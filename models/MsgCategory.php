<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "msg_category".
 *
 * @property string $id
 * @property string $name 分类名称
 */
class MsgCategory extends \yii\db\ActiveRecord
{
    const BANNER_IMAGE_CATEGORY = 8;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'msg_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '分类名称',
        ];
    }

    public function getList()
    {
        return $this->hasMany(Msg::class, ['cid' => 'id'])
            ->where(['status' => Msg::STATUS_PUBLISHED])
            ->orderBy('id desc')
            ->limit(3)
            ->asArray();
    }
}
