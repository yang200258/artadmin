<?php

namespace app\controllers;


use app\models\Apply;
use app\models\ApplyPay;
use app\models\Inform;
use app\models\InformUser;
use yii\db\Expression;
use yii\helpers\Url;

//官网Native支付
class PayController extends Controller
{
    public function actionIndex()
    {
        $request = \Yii::$app->request;
        $apply_id = $request->getBodyParam('apply_id');

        $apply = Apply::find()->with('pay')->where(['id' => $apply_id])->asArray()->one();
        if (!$apply)
        {
            return $this->error('报名不存在');
        }
        if ($apply['plan'] != 2)
        {
            return $this->error('您无需缴费');
        }

        require_once(\Yii::getAlias('@app') . "/components/WxpayAPI/lib/WxPay.Api.php");
        require_once(\Yii::getAlias('@app') . "/components/WxpayAPI/example/WxPay.NativePay.php");

        $order_no = $apply['apply_no'];
        $money = (int)round($apply['pay']['price']);
        if($money < 1)
        {
            return  $this->error('支付金额不能少于1分钱');
        }
        //②、统一下单
        $weixin = \Yii::$app->params['weixin'];
        $input = new \WxPayUnifiedOrder();
        $input->SetAppid($weixin['appid']);//公众账号ID
        $input->SetProduct_id($apply_id);  //商品ID，trade_type=NATIVE时，此参数必传。此参数为二维码中包含的商品ID，商户自行定义。
        $input->SetBody("艺术考级海南账户支付");
        $input->SetAttach("艺术考级海南考区账户支付");
        $input->SetOut_trade_no($order_no);
        $input->SetTotal_fee($money);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("test");
        $input->SetNotify_url(Url::toRoute(['/pay/notify'], true));  //异步通知路径
        $input->SetTrade_type("NATIVE");

        $notify = new \NativePay();
        $result = $notify->GetPayUrl($input);
        $code_url = $result["code_url"];
        $payurl = urlencode($code_url);

        $tv = [];
        $tv['payurl'] = $payurl;
        $tv['apply'] = $apply;
        return $this->json($tv);
    }


    //扫码支付回调
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
                    Apply::updateAll(['plan' => 4], ['id' => $apply['id']]);

                    $content = '您已成功报名中国音乐学院社会艺术水平考级考试！ 关注微信公众号“海南考级中心”及时获取更多考试相关信息';
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

    //二维码链接输出，get传
    public function actionQrcode()
    {
        $data = \Yii::$app->request->get('data', '');
        $url = urldecode($data);
        ob_get_clean();
        \QRcode::logopng($url, false, QR_ECLEVEL_H, 6,4,false);
    }


    //轮询查询订单接口，返回支付是否成功信息
    public function actionQueryorder()
    {
        $request = \Yii::$app->request;
        $apply_id = $request->post('apply_id', 0);

        if (!$apply_id) {
            return $this->error('订单错误');
        }
        $apply_pay = ApplyPay::findOne(['apply_id' => $apply_id]);

        if (!$apply_pay) {
            return $this->error('订单错误');
        }
        if (!$apply_pay->status){
            return $this->error('未缴费');
        }

        return $this->ok('订单完成');
    }

}
