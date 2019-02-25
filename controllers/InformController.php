<?php

namespace app\controllers;


use app\models\Image;
use app\models\Inform;
use app\models\Msg;
use app\models\MsgCategory;
use yii\db\Expression;

class InformController extends Controller
{
    //通知详情
    public function actionDetail()
    {
        $request = \Yii::$app->request;
        $id = $request->getBodyParam('id');
        $inform = Inform::find()->select('id, type, UNCOMPRESS(content) content, create_at')
            ->where(['id' => $id])->asArray()->one();
        if (!$inform)
        {
            return $this->error('该通知不存在');
        }

        return $this->json($inform);
    }

}
