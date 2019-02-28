<?php

namespace app\modules\adminapi\controllers;


class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->renderFile('@webroot/admin/index.html');
    }

}
