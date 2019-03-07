<?php

namespace app\models;

use app\helpers\FetchImage;
use app\helpers\simple_html_dom;
use Yii;

/**
 * This is the model class for table "msg_content".
 *
 * @property string $id
 * @property string $content
 */
class MsgContent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'msg_content';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content'], 'required'],
            [['content'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => 'Content',
        ];
    }

    //从内容中提取出图片url抓取，并替换内容里面的图片路径
    public static function getContent($content)
    {
        $html = new simple_html_dom();
        $html->load($content);
        $imgs = $html->find('img');
        foreach ($imgs as $img)
        {
            $src = $img->attr['src'];
            if (strpos($src, 'hnyskj.net'))
            {
                continue;
            }
            $md5 = FetchImage::fetchImage($src);
            $img->src = Image::createAbsoluteUrl($md5);
        }
        return strval($html);
    }
}
