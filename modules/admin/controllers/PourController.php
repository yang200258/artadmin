<?php
namespace app\modules\admin\controllers;


use app\models\Pc28Pour;

class PourController extends Controller
{
    //期号ID   代理用户名称   普通用户名称   号码   赔率 购买金额   中奖金额 下注时间
    //pc28下注列表
    public function actionPc28()
    {
        $this->init_page();
        $request = \Yii::$app->request;
        $user_name = $request->post('user_name');

        $model = Pc28Pour::find()
            ->select('pc28_pour.draw_id,pc28_pour.odds, pc28_pour.status, user.username user_name, pc28_odds.name odds_name, pc28_pour.price, pc28_pour.win_price, pc28_pour.create_at')
            ->innerJoin('user', 'user.id = pc28_pour.uid')
            ->innerJoin('pc28_odds', 'pc28_odds.id = pc28_pour.odds_id')
            ->andWhere(['admin_id' => $this->admin->id])
            ->andFilterWhere(['user.username' => $user_name]);

        $total = $model->count();
        $list = $model
            ->orderBy('pc28_pour.id desc')
            ->offset($this->offset)->limit($this->limit)->asArray()->all();

        foreach ($list as &$one)
        {
            $one['odds'] =  $one['odds'] / 100;
            $one['price'] =  $one['price'] / 100;
            $one['win_price'] =  $one['win_price'] / 100;
        }

        return $this->json(['list' => $list, 'page' => $this->page($total)]);
    }
    
    //快3下注列表
    public function actionKuai3()
    {

    }

    //赛马下注列表
    public function actionHorse()
    {

    }

    //时时彩下注列表
    public function actionEvery()
    {

    }

    //自行车赛下注列表
    public function actionBicycle()
    {

    }
}
