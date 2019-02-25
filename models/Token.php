<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "token".
 *
 * @property string $id
 * @property string $token
 * @property string $create_at
 */
class Token extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'token';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['token'], 'required'],
            [['create_at'], 'safe'],
            [['token'], 'string', 'max' => 256],
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
            'create_at' => 'Create At',
        ];
    }
}
