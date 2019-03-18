<?php

namespace app\modules\mini\controllers;

use app\helpers\WeiXin;
use app\models\User;
use Yii;

class LoginController extends Controller
{
    public function actionIndex()
    {
        $request = \Yii::$app->request;
        $code = $request->getBodyParam('code', '');
        $sessionKeyArr = WeiXin::getSessionKey($code);
        if (!$sessionKeyArr) {
            return $this->error('获取登录态失败', 401);
        }else{
            $this->log(json_encode($sessionKeyArr), 'sessionKey');
            if (isset($sessionKeyArr['union_id']))
            {
                $user = User::findOne(['union_id' => $sessionKeyArr['union_id']]);
                if (!$user)
                {
                    return $this->error('登录失败', 401);
                }
                $user->openid = $sessionKeyArr['openid'];
                if (!$user->save(false)){
                    return $this->error('保存用户信息失败', 401);
                }
                $sessionString = md5($sessionKeyArr['openid'] . $sessionKeyArr['session_key']);
                \Yii::$app->cache->set($sessionString, $sessionKeyArr, 3600 * 24 * 30);
                $user->token = $sessionString;
                $user->save(false);
                return $this->json(['token' => $sessionString, 'username' => $user->username ? $user->username : $user->nick_name, 'avatar' => $user->avatar,'union_id' => $user->union_id ? true : false]);
            }else  //不存在的话需解密获得unionId
            {
                //如果没有unionId，需解密获得unionId
                $iv = $request->getBodyParam('iv');
                $encryptedData = $request->getBodyParam('encryptedData');
                if (!$iv || !$encryptedData)
                {
                    return $this->error('需要授权信息', 401);
                }
                require_once(\Yii::getAlias('@app') . "/components/wx_decode/wxBizDataCrypt.php");
                $weiXinConfig = \Yii::$app->params['weixin'];
                $pc = new \WXBizDataCrypt($weiXinConfig['appid'], $sessionKeyArr['session_key']);
                $errCode = $pc->decryptData($encryptedData, $iv, $data );
                $this->log($errCode, 'errCode');
                $this->log($data, 'data');
                if ($errCode == 0)
                {
                    $data = json_decode($data);
                    $user = User::findOne(['union_id' => $data->unionId]);
                    if (!$user){
                        $user = new User();
                        $user->openid = $sessionKeyArr['openid'];
                        $user->nick_name = preg_replace('/[\x00-\x1F\x7F-\x9F]/u', '', $data->nickName);  // 过滤控制字符
                        $user->union_id= $data->unionId;
                        $user->avatar = $data->avatarUrl;
                        $user->create_at = date("Y-m-d h:i:s",time());
                        if (!$user->save(false)){
                            return $this->error('用户创建失败', 401);
                        }
                    } elseif (!$user->openid) {
                        $user->openid = $sessionKeyArr['openid'];
                        $user->save(false);
                    }
                    $sessionString = md5($sessionKeyArr['openid'] . $sessionKeyArr['session_key']);
                    \Yii::$app->cache->set($sessionString, $sessionKeyArr, 3600 * 24 * 30);
                    $user->token = $sessionString;
                    $user->save(false);
                    return $this->json(['token' => $sessionString, 'username' => $user->username ? $user->username : $user->nick_name, 'avatar' => $user->avatar,'union_id' => $user->union_id ? true : false]);
                }
                return $this->error('获取登录态失败:' . $errCode, 401);
            }
        }
    }


    protected function log($message, $level = 'info')
    {
        $file = \Yii::getAlias("@app") . "/runtime/logs/" . "pay.log";
        file_put_contents($file, date("Y-m-d H:i:s") . "[$level]:" . $message . "\n", FILE_APPEND);
    }
}
