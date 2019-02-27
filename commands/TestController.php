<?php

namespace app\commands;


use app\models\Admin;

class TestController extends Controller
{

    public function actionAdmin()
    {
        $admin = new Admin();
        $admin->name = '超级管理员';
        $admin->identity = '超级管理员';
        $admin->username = 'admin';
        $admin->setPassword('123456');
        $admin->apply = 1;
        $admin->exam = 1;
        $admin->msg = 1;
        $admin->inform = 1;
        $admin->admin = 1;
        $admin->save(false);
        echo '添加成功';
    }

}