<?php
namespace app\controllers;

use app\helpers\WeiXin;
use app\models\User;

class LoginController extends Controller
{
    private $callBackUrl = 'https://www.hnyskj.net/login/wx-call-back';
    private $appID = '';
    //账户密码登录
    public function actionIndex()
    {
        $request = \Yii::$app->request;
        $username = $request->post('username');
        $password = $request->post('password');
        $type = $request->post('type');

        $user = User::findOne(['username' => $username]);
        if (!$user)
        {
            return $this->error('用户不存在');
        }
        if ($type != $user->type)
        {
            return $this->error('用户不存在');
        }
        if (!$user->validatePassword($password))
        {
            return $this->error('密码错误，请重新输入');
        }

        if (!\Yii::$app->user->login($user, 3600))
        {
            return $this->error('登录失败, 请重试');
        }

        $user->generateAuthKey();
        if (!$user->save(false))
        {
            return $this->error('登录失败, 请重试');
        }

        return $this->json([
            'token' => $user->token,
            'username' => $user->username,
            'type' => $user->type
        ]);
    }

    public function actionWeixin()
    {
        $weixin = \Yii::$app->params['weixin'];
        $state  = md5(uniqid(rand(), TRUE));  //--微信登录-----生成唯一随机串防CSRF攻击
        \Yii::$app->session->set('wx_state',$state); //存到SESSION

        $callback = urlencode($this->callBackUrl);
        $wxurl = "https://open.weixin.qq.com/connect/qrconnect?appid="
            . $weixin['home_appid'] ."&redirect_uri="
            . $callback."&response_type=code&scope=snsapi_login&state="
            . $state ."#wechat_redirect";
        return $this->json($wxurl);
    }

    //微信回调
    public function actionWxCallBack(){
//        $session = \Yii::$app->session;
//        if($_GET['state']!=$session["wx_state"]){
//            echo 'sorry,网络请求失败...';
//            exit("5001");
//        }
        $request = \Yii::$app->request;
        $code = $request->get('code');
        $weixin = \Yii::$app->params['weixin'];
        $url='https://api.weixin.qq.com/sns/oauth2/access_token?appid='. $weixin['home_appid'] .'&secret='. $weixin['home_appsecret'] .'&code='. $code .'&grant_type=authorization_code';
        $arr = file_get_contents($url);
        //得到 access_token 与 openid
        $url='https://api.weixin.qq.com/sns/userinfo?access_token='.$arr['access_token'].'&openid='.$arr['openid'].'&lang=zh_CN';
        $user_info = file_get_contents($url);
        $this->log($user_info);
        $result = json_decode($user_info, true);
        return $result;

    }

    protected function log($message)
    {
        $file = \Yii::getAlias("@app") . "/runtime/logs/" . "pay.log";
        file_put_contents($file, date("Y-m-d H:i:s") . ":" . $message . "\n", FILE_APPEND);
    }
}
