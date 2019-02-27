<?php

namespace app\controllers;


class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render(\Yii::getAlias('@app') . '/client/home/dist/index.html');
    }

}
