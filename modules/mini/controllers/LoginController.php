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

            if ($sessionKeyArr['unionid'])
            {
                $user = User::findOne(['union_id' => $sessionKeyArr['unionid']]);
                if (!$user){
                    $user = new User();
                    $user->openid = $sessionKeyArr['openid'];
                    $user->union_id = $sessionKeyArr['unionid'];
                    $user->create_at = date("Y-m-d h:i:s",time());
                    if (!$user->save(false)){
                        return $this->error('用户创建失败', 401);
                    }
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
                require_once(\Yii::getAlias('@app') . "/components/wx_decode/wxBizDataCrypt.php");
                $weiXinConfig = \Yii::$app->params['weixin'];
                $pc = new \WXBizDataCrypt($weiXinConfig['appid'], $sessionKeyArr['session_key']);
                $errCode = $pc->decryptData($encryptedData, $iv, $data );

                if ($errCode == 0)
                {
                    $user = User::findOne(['union_id' => $sessionKeyArr['unionid']]);
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
                    }
                    $sessionString = md5($sessionKeyArr['openid'] . $sessionKeyArr['session_key']);
                    \Yii::$app->cache->set($sessionString, $sessionKeyArr, 3600 * 24 * 30);
                    $user->token = $sessionString;
                    $user->save(false);
                    return $this->json(['token' => $sessionString, 'username' => $user->username ? $user->username : $user->nick_name, 'avatar' => $user->avatar,'union_id' => $user->union_id ? true : false]);
                }

            }
        }
    }
}
