<?php

namespace app\commands;


use app\models\Admin;

class TestController extends Controller
{

    public function actionAdmin()
    {
        $admin = new Admin();
        $admin->name = '超级管理员';
        $admin->identity = '超级管理员';
        $admin->username = 'admin';
        $admin->setPassword('123456');
        $admin->apply = 1;
        $admin->exam = 1;
        $admin->msg = 1;
        $admin->inform = 1;
        $admin->admin = 1;
        $admin->save(false);
        echo '添加成功';
    }

    //下载pdf,打包下载zip
    public function actionZip($files)
    {
        $rootPath = \Yii::getAlias('@app');
        $fileFolder = $rootPath . "/file/temporary/";
        $zip = new \ZipArchive();

        $zipname = $fileFolder . time() . ".zip";

        if (file_exists($zipname)){
            unlink($zipname);
        }

        if ($zip->open($zipname, \ZipArchive::CREATE) !== TRUE) {
            echo "暂时无法下载,请稍后重试!\n";
        }
        foreach ($files as $one) {
            if (file_exists(\Yii::getAlias("@app") . "/file/apply/{$one}.pdf")){
                $zip->addFile(\Yii::getAlias("@app") . "/file/apply/{$one}.pdf");
            }
        }
        $zip->close();
        header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: Binary");
        header("Content-Length: " . filesize($zipname));
        header("Content-Disposition: attachment; filename=\"" . basename($zipname) . "\"");
        readfile($zipname);
        exit();
    }

}