<?php
namespace app\modules\admin\controllers;


use app\models\User;

class CommonController extends Controller
{
    public function actionOption()
    {
        $option = require(dirname(__DIR__) . '/../common/config/option.php');
        return $this->json($option);
    }

    public function actionOrgan()
    {
        $user = User::find()->select('id, organ_name')->where(['type' => User::TYPE_ORGAN])->asArray()->all();
        return $this->json($user);
    }
}
