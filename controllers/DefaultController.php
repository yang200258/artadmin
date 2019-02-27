<?php

namespace app\controllers;


class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->renderFile('@webroot/home/index.html');
    }

}
