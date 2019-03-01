<?php

namespace app\modules\mini\controllers;


use app\models\Image;
use app\models\Msg;
use app\models\MsgCategory;

class HomeController extends Controller
{
    //主页
    public function actionIndex()
    {
        $category = MsgCategory::find()->where(['id' => [1, 2, 3, 4, 5]])->asArray()->all();

        foreach ($category as &$one)
        {
            $list = Msg::find()->select('')->where(['cid' => $one['id'], 'status' => Msg::STATUS_PUBLISHED])->orderBy('id desc')->limit(3)->asArray()->all();
            array_walk($list, function (&$val){
                $val['cover_url'] = $val['cover_id'] ? Image::getAbsoluteUrlById($val['cover_id']) : '';
            });
            $one['list'] = $list;
        }
        $banner = [
            [
                'url' => $this->createMiniUrl('/nimiappregulation'),
                'img' => \Yii::$app->params['image_site'] . '/image/mini/1.png'
            ],
            [
                'url' => $this->createMiniUrl('/nimiappregulation'),
                'img' => \Yii::$app->params['image_site'] . '/image/mini/2.png'
            ],
            [
                'url' => $this->createMiniUrl('/nimiappregulation'),
                'img' => \Yii::$app->params['image_site'] . '/image/mini/3.png'
            ]
        ];

        return $this->json(['list' => $category, 'banner' => $banner]);
    }

}
