<?php

namespace app\controllers;

use app\helpers\Query;
use app\models\Apply;

class SearchController extends Controller
{
    //成绩查询
    public function actionGrade()
    {
        $request = \Yii::$app->request;
        $name = $request->post('name');
        $id_card = $request->post('id_card');
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

    //考场查询
    public function actionRoom()
    {
        $request = \Yii::$app->request;
        $name = $request->post('name');
        $id_card = $request->post('id_card');
        $domain = $request->post('domain');
        $level = $request->post('level');

        if (!$name)
        {
            return $this->error('请输入姓名');
        }
        if (!$id_card)
        {
            return $this->error('请输入身份证号');
        }
        if (!$domain)
        {
            return $this->error('请输入专业');
        }
        if (!$level)
        {
            return $this->error('请输入等级');
        }

        $apply = Apply::findOne(['name' => $name, 'id_card' => $id_card, 'domain' => $domain, 'level' => $level, 'plan' => 4]);
        if (!$apply)
        {
            $url = '';
        }else
        {
            $url = $apply->kz ? \Yii::$app->params['file_site'] . '/file/examimg/'. $apply->kz . '.png' : '';
        }
        return $this->json(['kz_image_url' => $url]);
    }
}
