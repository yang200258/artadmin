<?php

namespace app\modules\adminapi\controllers;



use app\models\Exam;
use app\models\ExamExaminee;
use app\models\ExamSite;
use app\models\Record;

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
            $one['room_num'] = ExamSite::find()->where(['exam_id' => $one['id']])->count('DISTINCT room');
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
                    "$name-考点{$one['address']}-新增“{$one['room']}”",
                    2,
                    date("Y-m-d H:i:s")
                ];
            }
            // 批量记录考点信息
            \Yii::$app->db->createCommand()
                ->batchInsert(ExamSite::tableName(), $examSiteRecordKey, $examSiteRecordData)
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
        ExamSite::deleteAll(['exam_id' => $exam->id]);

        //todo: 这里考点编辑处理比较直接，所以就先不记录考点的编辑信息了
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
            }
        }
        Record::saveRecord($this->admin->id, 2, "编辑考试：$name");

        return $this->ok('修改成功');
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
            ->andFilterWhere(['<', 'exam_time', $exam_time_start])
            ->andFilterWhere(['>', 'exam_time', $exam_time_end]);

        $total = $model->count();

        $list = $model->select('exam_site.id, exam_id, number, address, room, exam_time')->orderBy('id desc')->offset($this->offset)->limit($this->limit)->asArray()->all();
        foreach ($list as &$one)
        {
            $one['examinee_num'] = ExamExaminee::find()->where(['exam_site_id' => $one['id']])->count();
        }

        return $this->json(['list' => $list, 'page' => $this->page($total)]);
    }
}
