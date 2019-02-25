<?php

namespace app\modules\admin\controllers;


use app\models\Admin;

class LogoutController extends Controller
{
    public function actionIndex()
    {
        $token = \Yii::$app->request->post('token');
        $Admin = Admin::findOne(['token' => $token, 'type' => Admin::TYPE_FALSE]);
        if (!$Admin)
        {
            return $this->ok('退出成功');
        }

        $Admin->generateAuthKey();
        if (!$Admin->save(false))
        {
            return $this->error('退出失败');
        }
        return $this->ok('退出成功');
    }
}
