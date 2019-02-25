<?php

namespace app\controllers;


use app\models\User;

class LogoutController extends Controller
{
    public function actionIndex()
    {
        $token = \Yii::$app->request->post('token');
        $user = User::findOne(['token' => $token]);
        if (!$user)
        {
            return $this->ok('退出成功');
        }

        $user->generateAuthKey();
        if (!$user->save(false))
        {
            return $this->error('退出失败');
        }
        return $this->ok('退出成功');
    }
}
