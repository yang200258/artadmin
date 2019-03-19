<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inform".
 *
 * @property string $id
 * @property int $type 后台发布通知：1=成绩查询通知2=准考证领取通知3=考场查询通知4=考试报名通知5=大赛通知6=定向通知，系统自动发布通知：7=审核未通过8=缴费通知9=缴费成功通知10=缺考顺延通知
 * @property string $content 通知详情
 * @property string $create_at 创建时间
 */
class Inform extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inform';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type'], 'integer'],
            [['content', 'create_at'], 'required'],
            [['content'], 'string'],
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
            'type' => '后台发布通知：1=成绩查询通知2=准考证领取通知3=考场查询通知4=考试报名通知5=大赛通知6=定向通知，系统自动发布通知：7=审核未通过8=缴费通知9=缴费成功通知10=缺考顺延通知',
            'content' => '通知详情',
            'create_at' => '创建时间',
        ];
    }

    public static function saveInform($content, $type, $uid, $applyId = 0)
    {
        $inform = new self;
        $inform->content = $content;
        $inform->type = $type;
        $inform->create_at = date("Y-m-d H:i:s");
        if (!$inform->save(false)) {
            return false;
        }
        $informUser = new InformUser();
        $informUser->uid = $uid;
        $informUser->inform_id = $inform->id;
        $informUser->apply_id = $applyId;
        return $informUser->save(false);
    }
}
