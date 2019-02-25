<?php

namespace app\modules\mini\controllers;

use common\helpers\Query;

class SearchController extends Controller
{
    public function actionIndex()
    {
        $request = \Yii::$app->request;
        $name = $request->getBodyParam('name');
        $id_card = $request->getBodyParam('id_card');
        if (!$name)
        {
            return $this->error('请输入姓名');
        }
        if (!$id_card)
        {
            return $this->error('请输入身份证号');
        }
        $data = Query::getSpiderRes($name, $id_card);

        return $this->json($data);

    }
}
