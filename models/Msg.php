<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "msg".
 *
 * @property string $id
 * @property string $cid 分类ID
 * @property string $title 标题
 * @property int $cover_id 封面图片ID
 * @property string $intro 引言
 * @property int $content_id 内容ID
 * @property int $status 1=已发布2=草稿
 * @property string $create_at 创建时间
 */
class Msg extends \yii\db\ActiveRecord
{
    const STATUS_PUBLISHED = 1;
    const STATUS_DRAFT = 2;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'msg';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cid', 'cover_id', 'content_id', 'status'], 'integer'],
            [['create_at'], 'required'],
            [['create_at'], 'safe'],
            [['title'], 'string', 'max' => 128],
            [['intro'], 'string', 'max' => 512],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cid' => '分类ID',
            'title' => '标题',
            'cover_id' => '封面图片ID',
            'intro' => '引言',
            'content_id' => '内容ID',
            'status' => '1=已发布2=草稿',
            'create_at' => '创建时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     * @author tao
     */
    public function getMsgCategory()
    {
        return $this->hasOne(MsgCategory::class, ['id' => 'cid']);
    }
}
