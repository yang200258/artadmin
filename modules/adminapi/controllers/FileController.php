<?php
namespace app\modules\adminapi\controllers;


class FileController extends Controller
{
    public function actionBm()
    {
        $request = \Yii::$app->request;
        $name = $request->get('name');
        $file = \Yii::getAlias("@root") . "/file/apply/{$name}.pdf";
        $fp = fopen($file, "r");
        header("Content-type: application/pdf");
        fpassthru($fp);
        fclose($fp);
    }

    public function actionKz()
    {
        $request = \Yii::$app->request;
        $name = $request->get('name');
        $file = \Yii::getAlias("@root") . "/file/exam/{$name}.pdf";
        $fp = fopen($file, "r");
        header("Content-type: application/pdf");
        fpassthru($fp);
        fclose($fp);
    }

}
