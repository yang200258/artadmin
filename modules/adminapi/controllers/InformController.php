<?php

namespace app\modules\adminapi\controllers;


use app\models\Inform;
use app\models\InformUser;
use yii\db\Expression;

//通知管理
class InformController extends Controller
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

    public function actionList()
    {
        $request = \Yii::$app->request;
        $type = $request->post('type');
        $start_time = $request->post('start_time');
        $end_time = $request->post('end_time');

        $this->init_page();
        $model = Inform::find()->select('id, type, UNCOMPRESS(content) content, create_at')
            ->andFilterWhere(['>=', 'create_at', $start_time])
            ->andFilterWhere(['<=', 'create_at', $end_time ? $end_time . ' 23:59:59': '']);
        if ($type)
        {
            $model->andFilterWhere(['type' => $type]);
        }else
        {
            $model->andFilterWhere(['type' => [1,2,3,4,5,6]]);
        }
        $total = $model->count();
        $list = $model->orderBy('create_at desc')->offset($this->offset)->limit($this->limit)->asArray()->all();
        array_walk($list, function (&$val){
            $val['uid_arr'] = InformUser::find()->select(['uid'])->where(['inform_id' => $val['id']])->column();
        });

        return $this->json([
            'list' => $list,
            'filter' => [
                0 => '请选择',
                1 => '成绩查询通知',
                2 => '准考证领取通知',
                3 => '考场查询通知',
                4 => '考试报名通知',
                5 => '大赛通知',
                6 => '定向通知',
            ],
            'page' => $this->page($total)
        ]);
    }

    public function actionAdd()
    {
        $request = \Yii::$app->request;
        $type = $request->post('type');
        $content = $request->post('content');
        $uid_arr = $request->post('uid_arr', []);

        if (!$type)
        {
            return $this->error('请选择通知类型');
        }

        if (!$content)
        {
            return $this->error('请输入通知内容');
        }
        if (!$uid_arr)
        {
            return $this->error('请输入选择通知对象');
        }
        $transaction = \Yii::$app->db->beginTransaction();//创建事务
        try {
            $inform = new Inform();
            $inform->type = $type;
            $inform->content = new Expression("COMPRESS(:content)", [':content' => $content]);
            $inform->create_at = date("Y-m-d H:i:s",time());
            if (!$inform->save(false))
            {
                $transaction->rollback();//回滚事务
                return $this->error('创建失败');
            }
            $rows = [];
            $uid_arr = array_unique($uid_arr);
            foreach ($uid_arr as $uid) {
                $rows[] = [
                    $uid,
                    $inform->id
                ];
            }
            if (!$rows) {
                $transaction->rollback();//回滚事务
                return $this->error('服务器繁忙，请稍后再试！');
            }

            $insert = \Yii::$app->db->createCommand()->batchInsert(InformUser::tableName(), ['uid', 'inform_id'], $rows)->execute();
            if (!$insert) {
                $transaction->rollback();//回滚事务
                return $this->error('服务器繁忙，请稍后再试！');
            }
            $transaction->commit();//提交事务
            return $this->ok('创建成功');
        } catch (\Exception $e) {
            $transaction->rollback();//回滚事务
            return $this->error('服务器繁忙，请稍后再试！');
        }
    }

    public function actionEdit()
    {
        $request = \Yii::$app->request;
        $id = $request->post('id');
        $type = $request->post('type');
        $content = $request->post('content');
        $uid_arr = $request->post('uid_arr', []);

        if (!$type)
        {
            return $this->error('请选择通知类型');
        }

        if (!$content)
        {
            return $this->error('请输入通知内容');
        }
        if (!$uid_arr)
        {
            return $this->error('请输入选择通知对象');
        }
        $inform = Inform::findOne($id);
        if (!$inform)
        {
            return $this->error('通知不存在');
        }
        $transaction = \Yii::$app->db->beginTransaction();//创建事务
        try {
            $inform->type = $type;
            $inform->content = new Expression("COMPRESS(:content)", [':content' => $content]);
            $inform->create_at = date("Y-m-d H:i:s",time());
            if (!$inform->save(false))
            {
                $transaction->rollback();//回滚事务
                return $this->error('创建失败');
            }
            $rows = [];
            $uid_arr = array_unique($uid_arr);
            foreach ($uid_arr as $uid) {
                $rows[] = [
                    $uid,
                    $inform->id
                ];
            }
            if (!$rows) {
                $transaction->rollback();//回滚事务
                return $this->error('服务器繁忙，请稍后再试！');
            }
            InformUser::deleteAll(['inform_id' => $inform->id]);
            $insert = \Yii::$app->db->createCommand()->batchInsert(InformUser::tableName(), ['uid', 'inform_id'], $rows)->execute();
            if (!$insert) {
                $transaction->rollback();//回滚事务
                return $this->error('服务器繁忙，请稍后再试！');
            }
            $transaction->commit();//提交事务
            return $this->ok('创建成功');
        } catch (\Exception $e) {
            $transaction->rollback();//回滚事务
            return $this->error('服务器繁忙，请稍后再试！');
        }


    }

    public function actionDelete()
    {
        $id = \Yii::$app->request->post('id');

        Inform::deleteAll(['id' => $id]);
        InformUser::deleteAll(['inform_id' => $id]);

        return $this->ok('删除成功');
    }

}
