<?php
namespace app\helpers;

use app\models\Token;
use EasyWeChat\Factory;

class WeiXin {

    public static function getAccessToken()
    {
        $t = Token::find()->orderBy('id DESC')->limit(1)->one();
        if ($t)
        {
            $time = strtotime(date('Y-m-d H:i:s')) - strtotime($t->timestamp);
            if ($time < 5400)
            {
                return $t->token;
            }
        }
        $weixin = \Yii::$app->params['weixin'];
        $token_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$weixin['home_appid']}&secret={$weixin['home_appsecret']}";
        $json = file_get_contents($token_url);
        $result = json_decode($json, true);
        if (isset($result['access_token']))
        {
            if (!$t)
            {
                $t = new Token();
            }
            $t->token = $result['access_token'];
            $t->create_at = date('Y-m-d H:i:s');
            $t->save(false);
            return $result['access_token'];
        }
        return '';
    }

    /**
     * @param $code
     * @return array|mixed
     */
    public static function getSessionKey($code)
    {
        if (!$code){
            return [];
        }

//        $weixin = \Yii::$app->params['weixin'];
//        $apiUrl = "https://api.weixin.qq.com/sns/jscode2session?appid={$weixin['appid']}&secret={$weixin['appsecret']}&js_code={$code}&grant_type=authorization_code";
//        $json = file_get_contents($apiUrl);
//        $result = json_decode($json, true);
        $app = Factory::miniProgram(\Yii::$app->params['weixin_mini']);
        $result = $app->auth->session($code);
        if (isset($result['openid'])){
            return $result;
        }else{
            return [];
        }
    }

    /**
     * @param $openid
     * @return string
     * 根据openid获取微信名称
     */
    public static function getNickname($openid)
    {
        $token = WeiXin::getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$token}&openid={$openid}&lang=zh_CN";
        $json = file_get_contents($url);
        if (!$json){
            return '';
        }

        $result = json_decode($json, true);
        return $result['nickname'];
    }

}