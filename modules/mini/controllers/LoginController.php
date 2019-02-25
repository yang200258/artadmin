<?php

namespace app\modules\mini\controllers;

use common\helpers\WeiXin;
use app\models\User;
use Yii;

class LoginController extends Controller
{
    public function actionIndex()
    {
        $request = \Yii::$app->request;
        $code = $request->getBodyParam('code', '');
        $union_id = '';
        $username = '';
        $avatar = '';

        $sessionKeyArr = WeiXin::getSessionKey($code);
        if (!$sessionKeyArr) {
            return $this->error('获取登录态失败', 401);
        }else{
            $user = User::findOne(['openid' => $sessionKeyArr['openid']]);
            if (!$user){
                $user = new User();
                $user->openid = $sessionKeyArr['openid'];
                $user->create_at = date("Y-m-d h:i:s",time());
                if (!$user->save(false)){
                    return $this->error('用户创建失败', 401);
                }
            }else{
                $union_id= $user['union_id'];
                $username = $user['username'];
                $avatar = $user['avatar'];
            }
            $sessionString = md5($sessionKeyArr['openid'] . $sessionKeyArr['session_key']);
            \Yii::$app->cache->set($sessionString, $sessionKeyArr, 3600 * 24 * 30);
            $user->token = $sessionString;
            $user->save(false);
            return $this->json(['token' => $sessionString, 'username' => $username, 'avatar' => $avatar,'union_id' => $union_id ? true : false]);
        }
    }
}
