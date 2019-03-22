<?php

namespace app\modules\mini\controllers;



use app\models\Exam;

class ExamController extends Controller
{
    //获取考试信息
    public function actionIndex()
    {
        $serverTime = date('Y-m-d H:i:s');

        $exam = Exam::find()
            ->where(['<', 'apply_time_start', $serverTime])
            ->where(['>', 'apply_time_end', $serverTime])
            ->orderBy('id desc')->asArray()->one();
        if (!$exam)
        {
            return $this->json(['is_exam' => false]);
        }

        $data['exam'] = $exam;
        $data['is_apply'] = true;
        $data['url'] = $this->createMiniUrl('/miniappregulation'); //简章链接

        return $this->json($data);
    }








}
