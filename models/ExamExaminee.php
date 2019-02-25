<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "exam_examinee".
 *
 * @property string $id
 * @property string $exam_site_id 某考场某时间点ID
 * @property string $apply_id 考生报名ID
 * @property string $sort 考生排位号
 * @property string $create_at 创建时间
 */
class ExamExaminee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'exam_examinee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['exam_site_id', 'apply_id', 'sort'], 'integer'],
            [['sort', 'create_at'], 'required'],
            [['create_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'exam_site_id' => '某考场某时间点ID',
            'apply_id' => '考生报名ID',
            'sort' => '考生排位号',
            'create_at' => '创建时间',
        ];
    }

    public function getExamsite()
    {
        return $this->hasOne(ExamSite::className(), ['id' => 'exam_site_id']);
    }

    public function getApply()
    {
        return $this->hasOne(ExamSite::className(), ['id' => 'apply_id']);
    }

    //添加
    public static function saveExamExaminee($exam_site_id, $apply)
    {
        $max_sort = ExamExaminee::find()->where(['exam_site_id' => $exam_site_id])->max('sort');
        if (!$max_sort)
        {
            $max_sort = 0;
        }
        if (!$apply->exam_site_id1)
        {
            $exam_examinee = new ExamExaminee();
            $exam_examinee->exam_site_id = $exam_site_id;
            $exam_examinee->apply_id = $apply->id;
            $exam_examinee->sort = $max_sort + 1;
            $exam_examinee->create_at = date('Y-m-d H:i:s', time());
            $exam_examinee->save(false);

            Apply::updateAll(['exam_site_id1' => $exam_site_id], ['id' => $apply->id]);
            return;
        }
        if (!$apply->exam_site_id2 && $apply->is_continuous && $apply->exam_site_id1!= $exam_site_id)
        {
            $exam_examinee = new ExamExaminee();
            $exam_examinee->exam_site_id = $exam_site_id;
            $exam_examinee->apply_id = $apply->id;
            $exam_examinee->sort = $max_sort + 1;
            $exam_examinee->create_at = date('Y-m-d H:i:s', time());
            $exam_examinee->save(false);

            Apply::updateAll(['exam_site_id2' => $exam_site_id], ['id' => $apply->id]);
            return;
        }
        return;
    }

}
