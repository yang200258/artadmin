<?php

namespace app\modules\mini\controllers;


use app\models\Image;
use app\models\Msg;
use app\models\MsgContent;

class MsgController extends Controller
{
    //列表
    public function actionList()
    {
        $this->init_page();
        $request = \Yii::$app->request;
        $cid = $request->post('cid');

        $model = Msg::find()->where(['cid' => $cid]);
        $total = $model->count();
        $list = $model->orderBy('id desc')->offset($this->offset)->limit($this->limit)->asArray()->all();
        array_walk($list, function (&$val){
            $val['cover_url'] = $val['cover_id'] ? Image::getAbsoluteUrlById($val['cover_id']) : '';
            $val['detail_url'] = 'https://www.baidu.com/';
        });
        return $this->json(['list' => $list, 'page' => $this->page($total)]);
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
