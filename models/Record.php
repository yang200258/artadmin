<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "record".
 *
 * @property string $id
 * @property string $admin_id 管理员ID
 * @property int $type 1=报名管理2=考试管理3=通知管理4=信息管理5=管理员管理
 * @property string $content 操作内容
 * @property string $create_at 创建时间
 */
class Record extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'record';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['admin_id', 'type'], 'integer'],
            [['create_at'], 'required'],
            [['create_at'], 'safe'],
            [['content'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'admin_id' => '管理员ID',
            'type' => '1=报名管理2=考试管理3=通知管理4=信息管理5=管理员管理',
            'content' => '操作内容',
            'create_at' => '创建时间',
        ];
    }

    //添加操作记录
    public static function saveRecord($admin_id, $type, $content)
    {
        $record = new Record();
        $record->admin_id = $admin_id;
        $record->type = $type;
        $record->content  = $content;
        $record->save(false);
    }
}
