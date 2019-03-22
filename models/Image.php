<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "image".
 *
 * @property int $id
 * @property string $md5
 */
class Image extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'image';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['md5'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'md5' => 'Md5',
        ];
    }
    /**
     * @param $extension
     * @return string
     */
    public function getAbsoluteUrl($extension = 'jpg')
    {
        return static::createAbsoluteUrl($this->md5, $extension);
    }

    /**
     * @param $md5
     * @param string $extension
     * @return string
     */
    public static function createAbsoluteUrl($md5, $extension = 'jpg')
    {
        $imgUrl = self::createUrl($md5, $extension);
        return $imgUrl ? \Yii::$app->params['image_site'] . $imgUrl : '';
    }

    /**
     * @param $md5
     * @param $extension
     * @return string
     */
    public static function createUrl($md5, $extension = 'jpg')
    {
        if (!$md5)
        {
            return '';
        }
        $imgUrl = '/uimage/';
        for ($i = 0; $i < 4; $i++)
        {
            $imgUrl .= substr($md5, $i * 2, 2) . '/';
        }
        $imgUrl .= $md5 . '.' . $extension;
        return $imgUrl;
    }

    /**
     * @param $imageId
     * @return string
     */
    public static function getAbsoluteUrlById($imageId)
    {
        if ($imageId == 0) {
            return '';
        }
        // 永久缓存
        $md5 = static::find()->select('md5')->where(['id' => $imageId])->cache(0)->limit(1)->scalar();
        return $md5 ? static::createAbsoluteUrl($md5) : '';
    }
}
