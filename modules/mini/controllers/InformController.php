<?php

namespace app\modules\mini\controllers;

use app\models\InformUser;

class InformController extends Controller
{
    //通知列表
    public function actionList()
    {
        $this->init_page();

        $model = InformUser::find()->with('inform')
            ->where(['uid' => $this->userid]);
        $total = $model->count();
        $list = $model->orderBy('id desc')->offset($this->offset)->limit($this->limit)->asArray()->all();

        array_walk($list, function (&$val){
            if (in_array($val['inform']['type'], [1,2,3,4,5,6]))
            {
                $val['inform']['content'] = htmlspecialchars($val['inform']['content']);
            }
           $val['inform']['detail'] = $this->createMiniUrl('/miniappmessage?id=' . $val['inform']['id']);
        });
        InformUser::updateAll(['read' => 1], ['uid' => $this->userid, 'read' => 0]); //更新所有未读消息为已读
        return $this->json(['list' => $list, 'page' => $this->page($total)]);
    }

}
