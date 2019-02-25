<?php
namespace app\modules\admin\controllers;


use app\models\User;
use app\models\Wallet;

class NormaluserController extends Controller
{
    public function actionList()
    {
        $this->init_page();
        $request = \Yii::$app->request;
        $username = $request->post('username');

        $model = User::find()
            ->select('user.id, admin.username admin_username, user.username, user.price, user.create_at')
            ->andWhere(['admin_id' => $this->admin->id])
            ->andWhere(['user.is_delete' => User::IS_DELETE_FALSE])
            ->andFilterWhere(['user.username' => $username]);

        $total = $model->count();
        $list = $model->orderBy('user.id desc')->offset($this->offset)->limit($this->limit)->asArray()->all();

        array_walk($list, function (&$val) {
            $val['price'] = $val['price'] / 100;
        });
        return $this->json(['list' => $list, 'page' => $this->page($total)]);

    }
    //添加
    public function actionAdd()
    {
        $request = \Yii::$app->request;
        $username = $request->post('username');
        $password = $request->post('password');

        if (!$username)
        {
            return $this->error('请输入用户名');
        }

        if (!$password)
        {
            return $this->error('请输入密码');
        }
        $res = User::findOne(['username' => $username, 'is_delete' => User::IS_DELETE_FALSE]);
        if ($res)
        {
            return $this->error('该用户名称已经存在，请重新输入');
        }

        $user = new User();
        $user->admin_id = $this->admin->id;
        $user->username = $username;
        $user->price = "0";
        $user->setPassword($password);
        $user->create_at = date("Y-m-d H:i:s",time());
        $user->save(false);

        return $this->json(['id' => $user->id, 'admin_username' => $this->admin->username, 'username' => $user->username, 'price' => $user->price, 'create_at' => $user->create_at]);
    }

    //编辑
    public function actionEdit()
    {
        $request = \Yii::$app->request;
        $id = $request->post('id');
        $username = $request->post('username');

        if (!$username)
        {
            return $this->error('请输入用户名');
        }

        $res = User::find()->where(['username' => $username, 'is_delete' => User::IS_DELETE_FALSE])->andWhere(['!=', 'id', $id])->exists();
        if ($res)
        {
            return $this->error('该用户名名称已经存在，请重新输入');
        }

        $user = User::findOne($id);
        if (!$user)
        {
            return $this->error('该用户不存在');
        }
        $user->username = $username;
        $user->save(false);

        return $this->json(['id' => $user->id, 'admin_username' => $this->admin->username, 'username' => $user->username, 'price' => $user->price / 100, 'create_at' => $user->create_at]);

    }

    //删除
    public function actionDelete()
    {
        $request = \Yii::$app->request;
        $id = $request->post('id');
        User::updateAll(['is_delete' => User::IS_DELETE_TRUE], ['id' => $id]);
        return $this->ok('删除成功');
    }

    //重置密码
    public function actionResetpassword()
    {
        $request = \Yii::$app->request;
        $id = $request->post('id');
        $password = $request->post('password');

        if (!$password)
        {
            return $this->error('请输入密码');
        }

        $user = User::findOne($id);
        if (!$user)
        {
            return $this->error('该用户不存在');
        }
        $user->setPassword($password);
        $user->save(false);

        return $this->ok('重置密码成功');
    }

    //普通用户增减积分
    public function actionPrice()
    {
        $request = \Yii::$app->request;
        $id = $request->post('id');
        $price = $request->post('price');
        $type = $request->post('type', 0);  //0=增加1=减
        if (!$id)
        {
            return $this->error('参数错误');
        }
        if (!is_numeric($price))
        {
            return $this->error('请正确输入积分');
        }
        $price = $price * 100;

        if (!is_int($price))
        {
            return $this->error('请正确输入积分');
        }

        $user = User::findOne($id);

        if ($type)
        {
            if ($user->price < $price)
            {
                return $this->error('提取积分不能大于剩余积分');
            }
            $price = -$price;
        }
        $transaction = \Yii::$app->db->beginTransaction();//创建事务
        try {
            User::updateAllCounters(['price' => $price], ['id' => $id]);
            $admin_wallet = new Wallet();
            $admin_wallet->admin_id = $this->admin->id;
            $admin_wallet->uid = $id;
            $admin_wallet->price = $price;
            $admin_wallet->type = $type;
            $admin_wallet->create_at = date('Y-m-d H:i:s',time());
            $admin_wallet->save(false);
            $transaction->commit();//提交事务
        } catch (\Exception $e) {
            \Yii::error($e->getMessage());
            $transaction->rollback();//回滚事务
            return $this->error('服务器繁忙，请稍后再试！', 1);
        }

        $user = User::findOne($id);
        return $this->json($user->price / 100);
    }

}
