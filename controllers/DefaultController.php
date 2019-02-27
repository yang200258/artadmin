<?php

namespace app\controllers;


class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->renderFile('@webroot/client/home/dist/index.html');
    }

}
