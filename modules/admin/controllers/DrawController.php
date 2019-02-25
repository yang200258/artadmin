<?php
namespace app\modules\admin\controllers;



use app\models\Pc28Draw;

class DrawController extends Controller
{
    //pc28开奖列表
    public function actionPc28()
    {
        $this->init_page();

        $model = Pc28Draw::find()->andWhere(['type' => Pc28Draw::TYPE_FALSE]);

        $total = $model->count();
        $list = $model->select('id, number, one, two, three, pc_sum')
            ->orderBy('id desc')
            ->offset($this->offset)->limit($this->limit)->asArray()->all();

        return $this->json(['list' => $list, 'page' => $this->page($total)]);
    }

    //快3开奖列表
    public function actionKuai3()
    {

    }

    //赛马开奖列表
    public function actionHorse()
    {

    }

    //时时彩开奖列表
    public function actionEvery()
    {

    }

    //自行车赛开奖列表
    public function actionBicycle()
    {

    }
}
