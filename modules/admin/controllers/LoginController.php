<?php
namespace app\modules\admin\controllers;


use app\models\Admin;

class LoginController extends Controller
{
    //账户密码登录
    public function actionIndex()
    {
        $request = \Yii::$app->request;
        $username = $request->post('username');
        $password = $request->post('password');

        $admin = Admin::findOne(['username' => $username]);
        if (!$admin)
        {
            return $this->error('管理员不存在');
        }

        if (!$admin->validatePassword($password))
        {
            return $this->error('密码错误，请重新输入');
        }

        if (!\Yii::$app->user->login($admin, 3600))
        {
            return $this->error('登录失败, 请重试');
        }

        $admin->generateAuthKey();
        if (!$admin->save(false))
        {
            return $this->error('登录失败, 请重试');
        }

        return $this->json([
            'token' => $admin->token,
            'username' => $admin->username,
            'apply' => $admin->apply,
            'exam' => $admin->exam,
            'msg' => $admin->msg,
            'inform' => $admin->inform,
            'admin' => $admin->admin,
        ]);
    }
}
