<?php
namespace app\modules\admin\controllers;


class OptionController extends Controller
{
    public function actionIndex()
    {
        $option = require(dirname(__DIR__) . '/../common/config/option.php');
        return $this->json($option);
    }
}
