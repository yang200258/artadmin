<?php
namespace app\controllers;
use app\models\User;

class LoginController extends Controller
{
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
            'username' => $user->username ? $user->username : $user->nick_name,
            'type' => $user->type
        ]);
    }

    public function actionGetState()
    {
        $state  = md5(uniqid(rand(), TRUE));  //--微信登录-----生成唯一随机串防CSRF攻击
        $session = \Yii::$app->session;
        $session->set('wx_state',$state); //存到SESSION
        return $this->json(['state' => $state]);
    }

    //微信回调
    public function actionWxCallBack(){
//        $session = \Yii::$app->session;
//        return $this->json(['state' => $session->get('wx_state')]);
        $request = \Yii::$app->request;
        $code = $request->get('code');
        $state = $request->get('state');
//        $this->log('微信：' . $state);
//        $this->log('session:' . $session->get('wx_state'));
//        if ($state != $session->get('wx_state'))
//        {
//            $this->log('校验失败');
//            return false;
//        }

        $weixin = \Yii::$app->params['weixin'];
        $url='https://api.weixin.qq.com/sns/oauth2/access_token?appid='. $weixin['home_appid'] .'&secret='. $weixin['home_appsecret'] .'&code='. $code .'&grant_type=authorization_code';
        $arr = file_get_contents($url);
        $this->log($arr);
        $arr = json_decode($arr, true);
        //得到 access_token 与 openid
        $url='https://api.weixin.qq.com/sns/userinfo?access_token='.$arr['access_token'].'&openid='.$arr['openid'].'&lang=zh_CN';
        $user_info = file_get_contents($url);
        $this->log($user_info);
        $result = json_decode($user_info, true);
        $unionid = $result['unionid'];
        $nickname= $result['nickname'];
        if (!$unionid)
        {
            return false;
        }

        $user = User::findOne(['union_id' => $unionid]);
        if (!$user){
            $user = new User();
            $user->union_id = $unionid;
            $user->create_at = date("Y-m-d h:i:s",time());
        }
        $user->nick_name = $nickname;
//        $user->home_openid = $openid;
        $user->generateAuthKey();
        if (!$user->save(false))
        {
            $this->log('更新token失败');
            return false;
        }
        //设置缓存
        \Yii::$app->cache->set($state, $user->token, 600);
        $this->log('登录回调成功');
        return '微信登录成功';
    }

    //轮询请求，获得登录token
    public function actionGetToken()
    {
        $request = \Yii::$app->request;
//        $code = $request->get('code');
        $state = $request->post('state');
//        $session = \Yii::$app->session;
//        $state = $session->get('wx_state');
        $token = \Yii::$app->cache->get($state);
        if (!$token || !$state)
        {
            return $this->json(['token' => '']);
        }
        $user = User::findOne(['token' => $token]);
        return $this->json([
            'token' => $user->token,
            'username' => $user->username ? $user->username : $user->nick_name,
            'type' => $user->type
        ]);
    }

    protected function log($message)
    {
        $file = \Yii::getAlias("@app") . "/runtime/logs/" . "pay.log";
        file_put_contents($file, date("Y-m-d H:i:s") . ":" . $message . "\n", FILE_APPEND);
    }
}
