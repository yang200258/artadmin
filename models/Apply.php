<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "apply".
 *
 * @property string $id
 * @property string $apply_no 报名单号
 * @property string $uid 考生用户ID
 * @property string $exam_id 考试信息ID
 * @property string $picture_id 考生照片ID
 * @property string $name 姓名
 * @property string $pinyin 拼音
 * @property string $sex 性别
 * @property string $birth 出生日期
 * @property string $nationality 国籍
 * @property string $nation 民族
 * @property string $id_type 证件类型
 * @property string $id_number 证件号码
 * @property string $domain 报考专业
 * @property string $level 报考级别
 * @property string $continuous_level 连考级别
 * @property string $is_continuous 是否连考
 * @property string $lately_credential 最近一次获得同专业考级证书
 * @property string $pro_certificate_id 专业证书ID
 * @property string $basic_certificate_id 基本证书ID
 * @property string $track_one 曲目1
 * @property string $track_two 曲目2
 * @property string $track_three 曲目3
 * @property string $track_four 曲目4
 * @property string $track_five 曲目5
 * @property string $linkman 联系人
 * @property string $phone 联系方式
 * @property string $preparer 填表人
 * @property string $adviser 指导老师
 * @property string $adviser_phone 指导老师电话
 * @property int $status 审核状态：1=待审核2=不通过3=无需审核4已通过
 * @property int $plan 当前进度：1=审核中2=待缴费3=已失效4=已缴费
 * @property int $cause 失效原因：两种原因（审核未通过；未在规定报名时间内完成缴费）
 * @property int $postpone 是否缺考顺延
 * @property int $exam_site_id1 考场ID
 * @property int $exam_site_id2 考场ID2（连考情况需安排两个考场）
 * @property string $create_at 创建时间
 * @property string $bm 报名表（评审表）PDF文件名
 * @property string $bm_continuous 连考报名表（评审表）PDF文件名
 * @property string $kz 准考证PDF文件名
 * @property string $mini_form_id 小程序formId，用于发送模板消息
 */
class Apply extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'apply';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'exam_id', 'picture_id', 'pro_certificate_id', 'basic_certificate_id', 'is_continuous', 'status', 'plan', 'postpone', 'exam_site_id1', 'exam_site_id2'], 'integer'],
            [['birth', 'create_at'], 'required'],
            [['birth', 'create_at'], 'safe'],
            [['sex'], 'string', 'max' => 4],
            [['apply_no', 'name', 'nationality', 'nation', 'id_type', 'domain', 'level', 'continuous_level', 'linkman', 'phone', 'preparer', 'adviser', 'adviser_phone', 'cause'], 'string', 'max' => 32],
            [['pinyin', 'id_number', 'lately_credential', 'track_one', 'track_two', 'track_three', 'track_four', 'track_five', 'bm', 'kz'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'apply_no' => '报名单号',
            'uid' => '考生用户ID',
            'exam_id' => '考试信息ID',
            'picture_id' => '考生照片ID',
            'name' => '姓名',
            'pinyin' => '拼音',
            'sex' => '性别',
            'birth' => '出生日期',
            'nationality' => '国籍',
            'nation' => '民族',
            'id_type' => '证件类型',
            'id_number' => '证件号码',
            'domain' => '报考专业',
            'level' => '报考级别',
            'continuous_level' => '连考级别',
            'is_continuous' => '基本乐科是否连考',
            'lately_credential' => '最近一次获得同专业考级证书',
            'pro_certificate_id' => '专业证书ID',
            'basic_certificate_id' => '基本证书ID',
            'track_one' => '曲目1',
            'track_two' => '曲目2',
            'track_three' => '曲目3',
            'track_four' => '曲目4',
            'track_five' => '曲目5',
            'linkman' => '联系人',
            'phone' => '联系方式',
            'preparer' => '填表人',
            'adviser' => '指导老师',
            'adviser_phone' => '指导老师电话',
            'status' => '审核状态：1=待审核2=不通过3=无需审核4已通过',
            'plan' => '当前进度：1=审核中2=待缴费3=已失效4=已缴费',
            'cause' => '失效原因：两种原因（审核未通过；未在规定报名时间内完成缴费）',
            'postpone' => '是否缺考顺延',
            'exam_site_id1' => '考场ID',
            'exam_site_id2' => '考场ID2（连考情况需安排两个考场）',
            'create_at' => '创建时间',
            'bm' => '报名表（评审表）PDF文件名',
            'kz' => '准考证PDF文件名',
        ];
    }

    /**
     * @return string
     * 生成报名单号
     */
    public static function getApplynum($name)
    {
        $time = substr(date('Ymd'),2) . str_pad(strtotime(date('YmdHis')) - strtotime(date('Ymd')), 5, '0', STR_PAD_LEFT);
        $obj = new Counter();
        $obj->save(false);
        $lastInsertID = \Yii::$app->db->getLastInsertID();
        $num = str_pad(substr($lastInsertID, -4), 4, '0', STR_PAD_LEFT);
        $orderNum = static::getInitial($name) . $time . $num;
        return $orderNum;
    }

    public function getExam()
    {
        return $this->hasOne(Exam::className(), ['id' => 'exam_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'uid'])->select('id,name,organ_name,type');
    }

    public function getExamsite1()
    {
        return $this->hasOne(ExamSite::className(), ['id' => 'exam_site_id1']);
    }

    public function getExamsite2()
    {
        return $this->hasOne(ExamSite::className(), ['id' => 'exam_site_id2']);
    }

    public function getPay()
    {
        return $this->hasOne(ApplyPay::className(), ['apply_id' => 'id']);
    }

    //大类获取首字母
    public static function getInitial($name)
    {
        $category = [
            '竹笛' => 'M',
            '唢呐' => 'M',
            '琵琶' => 'M',
            '扬琴' => 'M',
            '阮' => 'M',
            '二胡' => 'M',
            '中国鼓' => 'M',
            '三弦' => 'M',
            '笙' => 'M',
            '古筝' => 'M',
            '巴乌' => 'M',
            '葫芦丝' => 'M',
            '钢琴' => 'J',
            '电子琴' => 'J',
            '手风琴' => 'J',
            '电子琴(双排键)' => 'J',
            '数码钢琴' => 'J',
            '少儿声乐' => 'S',
            '美声唱法' => 'S',
            '民族唱法' => 'S',
            '通俗唱法' => 'S',
            '单簧管' => 'X',
            '小号' => 'X',
            '长号' => 'X',
            '次中音号' => 'X',
            '低音提琴' => 'X',
            '马林巴' => 'X',
            '大提琴' => 'X',
            '圆号' => 'X',
            '大号' => 'X',
            '萨克斯' => 'X',
            '长笛' => 'X',
            '小提琴' => 'X',
            '古典吉他' => 'X',
            '民谣吉他' => 'X',
            '尤克里里' => 'X',
            '爵士鼓（电爵士鼓）' => 'X',
            '小鼓' => 'X',
            '基本乐科' => 'Y',
            '朗诵' => 'L',
        ];

        return $category[$name];
    }
}
