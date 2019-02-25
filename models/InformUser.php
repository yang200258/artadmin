<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inform_user".
 *
 * @property string $id
 * @property int $uid 通知用户ID
 * @property string $inform_id 通知ID
 * @property string $apply_id 某次考试的报名ID
 * @property string $read 1=已读
 */
class InformUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inform_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'inform_id', 'apply_id', 'read'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => '通知用户ID',
            'inform_id' => '通知ID',
            'apply_id' => '某次考试的报名ID',
            'read' => '1=已读',
        ];
    }

    public function getInform()
    {
        return $this->hasOne(Inform::className(), ['id' => 'inform_id'])->select('id, type, UNCOMPRESS(content) content, create_at');
    }
}
