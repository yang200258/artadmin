<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "exam_site".
 *
 * @property string $id
 * @property int $exam_id 考试信息ID
 * @property string $address 考试地点
 * @property string $room 考场名称
 * @property string $exam_time 考试时间
 */
class ExamSite extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'exam_site';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['exam_id'], 'integer'],
            [['exam_time'], 'required'],
            [['exam_time'], 'safe'],
            [['address', 'room'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'exam_id' => '考试信息ID',
            'address' => '考试地点',
            'room' => '考场名称',
            'exam_time' => '考试时间',
        ];
    }
}
