<?php

namespace app\modules\adminapi\controllers;

use app\models\Record;

class RecordController extends Controller
{
    // 记录列表
    public function actionList()
    {
        $this->init_page();

        $model = Record::find();

        $total = $model->count();
        $list = $model
            ->with([
                'admin' => function ($q) {
                    $q->select('id,name');
                }
            ])
            ->offset($this->offset)
            ->limit($this->limit)
            ->asArray()
            ->all();

        return $this->json(['list' => $list, 'page' => $this->page($total)]);
    }
}
