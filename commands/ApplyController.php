<?php

namespace app\commands;



use app\models\Apply;
use app\models\Exam;

class ApplyController extends Controller
{
    //未在规定报名时间内完成缴费
    //为未审核
    //只要过了报名时间，则为已失效
    //本脚本每分钟执行一次

    public function actionCheck()
    {
        $time = date( 'Y-m-d H:i:s', time());

        //查询最近的5次考试
        $exam = Exam::find()->where(['>', 'apply_time_end', $time])->limit(5)->all();

        foreach ($exam as $one)
        {
            Apply::updateAll(['plan' => 3,'cause' => '规定时间内审核未通过'], ['exam_id' => $one->id, 'status' => 1, 'plan' => 1]);  //未审核的
            Apply::updateAll(['plan' => 3,'cause' => '未在规定报名时间内完成缴费'], ['exam_id' => $one->id, 'plan' => 2]);  //未在规定报名时间内完成缴费
        }

    }

}