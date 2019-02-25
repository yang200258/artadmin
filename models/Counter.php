<?php

namespace app\models;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "counter".
 *
 * @property integer $id
 */
class Counter extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'counter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
        ];
    }
}
