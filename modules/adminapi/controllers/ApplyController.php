<?php

namespace app\modules\adminapi\controllers;


use app\models\Apply;
use app\models\ApplyPay;
use app\models\ExamExaminee;
use app\models\Image;
use app\models\Inform;
use app\models\InformUser;
use app\models\Record;
use app\models\User;
use yii\db\Expression;

class ApplyController extends Controller
{
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action))
        {
            return false;
        }

        if (!$this->admin->apply)
        {
            $this->throwForbidden();
        }
        return true;
    }

    //列表
    public function actionList()
    {
        $this->init_page();
        $request = \Yii::$app->request;
        $name = $request->post('name'); //考生姓名
        $domain = $request->post('domain');  //专业
        $level = $request->post('level'); //等级
        $id_type = $request->post('id_type'); //证件类型
        $id_number = $request->post('id_number'); //证件号码
        $status = $request->post('status'); //审核状态：1=待审核2=不通过3=无需审核4已通过
        $plan = $request->post('plan'); //当前进度：1=审核中2=待缴费3=已失效4=已缴费
        $postpone = $request->post('postpone'); //是否缺考顺延
        $organ_name = $request->post('organ_name'); //机构名称
        $teacher_name = $request->post('teacher_name');//老师名称
        $start_time = $request->post('start_time');
        $end_time = $request->post('end_time');

        $model = Apply::find()->with(['pay', 'user', 'examsite1', 'examsite2'])
            ->andFilterWhere(['LIKE', 'name', $name])
            ->andFilterWhere(['domain' => $domain])
            ->andFilterWhere(['level' => $level])
            ->andFilterWhere(['id_type' => $id_type])
            ->andFilterWhere(['id_number' => $id_number])
            ->andFilterWhere(['status' => $status])
            ->andFilterWhere(['plan' => $plan])
            ->andFilterWhere(['>=', 'create_at', $start_time])
            ->andFilterWhere(['<=', 'create_at', $end_time ? $end_time . ' 23:59:59': '']);
        if ($postpone)
        {
            $model->andWhere(['postpone' => $postpone-1]);
        }
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
        $list = $model->orderBy('create_at desc')->offset($this->offset)->limit($this->limit)->asArray()->all();

        return $this->json(['list' => $list, 'page' => $this->page($total)]);
    }

    //详情
    public function actionDetail()
    {
        $id = \Yii::$app->request->post('id');
        $apply = Apply::find()->with(['exam', 'pay', 'user', 'examsite1', 'examsite2'])->where(['id' => $id])->asArray()->one();
        if (!$apply)
        {
            return $this->error('报名不存在');
        }
        $apply['picture_url'] = $apply['picture_id'] ? Image::getAbsoluteUrlById($apply['picture_id']) : '';
        $apply['pro_certificate_url'] = $apply['pro_certificate_id'] ? Image::getAbsoluteUrlById($apply['pro_certificate_id']) : '';
        $apply['basic_certificate_url'] = $apply['basic_certificate_id'] ? Image::getAbsoluteUrlById($apply['basic_certificate_id']) : '';
        $apply['pay']['price'] =  $apply['pay']['price'] / 100;
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
        if ($apply['examsite1'])
        {
            $apply['examsite1']['sort'] = ExamExaminee::find()->select('sort')
                ->where(['exam_site_id' => $apply['examsite1']['id']])
                ->andWhere(['apply_id' => $apply['id']])->scalar();
        }
        if ($apply['examsite2'])
        {
            $apply['examsite2']['sort'] = ExamExaminee::find()->select('sort')
                ->where(['exam_site_id' => $apply['examsite2']['id']])
                ->andWhere(['apply_id' => $apply['id']])->scalar();
        }

        $apply['kz_image_url'] = $apply['kz'] ? \Yii::$app->params['file_site'] . '/file/examimg/'. $apply['kz'] . '.png' : '';
        $apply['bm_image_url'] = $apply['bm'] ? \Yii::$app->params['file_site'] . '/file/applyimg/'. $apply['bm'] . '.png' : '';
        $apply['bm_continuous_image_url'] = $apply['bm_continuous'] ? \Yii::$app->params['file_site'] . '/file/applyimg/'. $apply['bm_continuous'] . '.png' : '';

        $apply['kz_url'] = $apply['kz'] ? \Yii::$app->params['file_site'] . '/file/exam/'. $apply['kz'] . '.pdf' : '';
        $apply['bm_url'] = $apply['bm'] ? \Yii::$app->params['file_site'] . '/file/apply/'. $apply['bm'] . '.pdf' : '';
        $apply['bm_continuous_url'] = $apply['bm_continuous'] ? \Yii::$app->params['file_site'] . '/file/apply/'. $apply['bm_continuous'] . '.pdf' : '';

        return $this->json($apply);
    }

    //审核考生信息，只有未审核的考生可以审核
    public function actionCheck()
    {
        $request = \Yii::$app->request;
        $apply_id = $request->post('id');
        $status = $request->post('status');

        $apply = Apply::findOne($apply_id);
        if (!$apply)
        {
            return $this->error('报名不存在');
        }
        if ($apply->status != 1)
        {
            return $this->error('该报名非待审核，不能审核');
        }
        if (!in_array($status, [2, 4])) //2为不通过4为通过
        {
            return $this->error('参数错误');
        }
        $transaction = \Yii::$app->db->beginTransaction();//创建事务
        try {
            $inform = new Inform();
            if ($status == 4) //通过
            {
                Apply::updateAll(['status' => 4, 'plan' => 2], ['id' => $apply->id]); //已通过、待缴费
                $content = '您提交的中国音乐学院全国社会艺术水平考级报名信息已通过审核，请在规定报名时间内完成缴费！若逾期未缴费，视为放弃报名。';
                $inform->type = 8;
            }else //不通过
            {
                Apply::updateAll(['status' => 2, 'plan' => 3, 'cause' => '审核未通过'], ['id' => $apply->id]); //未通过、已失效
                $content = '您提交的中国音乐学院全国社会艺术水平考级报名信息未通过审核。请上传正确的相关证书，并在规定报名时间内重新进行报名。';
                $inform->type = 7;
            }
            $inform->content = new Expression("COMPRESS(:content)", [':content' => $content]);
            if (!$inform->save(false))
            {
                $transaction->rollback();//回滚事务
                return $this->error('创建失败');
            }
            $inform_user = new InformUser();
            $inform_user->uid = $apply->uid;
            $inform_user->inform_id = $inform->id;
            $inform_user->apply_id = $apply->id;
            if (!$inform_user->save(false))
            {
                $transaction->rollback();//回滚事务
                return $this->error('创建失败');
            }
            Record::saveRecord($this->admin->id, 1, ($status == 4 ? '通过' : '未通过') . "审核：报名编号[$apply_id]");
            $transaction->commit();//提交事务
            return $this->ok('审核完成');
        } catch (\Exception $e) {
            $transaction->rollback();//回滚事务
            return $this->error('服务器繁忙，请稍后再试！');
        }
    }

    //考试顺延
    public function actionProlong()
    {
        $request = \Yii::$app->request;
        $apply_id = $request->post('apply_id');
        $status = $request->post('status');

    }

}
