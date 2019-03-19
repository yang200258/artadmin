<?php

namespace app\models;

use Yii;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use yii2tech\ar\softdelete\SoftDeleteQueryBehavior;

/**
 * This is the model class for table "exam".
 *
 * @property string $id
 * @property string $number 考试编号
 * @property string $name 考试名称
 * @property string $apply_time_start 报名开始时间
 * @property string $apply_time_end 报名结束时间
 * @property string $exam_time_start 考试开始时间
 * @property string $exam_time_end 考试结束时间
 * @property string $create_at 创建时间
 * @property string $is_delete 软删除标记
 */
class Exam extends \yii\db\ActiveRecord
{

    // 使用软删除
    public function behaviors()
    {
        return [
            'softDeleteBehavior' => [
                'class' => SoftDeleteBehavior::class,
                'softDeleteAttributeValues' => [
                    'is_delete' => true,
                ],
                'replaceRegularDelete' => true // mutate native `delete()` method
            ],
        ];
    }

    /**
     * 只查询没有被软删除的数据
     * @return \yii\db\ActiveQuery|SoftDeleteQueryBehavior
     */
    public static function find()
    {
        $query = parent::find();
        $query->attachBehavior('softDelete', SoftDeleteQueryBehavior::class);
        return $query->notDeleted();;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'exam';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['apply_time_start', 'apply_time_end', 'exam_time_start', 'exam_time_end', 'create_at'], 'required'],
            [['apply_time_start', 'apply_time_end', 'exam_time_start', 'exam_time_end', 'create_at'], 'safe'],
            [['number'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => '考试编号',
            'name' => '考试名称',
            'apply_time_start' => '报名开始时间',
            'apply_time_end' => '报名结束时间',
            'exam_time_start' => '考试开始时间',
            'exam_time_end' => '考试结束时间',
            'create_at' => '创建时间',
        ];
    }
}
