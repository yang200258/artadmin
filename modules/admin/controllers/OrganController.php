<?php

namespace app\modules\admin\controllers;


use app\models\User;

class OrganController extends Controller
{
    public function actionIndex()
    {
       $user = User::find()->select('id, organ_name')->where(['type' => User::TYPE_ORGAN])->asArray()->all();
       return $this->json($user);
    }
}
