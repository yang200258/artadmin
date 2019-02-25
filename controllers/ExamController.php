<?php

namespace home\controllers;

use app\models\Exam;

class ExamController extends Controller
{
    public function actionList()
    {
        $this->init_page();
        $model = Exam::find();
        $total = $model->count();
        $list = $model->orderBy('id desc')->offset($this->offset)->limit($this->limit)->asArray()->all();
        array_walk($list, function (&$val){
            $val['status'] = $this->getStatus($val);
        });

        return $this->json(['list' => $list, 'page' => $this->page($total)]);
    }

    //1未报名2报名中3未考试4考试中5已结束
    public function getStatus($exam)
    {
        $time = strtotime(date('Y-m-d H:i:s', time()));

        if ($time < strtotime($exam['apply_time_start']))
        {
            $res = 1;
        }elseif (strtotime($exam['apply_time_start']) < $time  && $time < strtotime($exam['apply_time_end']))
        {
            $res = 2;
        }elseif (strtotime($exam['apply_time_end']) < $time  && $time < strtotime($exam['exam_time_start']))
        {
            $res = 3;
        }elseif (strtotime($exam['exam_time_start']) < $time  && $time < strtotime($exam['exam_time_end']))
        {
            $res = 4;
        }elseif (strtotime($exam['exam_time_end']) < $time)
        {
            $res = 5;
        }else
        {
            $res = '';
        }

        return $res;
    }
}
