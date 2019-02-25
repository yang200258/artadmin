<?php

namespace app\controllers;


use app\models\Image;
use app\models\Msg;
use app\models\MsgCategory;
use app\models\MsgContent;

class MsgController extends Controller
{
    //1考级动态2师资培训3艺术团表演4大赛动态5考级教材
    public function actionCategory1()
    {
        $this->init_page();
        $leftCates = MsgCategory::find()->where(['id' => 1])->asArray()->one();
        $model = Msg::find()->where(['cid' => 1, 'status' => Msg::STATUS_PUBLISHED]);
        $total = $model->count();
        $list = $model->orderBy('id desc')->offset($this->offset)->limit($this->limit)->asArray()->all();
        array_walk($list, function (&$val){
            $val['cover_url'] = $val['cover_id'] ? Image::getAbsoluteUrlById($val['cover_id']) : '';
        });
        $leftCates['list'] = $list;

        $category3 = MsgCategory::find()->where(['id' => 3])->asArray()->one();
        $list3 = Msg::find()->where(['cid' => 3, 'status' => Msg::STATUS_PUBLISHED])->orderBy('id desc')->limit(2)->asArray()->all();
        array_walk($list, function (&$val){
            $val['cover_url'] = $val['cover_id'] ? Image::getAbsoluteUrlById($val['cover_id']) : '';
        });
        $category3['list'] = $list3;

        $category5 = MsgCategory::find()->where(['id' => 5])->asArray()->one();
        $list5 = Msg::find()->where(['cid' => 5, 'status' => Msg::STATUS_PUBLISHED])->orderBy('id desc')->limit(6)->asArray()->all();
        array_walk($list, function (&$val){
            $val['cover_url'] = $val['cover_id'] ? Image::getAbsoluteUrlById($val['cover_id']) : '';
        });
        $category5['list'] = $list5;
        $rightCates = [$category3, $category5];
        return $this->json(['leftCates' => $leftCates, 'rightCates' => $rightCates, 'page' => $this->page($total)]);
    }

    public function actionCategory2()
    {
        $this->init_page();
        $leftCates = MsgCategory::find()->where(['id' => 2])->asArray()->one();
        $model = Msg::find()->where(['cid' => 2, 'status' => Msg::STATUS_PUBLISHED]);
        $total = $model->count();
        $list = $model->orderBy('id desc')->offset($this->offset)->limit($this->limit)->asArray()->all();
        array_walk($list, function (&$val){
            $val['cover_url'] = $val['cover_id'] ? Image::getAbsoluteUrlById($val['cover_id']) : '';
        });
        $leftCates['list'] = $list;

        $category3 = MsgCategory::find()->where(['id' => 3])->asArray()->one();
        $list3 = Msg::find()->where(['cid' => 3, 'status' => Msg::STATUS_PUBLISHED])->orderBy('id desc')->limit(2)->asArray()->all();
        array_walk($list, function (&$val){
            $val['cover_url'] = $val['cover_id'] ? Image::getAbsoluteUrlById($val['cover_id']) : '';
        });
        $category3['list'] = $list3;

        $category5 = MsgCategory::find()->where(['id' => 5])->asArray()->one();
        $list5 = Msg::find()->where(['cid' => 5, 'status' => Msg::STATUS_PUBLISHED])->orderBy('id desc')->limit(6)->asArray()->all();
        array_walk($list, function (&$val){
            $val['cover_url'] = $val['cover_id'] ? Image::getAbsoluteUrlById($val['cover_id']) : '';
        });
        $category5['list'] = $list5;
        $rightCates = [$category3, $category5];
        return $this->json(['leftCates' => $leftCates, 'rightCates' => $rightCates, 'page' => $this->page($total)]);
    }

    public function actionCategory3()
    {
        $this->init_page();
        $leftCates = MsgCategory::find()->where(['id' => 3])->asArray()->one();
        $model = Msg::find()->where(['cid' => 3, 'status' => Msg::STATUS_PUBLISHED]);
        $total = $model->count();
        $list = $model->orderBy('id desc')->offset($this->offset)->limit($this->limit)->asArray()->all();
        array_walk($list, function (&$val){
            $val['cover_url'] = $val['cover_id'] ? Image::getAbsoluteUrlById($val['cover_id']) : '';
        });
        $leftCates['list'] = $list;
        return $this->json(['leftCates' => $leftCates, 'page' => $this->page($total)]);
    }

    public function actionCategory4()
    {
        $this->init_page();
        $leftCates = MsgCategory::find()->where(['id' => 4])->asArray()->one();
        $model = Msg::find()->where(['cid' => 4, 'status' => Msg::STATUS_PUBLISHED]);
        $total = $model->count();
        $list = $model->orderBy('id desc')->offset($this->offset)->limit($this->limit)->asArray()->all();
        array_walk($list, function (&$val){
            $val['cover_url'] = $val['cover_id'] ? Image::getAbsoluteUrlById($val['cover_id']) : '';
        });
        $leftCates['list'] = $list;

        $category3 = MsgCategory::find()->where(['id' => 3])->asArray()->one();
        $list3 = Msg::find()->where(['cid' => 3, 'status' => Msg::STATUS_PUBLISHED])->orderBy('id desc')->limit(2)->asArray()->all();
        array_walk($list, function (&$val){
            $val['cover_url'] = $val['cover_id'] ? Image::getAbsoluteUrlById($val['cover_id']) : '';
        });
        $category3['list'] = $list3;

        $category5 = MsgCategory::find()->where(['id' => 5])->asArray()->one();
        $list5 = Msg::find()->where(['cid' => 5, 'status' => Msg::STATUS_PUBLISHED])->orderBy('id desc')->limit(6)->asArray()->all();
        array_walk($list, function (&$val){
            $val['cover_url'] = $val['cover_id'] ? Image::getAbsoluteUrlById($val['cover_id']) : '';
        });
        $category5['list'] = $list5;
        $rightCates = [$category3, $category5];
        return $this->json(['leftCates' => $leftCates, 'rightCates' => $rightCates, 'page' => $this->page($total)]);
    }

    public function actionCategory5()
    {
        $this->init_page();
        $leftCates = MsgCategory::find()->where(['id' => 5])->asArray()->one();
        $model = Msg::find()->where(['cid' => 5, 'status' => Msg::STATUS_PUBLISHED]);
        $total = $model->count();
        $list = $model->orderBy('id desc')->offset($this->offset)->limit($this->limit)->asArray()->all();
        array_walk($list, function (&$val){
            $val['cover_url'] = $val['cover_id'] ? Image::getAbsoluteUrlById($val['cover_id']) : '';
        });
        $leftCates['list'] = $list;
        return $this->json(['leftCates' => $leftCates, 'page' => $this->page($total)]);
    }

    public function actionDetail()
    {
        $id = \Yii::$app->request->post('id');
        $msg = Msg::find()->where(['id' => $id])->asArray()->one();
        if (!$msg)
        {
            return $this->error('信息不存在');
        }
        $msg['content'] = MsgContent::find()->select('UNCOMPRESS(content) content')->where(['id' => $msg['content_id']])->scalar();
        return $this->json($msg);
    }


}
