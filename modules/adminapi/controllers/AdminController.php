<?php
namespace app\modules\adminapi\controllers;


use app\models\Admin;
use app\models\Record;

class AdminController extends Controller
{
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action))
        {
            return false;
        }

        if (!$this->admin->admin)
        {
            $this->throwForbidden();
        }
        return true;
    }

    //管理员列表
    public function actionList()
    {
        $this->init_page();
        $model = Admin::find()
            ->select('id, name, username,identity, apply, exam, msg, inform, admin, create_at');
        $total = $model->count();
        $list = $model->orderBy('create_at desc')->offset($this->offset)->limit($this->limit)->asArray()->all();

        return $this->json(['list' => $list, 'page' => $this->page($total)]);
    }

    //管理员添加
    public function actionAdd()
    {
        $request = \Yii::$app->request;
        $name = $request->post('name'); //姓名
        $identity = $request->post('identity'); //身份
        $username = $request->post('username');
        $password = $request->post('password');
        $repeat_password = $request->post('repeat_password');

        $apply = $request->post('apply');
        $exam = $request->post('exam');
        $msg = $request->post('msg');
        $inform = $request->post('inform');
        $ad = $request->post('admin');

        if (!$name)
        {
            return $this->error('请输入姓名');
        }
        if (!$identity)
        {
            return $this->error('请输入身份');
        }
        if (!$password || !$repeat_password)
        {
            return $this->error('请输入密码');
        }

        if ($password != $repeat_password)
        {
            return $this->error('密码不一致');
        }
        $admin = Admin::find()->where(['username' => $username])->one();
        if ($admin)
        {
            return $this->error('存在相同账号');
        }
        $admin = new Admin();
        $admin->name = $name;
        $admin->identity = $identity;
        $admin->username = $username;
        $admin->apply = $apply;
        $admin->exam = $exam;
        $admin->msg = $msg;
        $admin->inform = $inform;
        $admin->admin= $ad;
        $admin->create_at= date("Y-m-d H:i:s",time());
        $admin->setPassword($password);
        $admin->generateAuthKey();
        if (!$admin->save(false))
        {
            return $this->error('创建失败');
        }
        Record::saveRecord($this->admin->id, 5, "添加管理员[$name]");
        return $this->ok('创建成功');
    }

    //管理员编辑
    public function actionEdit()
    {
        $request = \Yii::$app->request;
        $id = $request->post('id');
        $name = $request->post('name'); //姓名
        $identity = $request->post('identity'); //身份
        $username = $request->post('username');

        $apply = $request->post('apply');
        $exam = $request->post('exam');
        $msg = $request->post('msg');
        $inform = $request->post('inform');
        $ad = $request->post('admin');

        if (!$name)
        {
            return $this->error('请输入姓名');
        }
        if (!$identity)
        {
            return $this->error('请输入身份');
        }

        if (!$username)
        {
            return $this->error('用户名不能为空');
        }
        $admin = Admin::findOne(['id' => $id]);
        if (!$admin)
        {
            return $this->error('账户不存在');
        }
        $admin->name = $name;
        $admin->identity = $identity;
        $admin->username = $username;
        $admin->apply = $apply;
        $admin->exam = $exam;
        $admin->msg = $msg;
        $admin->inform = $inform;
        $admin->admin= $ad;
        $admin->generateAuthKey();
        if (!$admin->save(false))
        {
            return $this->error('修改失败');
        }
        Record::saveRecord($this->admin->id, 5, "编辑管理员[$name]");
        return $this->ok('修改成功');
    }

    //管理员删除
    public function actionDelete()
    {
        $id = \Yii::$app->request->post('id');

        $adminM = Admin::findOne($id);
        if (!$adminM) {
            return $this->error('该管理员不存在');
        }
        $name = $adminM->name;
        try {
            $adminM->delete();
        } catch (\Throwable $e) {
            return $this->error('删除失败');
        }
        Record::saveRecord($this->admin->id, 5, "删除管理员[$name]");
        return $this->ok('删除成功');
    }

    //管理员重置密码
    public function actionReset()
    {
        $request = \Yii::$app->request;
        $admin_id = $request->post('id');
        $password = $request->post('password');
        $repeat_password = $request->post('repeat_password');

        if (!$password || !$repeat_password)
        {
            return $this->error('请输入密码');
        }

        if ($password != $repeat_password)
        {
            return $this->error('密码不一致');
        }

        $admin = Admin::findOne(['id' => $admin_id]);
        if (!$admin)
        {
            return $this->error('账户不存在');
        }
        $admin->setPassword($password);
        $admin->generateAuthKey();
        if (!$admin->save(false))
        {
            return $this->error('修改失败');
        }
        return $this->ok('修改成功');
    }
}