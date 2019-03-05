<?php

namespace app\modules\adminapi\controllers;


use app\models\Record;
use app\models\User;
use yii\db\StaleObjectException;

class OrganController extends Controller
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

    //机构列表
    public function actionList()
    {
        $this->init_page();
        $request = \Yii::$app->request;
        $name = $request->post('name'); //姓名
        $phone = $request->post('phone');
        $organ_name = $request->post('organ_name');
        $username = $request->post('username');

        $model = User::find()
            ->select('id, name, username, organ_address, phone, organ_name, create_at')
            ->andWhere(['type' => User::TYPE_ORGAN])
            ->andFilterWhere(['name' => $name])
            ->andFilterWhere(['LIKE', 'phone', $phone])
            ->andFilterWhere(['LIKE', 'organ_name', $organ_name])
            ->andFilterWhere(['username' => $username]);

        $total = $model->count();
        $list = $model->orderBy('create_at desc')->offset($this->offset)->limit($this->limit)->asArray()->all();

        return $this->json(['list' => $list, 'page' => $this->page($total)]);
    }

    //机构添加
    public function actionAdd()
    {
        $request = \Yii::$app->request;
        $name = $request->post('name'); //姓名
        $organ_address = $request->post('organ_address');
        $phone = $request->post('phone', []);
        $organ_name = $request->post('organ_name');
        $username = $request->post('username');
        $password = $request->post('password');
        $organ_area = $request->post('organ_area');

        if (!$name)
        {
            return $this->error('请输入姓名');
        }
        if (!$organ_address)
        {
            return $this->error('请输入性别');
        }
        if (!$phone)
        {
            return $this->error('请输入联系电话');
        }
        if (!$organ_name)
        {
            return $this->error('请输入所属机构');
        }
        if (!$username)
        {
            return $this->error('请输入登录名称');
        }
        if (!$password)
        {
            return $this->error('请输入密码');
        }

        $user = User::find()->where(['username' => $username])->one();
        if ($user)
        {
            return $this->error('存在相同账号');
        }
        $user = new User();
        $user->name = $name;
        $user->organ_address = $organ_address;
        $user->phone = implode(',', $phone);
        $user->organ_name = $organ_name;
        $user->organ_area = $organ_area;
        $user->type = User::TYPE_ORGAN;
        $user->create_at= date("Y-m-d H:i:s",time());
        $user->setPassword($password);
        $user->generateAuthKey();
        if (!$user->save(false))
        {
            return $this->error('创建失败');
        }

        $record = new Record();
        $record->admin_id = $this->admin->id;
        $record->content = "添加机构[$name]";
        $record->type = 5;
        $record->save(false);
        return $this->ok('创建成功');
    }

    //机构编辑
    public function actionEdit()
    {
        $request = \Yii::$app->request;
        $id = $request->post('id');
        $name = $request->post('name'); //姓名
        $organ_address = $request->post('organ_address');
        $phone = $request->post('phone', []);
        $organ_name = $request->post('organ_name');
        $username = $request->post('username');
        $organ_area = $request->post('organ_area');
        $password = $request->post('password');

        if (!$name)
        {
            return $this->error('请输入姓名');
        }
        if (!$organ_address)
        {
            return $this->error('请输入性别');
        }
        if (!$phone)
        {
            return $this->error('请输入联系电话');
        }
        if (!$organ_name)
        {
            return $this->error('请输入所属机构');
        }
        if (!$username)
        {
            return $this->error('请输入登录名称');
        }

        $user = User::findOne(['id' => $id]);
        if (!$user)
        {
            return $this->error('账户不存在');
        }
        $user_check = User::find()->where(['username' => $username])->andWhere(['!=', 'id', $id])->one();
        if ($user_check)
        {
            return $this->error('存在相同账号');
        }

        $user->username = $username;
        $user->name = $name;
        $user->organ_address = $organ_address;
        $user->phone = implode(',', $phone);
        $user->organ_name = $organ_name;
        $user->organ_area = $organ_area;
        $user->setPassword($password);
        $user->generateAuthKey();
        if (!$user->save(false))
        {
            return $this->error('修改失败');
        }

        $record = new Record();
        $record->admin_id = $this->admin->id;
        $record->content = "编辑机构[$name]信息";
        $record->type = 5;
        $record->save(false);
        return $this->ok('修改成功');
    }

    //机构删除
    public function actionDelete()
    {
        $id = \Yii::$app->request->post('id');

        $user = User::findOne($id);
        $organName = $user->name;

        try {
            $user->delete();
        } catch (\Throwable $e) {
            return $this->error('删除失败');
        }

        $record = new Record();
        $record->admin_id = $this->admin->id;
        $record->content = "删除机构[$organName]";
        $record->type = 5;
        $record->save(false);
        return $this->ok('删除成功');
    }

    //机构重置密码
    public function actionReset()
    {
        $request = \Yii::$app->request;
        $id = $request->post('id');
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

        $user = User::findOne(['id' => $id]);
        if (!$user)
        {
            return $this->error('账户不存在');
        }
        $user->setPassword($password);
        $user->generateAuthKey();
        if (!$user->save(false))
        {
            return $this->error('修改失败');
        }
        return $this->ok('修改成功');
    }
}
