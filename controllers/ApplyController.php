<?php

namespace app\controllers;

use app\helpers\Pdf;
use app\models\Apply;
use app\models\ApplyPay;
use app\models\Exam;
use app\models\Image;

class ApplyController extends Controller
{
    //报名
    public function actionAdd()
    {
        $request = \Yii::$app->request;
        $exam_id = $request->post('exam_id', 0);  //考试ID
        $picture_id = $request->post('picture_id', 0); //考生照片ID
        $name = $request->post('name', '');  //考生姓名
        $pinyin = $request->post('pinyin', ''); //考生拼音
        $sex = $request->post('sex', '');  //性别
        $birth = $request->post('birth', ''); //出生日期
        $nationality = $request->post('nationality', ''); //国籍
        $nation = $request->post('nation', ''); //民族
        $id_type = $request->post('id_type', ''); //证件类型
        $id_number = $request->post('id_number', ''); //证件号码
        $domain = $request->post('domain', ''); //报考专业
        $level = $request->post('level', ''); //报考级别
        $continuous_level = $request->post('continuous_level', ''); //连考级别
        $lately_credential = $request->post('lately_credential', 0); //最近一次获得同专业考级证书
        $pro_certificate_id = $request->post('pro_certificate_id', 0); //专业证书ID
        $basic_certificate_id = $request->post('basic_certificate_id', 0); //基本证书ID
        $track_one = $request->post('track_one', ''); //曲目1
        $track_two = $request->post('track_two', ''); //曲目2
        $track_three = $request->post('track_three', ''); //曲目3
        $track_four = $request->post('track_four', ''); //曲目4
        $track_five = $request->post('track_five', ''); //曲目5
        $phone = $request->post('phone', ''); //联系方式
        $preparer = $request->post('preparer', ''); //填表人
        $adviser = $request->post('adviser', ''); //指导老师
        $adviser_phone = $request->post('adviser_phone', ''); //指导老师电话
        $continuous_level = $continuous_level == '否' ? '' : $continuous_level;

        $exam = Exam::findOne($exam_id);
        if (!$exam)
        {
            return $this->error('该考试不存在');
        }
        if ($this->getStatus($exam) != 2)
        {
            return $this->error('不在报名时间内');
        }

        if (!$picture_id)
        {
            return $this->error('请上传照片');
        }
        if (!$name)
        {
            return $this->error('请输入姓名');
        }
        if (!$pinyin)
        {
            return $this->error('请输入拼音');
        }
        if (!$sex)
        {
            return $this->error('请输入性别');
        }
        if (!$birth)
        {
            return $this->error('请输入出生年月日');
        }
        if (!$nationality)
        {
            return $this->error('请输入国籍');
        }
        if (!$nation)
        {
            return $this->error('请输入民族');
        }
        if (!$id_type)
        {
            return $this->error('请输入证件类型');
        }
        if (!$id_number)
        {
            return $this->error('请输入证件号码');
        }
        if (!$domain)
        {
            return $this->error('请选择报考专业');
        }
        if (!$level)
        {
            return $this->error('请选择报考级别');
        }
        if (!$track_one)
        {
            return $this->error('请输入曲目1');
        }
        if (!$track_two)
        {
            return $this->error('请输入曲目2');
        }
        if (!$phone)
        {
            return $this->error('请输入联系电话');
        }
        if (!$preparer)
        {
            return $this->error('请输入填表人');
        }
        if (!$adviser)
        {
            return $this->error('请输入指导老师');
        }
        if (!$adviser_phone)
        {
            return $this->error('请输入指导老师电话');
        }
        $transaction = \Yii::$app->db->beginTransaction();//创建事务
        try {
            $apply = new Apply();
            $apply->apply_no = Apply::getApplynum($domain);
            $apply->uid = $this->user->id;
            $apply->exam_id = $exam_id;
            $apply->picture_id = $picture_id;
            $apply->name = $name;
            $apply->pinyin = $pinyin;
            $apply->sex = $sex;
            $apply->birth = $birth;
            $apply->nationality = $nationality;
            $apply->nation = $nation;
            $apply->id_type = $id_type;
            $apply->id_number = $id_number;
            $apply->domain = $domain;
            $apply->level = $level;
            $apply->continuous_level = $continuous_level;
            if ($continuous_level)
            {
                $apply->is_continuous = 1;
            }
            $apply->lately_credential = $lately_credential;
            $apply->pro_certificate_id = $pro_certificate_id ? $pro_certificate_id : 0;
            $apply->basic_certificate_id = $basic_certificate_id ? $basic_certificate_id : 0;
            $apply->track_one = $track_one;
            $apply->track_two = $track_two;
            $apply->track_three = $track_three;
            $apply->track_four = $track_four;
            $apply->track_five = $track_five;
            $apply->phone = $phone;
            $apply->preparer = $preparer;
            $apply->adviser = $adviser;
            $apply->adviser_phone = $adviser_phone;
            $apply->create_at = date('Y-m-d H:i:s');
            if (in_array($this->user->type, [1, 2]))   //如果是老师或者机构报名，相当于直接缴费
            {
                $apply->status = 3;
                $apply->plan = 4;
            }
            if (!$apply->save(false))
            {
                $transaction->rollback();//回滚事务
                return $this->error('报名失败');
            }

            $apply->bm = Pdf::createPdfApply($apply->id); //生成报名表
            $apply->bm_continuous = $continuous_level ? $apply->bm . '_continuous' : '';// 连考报名表
            $apply->save(false);
            //计算考收取费用，如果连考收两级费用
            $price = ApplyPay::$rates[$level];
            if ($continuous_level)
            {
                $price = $price + ApplyPay::$rates[$continuous_level];
            }
            $apply_pay = new ApplyPay();
            $apply_pay->apply_id = $apply->id;
            $apply_pay->price = 2;
            if (in_array($this->user->type, [1, 2]))   //如果是老师或者机构报名，相当于直接缴费
            {
                $apply_pay->type = 2;
                $apply_pay->status = 1;
                $apply_pay->pay_time = date('Y-m-d H:i:s');
            }
            $apply_pay->create_at = date('Y-m-d H:i:s');
            if(!$apply_pay->save(false))
            {
                $transaction->rollback();//回滚事务
                return $this->error('报名失败');
            }
            $transaction->commit();//提交事务
        } catch (\Exception $e) {
            \Yii::error($e->getMessage());
            $transaction->rollback();//回滚事务
            return $this->error('服务器繁忙，请稍后再试！');
        }
        return $this->json(['id' => $apply->id]);
    }

    public function actionList()
    {
        $this->init_page();
        $request = \Yii::$app->request;
        $name = $request->post('name');
        $domain = $request->post('domain');
        $level = $request->post('level');
        $start_time = $request->post('start_time');
        $end_time = $request->post('end_time');

        $model = Apply::find()->with(['user', 'exam'])
            ->andWhere(['uid' => $this->userid])
            ->andFilterWhere(['name' => $name])
            ->andFilterWhere(['domain' => $domain])
            ->andFilterWhere(['level' => $level])
            ->andFilterWhere(['>=', 'create_at', $start_time])
            ->andFilterWhere(['<=', 'create_at', $end_time ? $end_time . " 23:59:59" : null]);

        $total = $model->count();
        $list = $model->orderBy('create_at desc')->offset($this->offset)->limit($this->limit)->asArray()->all();

        return $this->json(['list' => $list, 'page' => $this->page($total)]);
    }


    public function actionDetail()
    {
        $id = \Yii::$app->request->post('id');
        $apply = Apply::find()
            ->with(['pay', 'examsite1', 'examsite2'])
            ->where(['id' => $id])
            ->andWhere(['uid' => $this->userid])
            ->asArray()->one();
        if (!$apply)
        {
            return $this->error('报名不存在');
        }
        $apply['pay']['price'] = $apply['pay']['price'] / 100;
        if ($apply['continuous_level'])
        {
            $apply['pay']['domain'] = [
                [
                    'name' => $apply['domain'] . $apply['level'],
                    'price' => ApplyPay::$rates[$apply['level']] / 100 . '元',
                ],
                [
                    'name' => $apply['domain'] . $apply['continuous_level'],
                    'price' => ApplyPay::$rates[$apply['continuous_level']] / 100 . '元',
                ],
            ];
        }else
        {
            $apply['pay']['domain'] = [
                [
                    'name' => $apply['domain'] . $apply['level'],
                    'price' => ApplyPay::$rates[$apply['level']] / 100 . '元',
                ]
            ];
        }
        $apply['pro_certificate_url'] = $apply['pro_certificate_id'] ? Image::getAbsoluteUrlById($apply['pro_certificate_id']) : '';
        $apply['basic_certificate_url'] = $apply['basic_certificate_id'] ? Image::getAbsoluteUrlById($apply['basic_certificate_id']) : '';
        $apply['bm_image_url'] = $apply['bm'] ? \Yii::$app->params['file_site'] . '/file/applyimg/'. $apply['bm'] . '.png' : '';

        return $this->json($apply);
    }
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
