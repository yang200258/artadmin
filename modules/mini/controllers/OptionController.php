<?php
namespace app\modules\mini\controllers;


class OptionController extends Controller
{
    public function actionIndex()
    {
        $option = require(dirname(__DIR__) . '/../config/option.php');
        return $this->json($option);
    }
}
