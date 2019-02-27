<?php

namespace app\controllers;


class DefaultController extends Controller
{
    public function actionIndex()
    {
        $response = \Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_HTML;
        return $this->renderFile('@webroot/home/index.html');
    }

}
