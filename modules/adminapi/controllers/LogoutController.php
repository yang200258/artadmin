<?php

namespace app\modules\adminapi\controllers;


use app\models\Admin;

class LogoutController extends Controller
{
    public function actionIndex()
    {
        $token = \Yii::$app->request->post('token');
        $admin = Admin::findOne(['token' => $token]);
        if (!$admin)
        {
            return $this->ok('退出成功');
        }

        $admin->generateAuthKey();
        if (!$admin->save(false))
        {
            return $this->error('退出失败');
        }
        return $this->ok('退出成功');
    }
}
