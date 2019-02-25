<?php

namespace app\modules\admin\controllers;


use app\models\Apply;
use app\models\InformUser;

//通知对象操作
class ObjectController extends Controller
{
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action))
        {
            return false;
        }

        if (!$this->admin->inform)
        {
            $this->throwForbidden();
        }
        return true;
    }

    //获取所有对象列表
    public function actionIndex()
    {
        $this->init_page();
        $request = \Yii::$app->request;
        $name = $request->post('name');
        $domain = $request->post('domain');
        $level = $request->post('level');
        $start_time = $request->post('start_time');
        $end_time = $request->post('end_time');

        $model = Apply::find()
            ->andFilterWhere(['name' => $name])
            ->andFilterWhere(['domain' => $domain])
            ->andFilterWhere(['level' => $level])
            ->andFilterWhere(['>=', 'create_at', $start_time])
            ->andFilterWhere(['<=', 'create_at', $end_time ? $end_time . ' 23:59:59': '']);

        $total = $model->count();
        $list = $model->select('uid, name, domain, level, create_at')->orderBy('id desc')->offset($this->offset)->limit($this->limit)->asArray()->all();

        return $this->json(['list' => $list, 'page' => $this->page($total)]);
    }

    //单条通知对象列表
    public function actionList()
    {
        $inform_id = \Yii::$app->request->post('inform_id');

        $uids = InformUser::find()->select('uid')->where(['inform_id' => $inform_id])->column();

        $list = Apply::find()->select('uid, name, domain, level, create_at')->where(['uid' => $uids])->asArray()->all();

        return $this->json(['list' => $list]);
    }

    //删除通知的单条对象
    public function actionDelete()
    {
        $inform_id = \Yii::$app->request->post('inform_id');
        $uid = \Yii::$app->request->post('uid');
        InformUser::deleteAll(['uid' => $uid, 'inform_id' => $inform_id]);

        return $this->ok('删除成功');
    }
}
