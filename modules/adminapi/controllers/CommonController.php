<?php
namespace app\modules\adminapi\controllers;


use app\models\User;

class CommonController extends Controller
{
    public function actionOption()
    {
        $option = require(\Yii::getAlias('@app') . '/config/option.php');
        return $this->json($option);
    }

    public function actionOrgan()
    {
        $user = User::find()->select('id, organ_name')->where(['type' => User::TYPE_ORGAN])->asArray()->all();
        return $this->json($user);
    }
}
