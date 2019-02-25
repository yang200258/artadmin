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
                'url' => 'http://www.baidu.com',
                'img' => 'http://image.mamicode.com/info/201706/20180110233404910337.png'
            ],
            [
                'url' => 'http://www.baidu.com',
                'img' => 'http://image.mamicode.com/info/201706/20180110233404910337.png'
            ],
            [
                'url' => 'http://www.baidu.com',
                'img' => 'http://image.mamicode.com/info/201706/20180110233404910337.png'
            ]
        ];

        return $this->json(['list' => $category, 'banner' => $banner]);
    }

}
