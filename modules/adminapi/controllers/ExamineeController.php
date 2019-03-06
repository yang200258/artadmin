<?php

namespace app\modules\adminapi\controllers;


use app\helpers\Pdf;
use app\models\Apply;
use app\models\Exam;
use app\models\ExamExaminee;
use app\models\ExamSite;
use app\models\Record;
use app\models\User;

class ExamineeController extends Controller
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

    //某考试所有考生列表-添加考生入考场时使用
    public function actionIndex()
    {
        $this->init_page();
        $request = \Yii::$app->request;
        $exam_id = $request->post('exam_id'); //必填参数
        $name = $request->post('name', '');
        $domain = $request->post('domain', '');
        $level = $request->post('level', '');
        $id_number = $request->post('id_number', '');
        $organ_name = $request->post('organ_name', ''); //机构名称
        $teacher_name = $request->post('teacher_name', '');//老师名称;

        $model = Apply::find()->with('user')
            ->andWhere(['exam_id' => $exam_id])
            ->andWhere(['plan' => 4]) //已缴费
            ->andWhere(['or', ['exam_site_id1' => 0], ['exam_site_id2' => 0, 'is_continuous' => 1]])
            ->andFilterWhere(['name' => $name])
            ->andFilterWhere(['domain' => $domain])
            ->andFilterWhere(['level' => $level])
            ->andFilterWhere(['id_number' => $id_number]);

        if ($organ_name)
        {
            $uid = User::find()->select('id')->where(['organ_name' => $organ_name, 'type' => 2])->scalar();
            $uid = $uid ? $uid : 0;
            $model->andWhere(['uid' => $uid]);
        }
        if ($teacher_name)
        {
            $uid = User::find()->select('id')->where(['name' => $teacher_name, 'type' => 1])->scalar();
            $uid = $uid ? $uid : 0;
            $model->andWhere(['uid' => $uid]);
        }

        $total = $model->count();
        $list = $model->orderBy('id desc')->offset($this->offset)->limit($this->limit)->asArray()->all();

        return $this->json(['list' => $list, 'page' => $this->page($total)]);
    }

    //考生安排列表
    public function actionList()
    {
        $request = \Yii::$app->request;
        $exam_site_id = $request->post('exam_site_id'); //必填参数,考场ID

        $model = ExamExaminee::find()->with(['examsite', 'apply.user'])
            ->where(['exam_site_id' => $exam_site_id]);

        $total = $model->count();
        $list = $model->orderBy('id desc')->offset($this->offset)->limit($this->limit)->asArray()->all();

        return $this->json(['list' => $list, 'total' => $total]);
    }


    //添加考生到考场
    public function actionSeat()
    {
        $request = \Yii::$app->request;
        $exam_site_id = $request->post('exam_site_id'); //必填参数,考场ID
        $apply_id_arr = $request->post('apply_id_arr'); //必填参数,考生报名ID

        if (!$exam_site_id || !$apply_id_arr)
        {
            return $this->error('参数错误');
        }

        $exam_site = ExamSite::findOne($exam_site_id);
        if (!$exam_site)
        {
            return $this->error('考场不存在');
        }
        $examName = Exam::findOne($exam_site->exam_id)->name;

        $apply = Apply::find()->where(['id' => $apply_id_arr])->all();

        foreach ($apply as $one)
        {
            ExamExaminee::saveExamExaminee($exam_site_id, $one);
            Apply::updateAll(['kz' =>  Pdf::createPdfExam($one->id)], ['id' => $one->id]);
            Record::saveRecord($this->admin->id, 2, "{$examName}-考点{$exam_site->address}-{$exam_site->room}-{$exam_site->exam_time}-{$one->name}");
        }

        return $this->ok('添加成功');
    }

    //考生排序
    public function actionSort()
    {
        $request = \Yii::$app->request;
        $exam_site_id = $request->post('exam_site_id '); //考场ID
        $id_arr = $request->post('id_arr', []); //必填参数

        if (!$id_arr || !$exam_site_id)
        {
            return $this->error('参数错误');
        }
        $ExamSite = ExamSite::findOne(['id' => $exam_site_id]);
        if (!$ExamSite)
        {
            return $this->error('考场不存在或者被删除');
        }
        $i = 0;
        foreach ($id_arr as $one)
        {
            $i++;
            ExamExaminee::updateAll(['sort' => $i], ['id' => $one, 'exam_site_id' => $exam_site_id]);
        }

        return $this->ok('排序成功');
    }
}
