<?php

namespace app\modules\admin\controllers;


use app\models\ExamExaminee;
use app\models\ExamSite;

class RoomController extends Controller
{
    public function actionIndex()
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
