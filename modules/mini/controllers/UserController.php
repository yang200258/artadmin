<?php

namespace app\modules\mini\controllers;


use app\models\InformUser;

class UserController extends Controller
{
    // 用户中心
    public function actionIndex()
    {
        $unread_num = InformUser::find()->where(['uid' => $this->userid, 'read' => 0])->count();
        $data =[
            'nick_name' => $this->user->nick_name,
            'avatar' => $this->user->avatar,
            'unread_num' => $unread_num,
            'about_us_url' => $this->createMiniUrl('/miniappus')
        ];
        return $this->json($data);
    }

    public function actionUnionid()
    {
        if ($this->user->union_id)
        {
            return $this->json(true);
        }
        return $this->json(false);
    }

    //保存微信用户信息
//    public function actionSavemes()
//    {
//        $request = \Yii::$app->request;
//        $iv = $request->getBodyParam('iv');
//        $encryptedData = $request->getBodyParam('encryptedData');
//
//        $sessionKeyArr = $this->retrieveSession();
//        require_once(\Yii::getAlias('@app') . "/components/wx_decode/wxBizDataCrypt.php");
//        $weiXinConfig = \Yii::$app->params['weixin'];
//        $pc = new \WXBizDataCrypt($weiXinConfig['appid'], $sessionKeyArr['session_key']);
//        $errCode = $pc->decryptData($encryptedData, $iv, $data );
//        $this->log('ceshi');
//        $this->log($errCode);
//        if ($errCode == 0) {
//            $user = $this->user;
//            if($user){
//                $data = json_decode($data);
//                $user->nick_name = preg_replace('/[\x00-\x1F\x7F-\x9F]/u', '', $data->nickName);  // 过滤控制字符
//                $user->union_id= $data->unionId;
//                $user->avatar = $data->avatarUrl;
//                if (!$user->save(false)) {
//                    return $this->error('保存用户信息失败');
//                }
//                return $this->json(['nick_name' => $user->nick_name, 'avatar' => $data->avatarUrl]);
//            }
//            return $this->error('找不到用户');
//        }
//        return $this->error('获取用户信息失败');
//    }

    protected function log($message)
    {
        $file = \Yii::getAlias("@app") . "/runtime/logs/" . "pay.log";
        file_put_contents($file, date("Y-m-d H:i:s") . ":" . $message . "\n", FILE_APPEND);
    }
}
