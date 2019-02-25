<?php

namespace app\modules\admin\controllers;


use app\helpers\Pdf;
use app\models\Apply;
use app\models\ExamExaminee;
use app\models\ExamSite;
use app\models\User;

class ExamineeController extends Controller
{
    //某考试所有考生列表-添加考生入考场时使用
    public function actionIndex()
    {
        $this->init_page();
        $request = \Yii::$app->request;
        $exam_id = $request->post('exam_id'); //必填参数
        $name = $request->post('name');
        $domain = $request->post('domain');
        $level = $request->post('level');
        $id_number = $request->post('id_number');
        $organ_name = $request->post('organ_name'); //机构名称
        $teacher_name = $request->post('teacher_name');//老师名称;

        $model = Apply::find()
            ->andWhere(['exam_id' => $exam_id])
            ->andWhere(['status' => 4]) //审核通过
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

        $model = ExamExaminee::find()->with(['examsite', 'apply'])
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

        $apply = Apply::find()->where(['id' => $apply_id_arr])->all();

        foreach ($apply as $one)
        {
            ExamExaminee::saveExamExaminee($exam_site_id, $one);
            Apply::updateAll(['kz' =>  Pdf::createPdfExam($one->id)], ['id' => $one->id]);
        }

        return $this->ok('添加成功');
    }

    //考生排序
    public function actionSort()
    {
        $request = \Yii::$app->request;
        $sort_arr = $request->post('sort_arr', []); //必填参数

        if (!$sort_arr)
        {
            return $this->error('参数错误');
        }

        foreach ($sort_arr as $one)
        {
            ExamExaminee::updateAll(['sort' => $one['sort']], ['id' => $one['id']]);
        }

        return $this->ok('排序成功');
    }
}
