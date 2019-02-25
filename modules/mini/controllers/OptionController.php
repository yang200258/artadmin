<?php
namespace app\modules\mini\controllers;


class OptionController extends Controller
{
    public function actionIndex()
    {
        $option = require(\Yii::getAlias('@app') . '/config/option.php');
        return $this->json($option);
    }
}
