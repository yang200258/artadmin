<?php

namespace app\modules\admin\controllers;


class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->renderFile('@webroot/manager/index.html');
    }

}
