<?php

namespace app\controllers;


use app\models\Image;
use app\models\Msg;
use app\models\MsgCategory;
use yii\db\Expression;

class HomepageController extends Controller
{
    //首页
    public function actionIndex()
    {
        $category = MsgCategory::find()->where(['id' => [1, 2, 3, 4, 5]])->orderBy([new Expression("FIELD(id, 1, 2, 4, 3, 5)")])->asArray()->all();

        foreach ($category as &$one)
        {
            $limit = 3;
            if ($one['id'] == 4)
            {
                $limit = 2;
            }
            if ($one['id'] == 5)
            {
                $limit = 6;
            }
            $list = Msg::find()->select('')->where(['cid' => $one['id'], 'status' => Msg::STATUS_PUBLISHED])->orderBy('id desc')->limit($limit)->asArray()->all();
            array_walk($list, function (&$val) use ($one){
                $val['cover_url'] = $val['cover_id'] ? Image::getAbsoluteUrlById($val['cover_id']) : '';
                $val['category_name'] = $one['name'];
            });
            $one['list'] = $list;
        }

        return $this->json(['list' => $category]);
    }

}
