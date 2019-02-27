<?php

namespace app\modules\mini\controllers;
use app\components\GlobalFunc;
use app\models\User;


/**
 * Class Controller
 * @property User $user
 * @property integer $userid
 */
class Controller extends \yii\rest\Controller
{
    use GlobalFunc;
    public $user;
    public $userid;
    public $pn;
    public $limit;
    public $offset;

    public function init()
    {
        parent::init();
        date_default_timezone_set('PRC');
    }

    /**
     * @param \yii\base\Action $action
     * @return bool
     * 用户登录态维护
     */
    public function beforeAction($action)
    {

        $beforeAction = parent::beforeAction($action);
        // 首次登录时不需要验证登录状态
        if (in_array($this->id, ['login', 'search']) || ($this->id == 'pay' && $this->action->id == 'notify'))
        {
            return $beforeAction;
        }

        $sessionArray = $this->retrieveSession();
        if (!$sessionArray){
            $this->sendLoginErrorResponse();
            return false;
        }else{
            $this->user = User::getUserByOpenid($sessionArray['openid']);
            if (!$this->user){
                $this->sendLoginErrorResponse();
                return false;
            }
            $this->userid = $this->user->id;
            return $beforeAction;
        }
    }

    /**
     * 登录失败时返回错误信息。
     */
    public function sendLoginErrorResponse()
    {
        $response = \Yii::$app->response;
//        $response->format = Response::FORMAT_JSON;
        $response->data = $this->error('登录失败', 401);
        $response->send();
    }

    /**
     * @return array|mixed
     * 从缓存中提取 session 信息
     */
    public function retrieveSession()
    {
        $sessionKey = \Yii::$app->request->getBodyParam("token", '');
        if (!$sessionKey){
            return [];
        }

        $sessionVal = \Yii::$app->cache->get($sessionKey);
        if ($sessionVal){
            return $sessionVal;
        }else{
            return [];
        }
    }
    /**
     * @param $unit
     * @return array
     */
    public function getUnit($unit)
    {
        $unitArray = \Yii::$app->params['units'];
        return isset($unitArray[$unit]) ? $unitArray[$unit] : [];
    }

    public function init_page()
    {
        $request = \Yii::$app->request;
        $pn = intval($request->post('pn'));
        if ($pn <= 0)
        {
            $pn = 1;
        }
        $this->pn = $pn;

        $limit = intval($request->post('limit'));
        $this->limit = $limit ? $limit : 10;
        $this->offset = ($pn - 1) * $this->limit;
    }

    public function page($total)
    {
        $page = [];
        $page['total'] = intval($total);
        $page['total_page'] = ceil($total / $this->limit);
        $page['limit'] = intval($this->limit);
        $page['pn'] = intval($this->pn);
        $page['is_end'] = ($this->pn >=  $page['total_page']) ? true : false;
        return $page;
    }
}
