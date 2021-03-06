<?php

namespace app\modules\adminapi\controllers;



use app\models\Apply;
use app\models\Exam;
use app\models\ExamExaminee;
use app\models\ExamSite;
use app\models\Record;
use yii\db\Exception;
use yii\db\StaleObjectException;

class ExamController extends Controller
{
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action))
        {
            return false;
        }

        if (!$this->admin->exam)
        {
            $this->throwForbidden();
        }
        return true;
    }

    //考试信息列表
    public function actionList()
    {
        $this->init_page();

        $request = \Yii::$app->request;
        $number = $request->post('number');
        $name = $request->post('name');
        $status = $request->post('status');  //1=未报名2=报名中3=未考试4=考试中5=已结束
        $apply_time_start = $request->post('apply_time_start');
        $apply_time_end = $request->post('apply_time_end');
        $exam_time_start = $request->post('exam_time_start');
        $exam_time_end = $request->post('exam_time_end');

        $model = Exam::find()
            ->andFilterWhere(['number' => $number])
            ->andFilterWhere(['name' => $name])
            ->andFilterWhere(['<', 'apply_time_start', $apply_time_start])
            ->andFilterWhere(['>', 'apply_time_end', $apply_time_end])
            ->andFilterWhere(['<', 'exam_time_start', $exam_time_start])
            ->andFilterWhere(['>', 'exam_time_end', $exam_time_end]);
        if ($status)
        {
            $time = date('Y-m-d H:i:s', time());
            switch ($status)
            {
                case 1:
                    $model->andFilterWhere(['>', 'apply_time_start', $time]);
                    break;
                case 2:
                    $model->andFilterWhere(['<', 'apply_time_start', $time])->andFilterWhere(['>', 'apply_time_end', $time]);
                    break;
                case 3:
                    $model->andFilterWhere(['<', 'apply_time_end', $time])->andFilterWhere(['>', 'exam_time_start', $time]);
                    break;
                case 4:
                    $model->andFilterWhere(['<', 'exam_time_start', $time])->andFilterWhere(['>', 'exam_time_end', $time]);
                    break;
                case 5:
                    $model->andFilterWhere(['<', 'exam_time_end', $time]);
                    break;
                default;
            }
        }

        $total = $model->count();
        $list = $model->orderBy('create_at desc')->offset($this->offset)->limit($this->limit)->asArray()->all();
        foreach ($list as &$one)
        {
            $one['site_num'] = ExamSite::find()->where(['exam_id' => $one['id']])->count('DISTINCT address');
            $one['room_num'] = ExamSite::find()->where(['exam_id' => $one['id']])->count('DISTINCT address,room');
            $one['status_name'] = $this->getStatus($one);
        }

        return $this->json(['list' => $list, 'page' => $this->page($total)]);
    }

    public function getStatus($exam)
    {
        $time = strtotime(date('Y-m-d H:i:s', time()));

        if ($time < strtotime($exam['apply_time_start']))
        {
            $res = '未报名';
        }elseif (strtotime($exam['apply_time_start']) < $time  && $time < strtotime($exam['apply_time_end']))
        {
            $res = '报名中';
        }elseif (strtotime($exam['apply_time_end']) < $time  && $time < strtotime($exam['exam_time_start']))
        {
            $res = '未考试';
        }elseif (strtotime($exam['exam_time_start']) < $time  && $time < strtotime($exam['exam_time_end']))
        {
            $res = '考试中';
        }elseif (strtotime($exam['exam_time_end']) < $time)
        {
            $res = '已结束';
        }else
        {
            $res = '';
        }

        return $res;
    }

    //添加
    public function actionAdd()
    {
        $request = \Yii::$app->request;
        $number = $request->post('number');
        $name = $request->post('name');
        $apply_time_start = $request->post('apply_time_start');
        $apply_time_end = $request->post('apply_time_end');
        $exam_time_start = $request->post('exam_time_start');
        $exam_time_end = $request->post('exam_time_end');
        $exam_site = $request->post('exam_site');

        if (!$number)
        {
            return $this->error('请填写考试编号');
        }

        if (!$name)
        {
            return $this->error('请填写考试名称');
        }

        if (!$apply_time_start || !$apply_time_end)
        {
            return $this->error('请填写报名起止日期');
        }

        if (!$exam_time_start || !$exam_time_end)
        {
            return $this->error('请填写考试起止日期');
        }

        $ex = Exam::find()->where(['number' => $number])->exists();
        if ($ex)
        {
            return $this->error('存在相同的考试编号，请核实，重新输入');
        }

        // 查询是否有相同时间段的考试
        $duplicateExam = Exam::find()
            ->where(['and', ['<', 'apply_time_start', $apply_time_end], ['>', 'apply_time_end', $apply_time_start]])
            ->orWhere(['and', ['<', 'exam_time_start', $exam_time_end], ['>', 'exam_time_end', $exam_time_start]])
            ->asArray()
            ->one();
//        var_dump($duplicateExam);die;
        if ($duplicateExam) {
            if ($duplicateExam['apply_time_start'] < $apply_time_end && $duplicateExam['apply_time_end'] > $apply_time_start) {
                return $this->error("报名时间{$apply_time_start}---{$apply_time_end}与考试“{$duplicateExam['name']}”报名时间{$duplicateExam['apply_time_start']}---{$duplicateExam['apply_time_end']}冲突");
            } else {
                return $this->error("考试时间{$exam_time_start}---{$exam_time_end}与考试“{$duplicateExam['name']}”考试时间{$duplicateExam['exam_time_start']}---{$duplicateExam['exam_time_end']}冲突");
            } 
        }

        $exam = new Exam();
        $exam->number = $number;
        $exam->name = $name;
        $exam->apply_time_start = $apply_time_start;
        $exam->apply_time_end = $apply_time_end;
        $exam->exam_time_start = $exam_time_start;
        $exam->exam_time_end = $exam_time_end;
        $exam->create_at = date("Y-m-d H:i:s",time());
        $exam->save(false);

        $examSiteRecordKey = ['admin_id', 'content', 'type','create_at'];
        $examSiteRecordData = [];

        if ($exam_site)
        {
            foreach ($exam_site as $one)
            {
                $site = new ExamSite();
                $site->exam_id = $exam->id;
                $site->address = $one['address'];
                $site->room = $one['room'];
                $site->exam_time = $one['time'];
                $site->save(false);
                $examSiteRecordData[] = [
                    $this->admin->id,
                    "$name-考点{$one['address']}-新增考场“{$one['room']}”",
                    2,
                    date("Y-m-d H:i:s")
                ];
            }
            // 批量记录考点信息
            \Yii::$app->db->createCommand()
                ->batchInsert(Record::tableName(), $examSiteRecordKey, $examSiteRecordData)
                ->execute();
        }
        Record::saveRecord($this->admin->id, 2, "新增考试：$name");

        return $this->ok('创建成功');
    }

    //编辑
    public function actionEdit()
    {
        $request = \Yii::$app->request;
        $id = $request->post('id');
        $number = $request->post('number');
        $name = $request->post('name');
        $apply_time_start = $request->post('apply_time_start');
        $apply_time_end = $request->post('apply_time_end');
        $exam_time_start = $request->post('exam_time_start');
        $exam_time_end = $request->post('exam_time_end');
        $exam_site = $request->post('exam_site');

        if (!$number)
        {
            return $this->error('请填写考试编号');
        }

        if (!$name)
        {
            return $this->error('请填写考试名称');
        }

        if (!$apply_time_start || !$apply_time_end)
        {
            return $this->error('请填写报名起止日期');
        }

        if (!$exam_time_start || !$exam_time_end)
        {
            return $this->error('请填写考试起止日期');
        }

        $exam = Exam::findOne($id);
        if (!$exam)
        {
            return $this->error('该考试信息不存在');
        }
        $exam->number = $number;
        $exam->name = $name;
        $exam->apply_time_start = $apply_time_start;
        $exam->apply_time_end = $apply_time_end;
        $exam->exam_time_start = $exam_time_start;
        $exam->exam_time_end = $exam_time_end;
        $exam->save(false);

        if ($exam_site)
        {
            foreach ($exam_site as $one)
            {
                $site = ExamSite::findOne(['id' => $one['id'], 'exam_id' => $exam->id]);
                if (!$site)
                {
                    $site = new ExamSite();
                }
                $site->exam_id = $exam->id;
                $site->address = $one['address'];
                $site->room = $one['room'];
                $site->exam_time = $one['time'];
                $site->save(false);
            }
        }
        Record::saveRecord($this->admin->id, 2, "编辑考试：$name");

        return $this->ok('修改成功');
    }

    public function actionDetail()
    {
        $request = \Yii::$app->request;
        $id = $request->post('id');
        if (!$id)
        {
            return $this->error('参数错误');
        }
        $exam = Exam::findOne($id);
        if (!$exam)
        {
            return $this->error('考试不存在');
        }
        $ExamSite = ExamSite::find()->where(['exam_id' => $id])->asArray()->all();
        array_walk($ExamSite, function (&$val){
            $val['is_arrange'] = ExamExaminee::find()->where(['exam_site_id' => $val['id']])->exists();
        });

        return $this->json(['exam' => $exam, 'exam_site' => $ExamSite]);
    }

    public function actionDeleteExam()
    {
        $request = \Yii::$app->request;
        $id = $request->post('id');
        if (!$id)
        {
            return $this->error('参数错误');
        }

        $exam = Exam::findOne($id);
        if (!$exam) {
            return $this->error('考试不存在或已被删除');
        }
        $apply = Apply::find()->where(['exam_id' => $id])->exists();
        if ($apply)
        {
            return $this->error('该考试已有考生报名，不可删除');
        }
        $transaction = \Yii::$app->db->beginTransaction();//创建事务
        try {
            // 删除考试
            $exam->delete();
//            $examSiteIds = ExamSite::find()->where(['exam_id' => $id])->select('id')->column();
//            ExamSite::deleteAll(['exam_id' => $id]);// 删除考场信息
//            ExamExaminee::deleteAll(['exam_site_id' => $id]);// 删除排序信息
//            // 移除分配信息
//            Apply::updateAll(['exam_site_id1' => 0,'exam_site_id2' => 0, 'kz' => ''], ['exam_site_id1' => $examSiteIds]);
//            Apply::updateAll(['exam_site_id1' => 0,'exam_site_id2' => 0, 'kz' => ''], ['exam_site_id2' => $examSiteIds]);
            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            return $this->error('删除失败' . $e->getMessage());
        }
        return $this->ok('删除成功');
    }
    public function actionDeleteExamSite()
    {
        $request = \Yii::$app->request;
        $id = $request->post('id');
        if (!$id)
        {
            return $this->error('参数错误');
        }
        $ExamSite  = ExamSite::findOne($id);
        if (!$ExamSite)
        {
            return $this->error('考场不存在或已被删除');
        }
        //删除考场，清空考场关联信息
        Apply::updateAll(['exam_site_id1' => 0,'exam_site_id2' => 0, 'kz' => ''], ['exam_site_id1' => $id]);
        Apply::updateAll(['exam_site_id1' => 0,'exam_site_id2' => 0, 'kz' => ''], ['exam_site_id2' => $id]);
        ExamExaminee::deleteAll(['exam_site_id' => $id]);
        ExamSite::deleteAll(['id' => $id]);

        return $this->ok('删除成功');
    }


    public function actionRoom()
    {
        $this->init_page();
        $request = \Yii::$app->request;
        $number = $request->post('number');
        $address = $request->post('address');
        $exam_time_start = $request->post('exam_time_start');
        $exam_time_end = $request->post('exam_time_end');


        $model = ExamSite::find()
            ->innerJoin('exam', 'exam.id = exam_site.exam_id')
            ->andFilterWhere(['number' => $number])
            ->andFilterWhere(['address' => $address])
            ->andFilterWhere(['>=', 'exam_time', $exam_time_start])
            ->andFilterWhere(['<=', 'exam_time', $exam_time_end ? $exam_time_end . ' 23:59:59' : null]);

        $total = $model->count();

        $list = $model->select('exam_site.id, exam_id, number, address, room, exam_time')->orderBy('id desc')->offset($this->offset)->limit($this->limit)->asArray()->all();
        foreach ($list as &$one)
        {
            $one['examinee_num'] = ExamExaminee::find()->where(['exam_site_id' => $one['id']])->count();
        }

        return $this->json(['list' => $list, 'page' => $this->page($total)]);
    }
}
