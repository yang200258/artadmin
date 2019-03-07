<?php
namespace app\modules\adminapi\controllers;


use app\helpers\Excel;
use app\models\Apply;
use app\models\ExamExaminee;
use app\models\ExamSite;
use app\models\Image;

class DownloadController extends Controller
{
    //批量下载报名表
    public function actionBm()
    {
        $request = \Yii::$app->request;
        $exam_site_id = $request->get('exam_site_id'); //考场ID
        if (!$exam_site_id)
        {
            return $this->error('参数错误');
        }
        $apply_ids = ExamExaminee::find()->select('apply_id')->where(['exam_site_id' => $exam_site_id])->column();
        if (!$apply_ids)
        {
            return $this->error('没有可下载文件');
        }
        $files = Apply::find()->select('bm')->where(['id' => $apply_ids])->column();
        if (!$files)
        {
            return $this->error('没有可下载文件');
        }
        $rootPath = \Yii::getAlias("@app");
        $fileFolder = $rootPath . "/file/temporary/";
        $zip = new \ZipArchive();

        $zipname = $fileFolder . $this->msectime() . ".zip";

        if (file_exists($zipname)){
            unlink($zipname);
        }

        if ($zip->open($zipname, \ZipArchive::CREATE) !== TRUE) {
            echo "暂时无法下载,请稍后重试!\n";
        }
        foreach ($files as $one) {
            if (!$one)
            {
                continue;
            }
            if (file_exists(\Yii::getAlias("@app") . "/file/apply/{$one}.pdf")){
                $zip->addFile(\Yii::getAlias("@app") . "/file/apply/{$one}.pdf", "{$one}.pdf");
            }
        }
        $zip->close();
        header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: Binary");
        header("Content-Length: " . filesize($zipname));
        header("Content-Disposition: attachment; filename=\"" . basename($zipname) . "\"");
        readfile($zipname);
        //下载完成清除文件
        if (file_exists($zipname)){
            unlink($zipname);
        }
        exit();
    }

    //批量下载准考证
    public function actionKz()
    {
        $request = \Yii::$app->request;
        $exam_site_id = $request->get('exam_site_id'); //考场ID
        if (!$exam_site_id)
        {
            return $this->error('参数错误');
        }
        $apply_ids = ExamExaminee::find()->select('apply_id')->where(['exam_site_id' => $exam_site_id])->column();
        if (!$apply_ids)
        {
            return $this->error('没有可下载文件');
        }
        $files = Apply::find()->select('kz')->where(['id' => $apply_ids])->column();
        if (!$files)
        {
            return $this->error('没有可下载文件');
        }

        $rootPath = \Yii::getAlias("@app");
        $fileFolder = $rootPath . "/file/temporary/";
        $zip = new \ZipArchive();

        $zipname = $fileFolder . $this->msectime() . ".zip";

        if (file_exists($zipname)){
            unlink($zipname);
        }

        if ($zip->open($zipname, \ZipArchive::CREATE) !== TRUE) {
            echo "暂时无法下载,请稍后重试!\n";
        }
        foreach ($files as $one) {
            if (!$one)
            {
                continue;
            }
            if (file_exists(\Yii::getAlias("@app") . "/file/exam/{$one}.pdf")){
                $zip->addFile(\Yii::getAlias("@app") . "/file/exam/{$one}.pdf", "{$one}.pdf");
            }
        }
        $zip->close();
        header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: Binary");
        header("Content-Length: " . filesize($zipname));
        header("Content-Disposition: attachment; filename=\"" . basename($zipname) . "\"");
        readfile($zipname);
        //下载完成清除文件
        if (file_exists($zipname)){
            unlink($zipname);
        }
        exit();
    }

    //批量下载照片
    public function actionZp()
    {
        $request = \Yii::$app->request;
        $exam_site_id = $request->get('exam_site_id'); //考场ID
        if (!$exam_site_id)
        {
            return $this->error('参数错误');
        }
        $apply_ids = ExamExaminee::find()->select('apply_id')->where(['exam_site_id' => $exam_site_id])->column();
        if (!$apply_ids)
        {
            return $this->error('没有可下载文件');
        }

        $apply = Apply::find()->where(['id' => $apply_ids])->asArray()->all();
        if (!$apply)
        {
            return $this->error('没有可下载文件');
        }

        $rootPath = \Yii::getAlias("@app");
        $fileFolder = $rootPath . "/file/temporary/";
        $zip = new \ZipArchive();

        $zipname = $fileFolder . $this->msectime() . ".zip";

        if (file_exists($zipname)){
            unlink($zipname);
        }

        if ($zip->open($zipname, \ZipArchive::CREATE) !== TRUE) {
            echo "暂时无法下载,请稍后重试!\n";
        }
        foreach ($apply as $one)
        {
            if (!$one)
            {
                continue;
            }
            $md5 = Image::find()->select('md5')->where(['id' => $one['picture_id']])->limit(1)->scalar();
            if($md5)
            {
                $f = \Yii::getAlias("@app") . Image::createUrl($md5);
                if (file_exists($f))
                {
                    $zip->addFile($f, "{$one['apply_no']}.jpg");
                }
            }
        }
        $zip->close();
        header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: Binary");
        header("Content-Length: " . filesize($zipname));
        header("Content-Disposition: attachment; filename=\"" . basename($zipname) . "\"");
        readfile($zipname);
        //下载完成清除文件
        if (file_exists($zipname)){
            unlink($zipname);
        }
        exit();
    }

    public function actionTest()
    {
        $data = [['1','2'],['2','3']];
        Excel::ExportPro(['测试1', '测试2'], $data, '测试-'.date('YmdHis'), 3);
    }

    public function actionSiteApplyList()
    {
        $request = \Yii::$app->request;
        $examSiteId = $request->get('id'); // 考场id

        if (!$examSiteId) {
            return $this->error('参数错误');
        }
        // 获取考场信息
        $examSite = ExamSite::findOne($examSiteId);
        if (!$examSite) {
            return $this->error('没有查询到考场信息');
        }
        $title = "{$examSite->exam->name}名单\n（{$examSite->exam->exam_time_start} {$examSite->room}）";
        $fileName = "{$examSite->exam->name}名单";
        $header = ['序号', '姓名', '性别', '出生', '民族', '报考级别',
            '报考专业', '联系电话', '指导老师', '交费单号', '考试时间', '备注'];
        // 获取考生信息
        $applies = Apply::find()
            ->with('pay')
            ->where(['exam_site_id1' => $examSiteId])
            ->orWhere(['exam_site_id2' => $examSiteId])
            ->all();
        $list = [];
        if ($applies) {
            foreach ($applies as $apply) {
                $list[] = [
                    $apply->apply_no, $apply->name, $apply->sex, $apply->birth, $apply->nation, $apply->level,
                    $apply->domain, $apply->phone, $apply->adviser, $apply->pay->number ?? '', $examSite->exam_time, ''
                ];
            }
        }
        Excel::ExportPro($header, $list, $title, 3, $fileName);
        exit();
    }

    //返回当前的毫秒时间戳-用于做压缩文件名
    public function msectime() {
        list($msec, $sec) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
    }
}
