<?php
namespace app\modules\adminapi\controllers;


use app\models\Record;
use app\models\User;
use yii\db\StaleObjectException;

class TeacherController extends Controller
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

    //老师列表
    public function actionList()
    {
        $this->init_page();
        $request = \Yii::$app->request;
        $name = $request->post('name'); //姓名
        $phone = $request->post('phone');
        $organ_name = $request->post('organ_name');
        $username = $request->post('username');

        $model = User::find()
            ->select('id, name, username, sex, phone, organ_uid, create_at')
            ->andWhere(['type' => User::TYPE_TEACHER])
            ->andFilterWhere(['name' => $name])
            ->andFilterWhere(['LIKE', 'phone', $phone])
            ->andFilterWhere(['username' => $username]);
        if ($organ_name)
        {
            $organ_uid = User::find()->select('id')->where(['organ_name' => $organ_name])->andWhere(['type' => User::TYPE_ORGAN])->scalar();
            $model->andWhere(['organ_uid'=> $organ_uid]);
        }

        $total = $model->count();
        $list = $model->orderBy('create_at desc')->offset($this->offset)->limit($this->limit)->asArray()->all();
        foreach ($list as &$one)
        {
            $one['organ'] = null;
            if ($one['organ_uid'])
            {
                $one['organ'] = User::find()->select('id, organ_name')->where(['id' => $one['organ_uid']])->asArray()->one();
            }
        }

        return $this->json(['list' => $list, 'page' => $this->page($total)]);
    }

    //老师添加
    public function actionAdd()
    {
        $request = \Yii::$app->request;
        $name = $request->post('name'); //姓名
        $sex = $request->post('sex');
        $phone = $request->post('phone', []);
        $organ_uid = $request->post('organ_uid');
        $username = $request->post('username');
        $password = $request->post('password');

        if (!$name)
        {
            return $this->error('请输入姓名');
        }
        if (!$sex)
        {
            return $this->error('请输入性别');
        }
        if (!$phone)
        {
            return $this->error('请输入联系电话');
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
        $user->username = $username;
        $user->name = $name;
        $user->sex = $sex;
        $user->phone = implode(',', $phone);
        $user->organ_uid = $organ_uid;
        $user->type = User::TYPE_TEACHER;
        $user->create_at= date("Y-m-d H:i:s",time());
        $user->setPassword($password);
        $user->generateAuthKey();
        if (!$user->save(false))
        {
            return $this->error('创建失败');
        }

        $record = new Record();
        $record->admin_id = $this->admin->id;
        $record->content = "添加报名老师[$name]信息";
        $record->type = 5;
        $record->save(false);
        return $this->ok('创建成功');
    }

    //老师编辑
    public function actionEdit()
    {
        $request = \Yii::$app->request;
        $id = $request->post('id');
        $name = $request->post('name'); //姓名
        $sex = $request->post('sex');
        $phone = $request->post('phone', []);
        $organ_uid = $request->post('organ_uid');
        $username = $request->post('username');

        if (!$name)
        {
            return $this->error('请输入姓名');
        }
        if (!$sex)
        {
            return $this->error('请输入性别');
        }
        if (!$phone)
        {
            return $this->error('请输入联系电话');
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
        $user->sex = $sex;
        $user->phone = implode(',', $phone);
        $user->organ_name = $organ_uid;
        $user->generateAuthKey();
        if (!$user->save(false))
        {
            return $this->error('修改失败');
        }

        $record = new Record();
        $record->admin_id = $this->admin->id;
        $record->content = "编辑报名老师[$name]信息";
        $record->type = 5;
        $record->save(false);
        return $this->ok('修改成功');
    }

    //老师删除
    public function actionDelete()
    {
        $id = \Yii::$app->request->post('id');

        $user = User::findOne($id);
        $teacherName = $user->name;
        try {
            $user->delete();
        } catch (\Throwable $e) {
            return $this->error('删除失败');
        }
        $record = new Record();
        $record->admin_id = $this->admin->id;
        $record->content = "删除报名老师[$teacherName]信息";
        $record->type = 5;
        $record->save(false);
        return $this->ok('删除成功');
    }

    //老师重置密码
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