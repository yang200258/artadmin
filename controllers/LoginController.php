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
            'username' => $user->username,
            'type' => $user->type
        ]);
    }

    public function actionGetState()
    {
        $state  = md5(uniqid(rand(), TRUE));  //--微信登录-----生成唯一随机串防CSRF攻击
        \Yii::$app->session->set('wx_state',$state); //存到SESSION
        return $this->json(['state' => $state]);
    }

    //微信回调
    public function actionWxCallBack(){
        $session = \Yii::$app->session;
        $request = \Yii::$app->request;
        $code = $request->get('code');
        $state = $request->get('state');
        if ($state != $session["wx_state"])
        {
            $this->log('校验失败');
            return false;
        }

        $weixin = \Yii::$app->params['weixin'];
        $url='https://api.weixin.qq.com/sns/oauth2/access_token?appid='. $weixin['home_appid'] .'&secret='. $weixin['home_appsecret'] .'&code='. $code .'&grant_type=authorization_code';
        $arr = file_get_contents($url);
        $this->log($arr);
        $arr = json_decode($arr, true);
        $unionid = isset($arr['unionid']) ? $arr['unionid'] : '';
        $openid = $arr['openid'];
        if (!$unionid)
        {
            //得到 access_token 与 openid
            $url='https://api.weixin.qq.com/sns/userinfo?access_token='.$arr['access_token'].'&openid='.$arr['openid'].'&lang=zh_CN';
            $user_info = file_get_contents($url);
            $this->log($user_info);
            $result = json_decode($user_info, true);
            $unionid = $result['unionid'];
            $openid = $result['openid'];
            if (!$unionid)
            {
                return false;
            }
        }
        $user = User::findOne(['unionid' => $unionid]);
        if (!$user){
            $user = new User();
            $user->home_openid = $openid;
            $user->union_id = $unionid;
            $user->create_at = date("Y-m-d h:i:s",time());
            if (!$user->save(false)){
                $this->log('创建用户失败');
                return false;
            }
        }
        $user->generateAuthKey();
        if (!$user->save(false))
        {
            $this->log('更新token失败');
            return false;
        }
        //设置缓存
        \Yii::$app->cache->set($state, $user->token, 60);
        return true;
    }

    //轮询请求，获得登录token
    public function actionGetToken()
    {
        $request = \Yii::$app->request;
        $state = $request->get('state');
        if (!$state)
        {
            return $this->error('参数错误');
        }

        $token = \Yii::$app->cache->get($state);
        if (!$token)
        {
            return $this->json(['token' => '']);
        }
        return $this->json(['token' => $token]);
    }

    protected function log($message)
    {
        $file = \Yii::getAlias("@app") . "/runtime/logs/" . "pay.log";
        file_put_contents($file, date("Y-m-d H:i:s") . ":" . $message . "\n", FILE_APPEND);
    }
}
