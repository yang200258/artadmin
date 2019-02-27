<?php
namespace app\modules\adminapi\controllers;


use app\models\Apply;
use app\models\Image;

class DownloadController extends Controller
{
    //批量下载报名表
    public function actionBm()
    {
        $files = Apply::find()->select('bm')->column();
        $rootPath = \Yii::getAlias('@root');
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
            if (file_exists(\Yii::getAlias("@root") . "/file/apply/{$one}.pdf")){
                $zip->addFile(\Yii::getAlias("@root") . "/file/apply/{$one}.pdf", "{$one}.pdf");
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
        $files = Apply::find()->select('kz')->column();
        $rootPath = \Yii::getAlias('@root');
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
            if (file_exists(\Yii::getAlias("@root") . "/file/exam/{$one}.pdf")){
                $zip->addFile(\Yii::getAlias("@root") . "/file/exam/{$one}.pdf", "{$one}.pdf");
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
        $apply = Apply::find()->asArray()->all();

        $rootPath = \Yii::getAlias('@root');
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
                $f = \Yii::getAlias("@root") . Image::createUrl($md5);
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





    //返回当前的毫秒时间戳-用于做压缩文件名
    public function msectime() {
        list($msec, $sec) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
    }
}
