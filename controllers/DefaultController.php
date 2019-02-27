<?php

namespace app\controllers;


class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->renderFile(\Yii::getAlias('@app') . '/client/home/dist/index.html');
    }

}
