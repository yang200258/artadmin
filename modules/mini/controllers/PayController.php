<?php

namespace app\modules\mini\controllers;


use app\models\Apply;
use app\models\ApplyPay;
use app\models\Inform;
use app\models\InformUser;
use EasyWeChat\Factory;
use yii\db\Expression;
use yii\helpers\Url;

class PayController extends Controller
{
    public function actionIndex()
    {
        $request = \Yii::$app->request;
        $apply_id = $request->getBodyParam('apply_id');

        $apply = Apply::find()->with('pay')->where(['id' => $apply_id])->one();
        if (!$apply)
        {
            return $this->error('报名不存在');
        }
        if ($apply['plan'] != 2)
        {
            return $this->error('您无需缴费');
        }

        require_once(\Yii::getAlias('@app') . "/components/WxpayAPI/lib/WxPay.Api.php");
        require_once(\Yii::getAlias('@app') . "/components/WxpayAPI/example/WxPay.JsApiPay.php");


        $openId = $this->user->openid;
        $order_no = $apply['apply_no'];
        $money = (int)round($apply['pay']['price']);
        if($money < 1)
        {
            return  $this->error('支付金额不能少于1分钱');
        }
        $weixin = \Yii::$app->params['weixin'];
        $input = new \WxPayUnifiedOrder();
        $input->SetAppid($weixin['appid']);//公众账号ID
        $input->SetBody("艺术考级海南账户支付");
        $input->SetAttach("艺术考级海南考区账户支付");
        $input->SetOut_trade_no($order_no);
        $input->SetTotal_fee($money);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("test");
        $input->SetNotify_url('https://www.hnyskj.net/mini/pay/notify');
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
        $order = \WxPayApi::unifiedOrder($input);

        if($order['return_code'] != 'SUCCESS' || $order['result_code'] != 'SUCCESS' || !isset($order['prepay_id']))
        {
            return $this->error('支付失败');
        }
        $apply->pay->prepay_id = $order['prepay_id'];
        $apply->pay->save(false);
        $tools = new \JsApiPay();
        $jsApiParameters = $tools->GetJsApiParameters($order);

        return $this->json($jsApiParameters);
    }


    public function actionNotify()
    {
        ini_set('date.timezone','Asia/Shanghai');
        require_once(\Yii::getAlias('@app') . "/components/WxpayAPI/lib/WxPay.Api.php");
        require_once(\Yii::getAlias('@app') . "/components/WxpayAPI/lib/WxPay.Notify.php");
        require_once(\Yii::getAlias('@app') . "/components/WxpayAPI/example/notify.php");
        require_once(\Yii::getAlias('@app') . "/components/WxpayAPI/lib/WxPay.Exception.php");
        require_once(\Yii::getAlias('@app') . "/components/WxpayAPI/lib/WxPay.Config.php");
        require_once(\Yii::getAlias('@app') . "/components/WxpayAPI/lib/WxPay.Data.php");

        //获取通知的数据
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        if (!$xml)
        {
            $xml = file_get_contents("php://input");
        }
        //如果返回成功则验证签名
        try {
            $result = \WxPayResults::Init($xml);
        } catch (\WxPayException $e){
            $msg = $e->errorMessage();
            return false;
        }
        //查询订单
        $weixin = \Yii::$app->params['weixin'];
        $input = new \WxPayOrderQuery();
        $input->SetAppid($weixin['appid']);//公众账号ID
        $input->SetTransaction_id($result['transaction_id']);
        $result = \WxPayApi::orderQuery($input);
        $apply = Apply::findOne(['apply_no' => $result['out_trade_no']]);
        if (!$apply)
        {
            return false;
        }
        if($apply->plan != 4) {
            if ($result['trade_state'] == "SUCCESS") {
                $transaction = \Yii::$app->db->beginTransaction();//创建事务
                try {

                    $apply_pay = ApplyPay::findOne(['apply_id' => $apply['id']]);
                    $apply_pay->number = $result['out_trade_no'];
                    $apply_pay->type = 1;
                    $apply_pay->status = 1;
                    $apply_pay->pay_time = date('Y-m-d H:i:s', time());
                    if (!$apply_pay->save(false))
                    {
                        $transaction->rollback();   //回滚事务
                        return $this->error('支付失败');
                    }
                    Apply::updateAll(['plan' => 4], ['id' => $apply->id]);

                    $content = '您已成功报名中国音乐学院社会艺术水平考级考试！ 关注微信公众号“海南考级中心”及时获取更多考试相关信息';
                    //
                    if ($apply_pay->prepay_id) {
                        $miniApp = Factory::miniProgram(\Yii::$app->params['weixin_mini']);
                        $miniApp->template_message->send([
                            'touser' => $apply->user->openid,
                            'template_id' => \Yii::$app->params['weixin_mini_template']['pay'],
                            'page' => 'pages/myenroll/myenroll',
                            'form_id' => $apply_pay->prepay_id,
                            'data' => [
                                'keyword1' => '中国音乐学院社会艺术水平考级（海南考区）' . $apply->exam->name . $apply->domain . $apply->level . '报名',
                                'keyword2' => '报名成功',
                                'keyword3' => $content,
                            ],
                        ]);
                    }
                    $inform = new Inform();
                    $inform->type = 9;
                    $inform->content = new Expression("COMPRESS(:content)", [':content' => $content]);
                    if (!$inform->save(false))
                    {
                        $transaction->rollback();//回滚事务
                        return $this->error('创建通知失败');
                    }
                    $inform_user = new InformUser();
                    $inform_user->uid = $apply->uid;
                    $inform_user->inform_id = $inform->id;
                    $inform_user->apply_id = $apply->id;
                    if (!$inform_user->save(false))
                    {
                        $transaction->rollback();//回滚事务
                        return $this->error('创建失败');
                    }
                    $transaction->commit();//提交事务

                    echo "success";

                } catch (\Exception $e) {
                    \Yii::error($e->getMessage());
                    $transaction->rollback();//回滚事务
                    return $this->error('服务器繁忙，请稍后再试！');
                }
            }
        }
    }


    protected function log($message)
    {
        $file = \Yii::getAlias("@app") . "/runtime/logs/" . "pay.log";
        file_put_contents($file, date("Y-m-d H:i:s") . ":" . $message . "\n", FILE_APPEND);
    }
}
