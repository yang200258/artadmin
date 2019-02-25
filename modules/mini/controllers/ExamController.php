<?php

namespace app\modules\mini\controllers;



use app\models\Exam;

class ExamController extends Controller
{
    //获取考试信息
    public function actionIndex()
    {
        $exam = Exam::find()->orderBy('id desc')->asArray()->one();
        if (!$exam)
        {
            return $this->json(['is_exam' => false]);
        }
        //服务器当前时间
        $server_time = date('Y-m-d H:i:s');

        if ($server_time < $exam['apply_time_start'] || $server_time > $exam['apply_time_end'])
        {
            return $this->json(['is_apply' => false]);
        }
        $data['exam'] = $exam;
        $data['is_apply'] = true;
        $data['url'] = 'https://www.baidu.com/'; //简章链接

        return $this->json($data);
    }








}
