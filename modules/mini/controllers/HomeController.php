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
        $category = MsgCategory::find()->with('list')->where(['id' => [1, 2, 3, 4, 5]])->asArray()->all();

        foreach ($category as &$one)
        {
            $list = $one['list'];
            array_walk($list, function (&$val){
                $val['cover_url'] = $val['cover_id'] ? Image::getAbsoluteUrlById($val['cover_id']) : '';
                $val['url'] = $this->createMiniUrl('/miniappdynamic?id=' . $val['id']);
            });
            $one['list'] = $list;
        }

        $bannerList = Msg::find()
            ->select('id, cover_id')
            ->where(['cid' => MsgCategory::BANNER_IMAGE_CATEGORY, 'status' => Msg::STATUS_PUBLISHED])
            ->orderBy('id desc')
            ->limit(3)
            ->asArray()
            ->all();
        $banner = [];

        foreach ($bannerList as $item) {
            $banner[] = [
                'url' => $this->createMiniUrl('/miniappdynamic?id=' . $item['id']),
                'img' => $item['cover_id'] ? Image::getAbsoluteUrlById($item['cover_id']) : '',
            ];
        }


        return $this->json(['list' => $category, 'banner' => $banner]);
    }

}
