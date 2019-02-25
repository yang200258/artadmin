<?php

namespace app\controllers;

use app\helpers\Query;

class SearchController extends Controller
{
    public function actionGrade()
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
